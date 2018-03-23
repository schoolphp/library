<?php
$id = ($_GET['id'] ?? 0);
\Core::$TITLE = 'Группы • '.(isset($_GET['id']) ? 'Редактирование' : 'Создание');

if(isset($_GET['action'], $_GET['id']) && $_GET['action'] == 'delete' && $_GET['id'] != 1) {
    q("
		DELETE FROM `fw_users_groups` 
		WHERE `id` = " . (int)$_GET['id'] . "
	");
    q("
        INSERT INTO `fw_admin_actions` SET 
        `user` = ".(int)\User::$id.",
        `action` = 'delete',
        `a_table` = 'fw_users_groups',
        `value` = '".(int)$_GET['id']."'
    ");
    \Info::set('success', 'Группа успешно удалена');
    header("Location: /admin/users/groups-view");
    exit;
}

if(isset($_POST['title'],$_POST['allow'],$_POST['deny'])) {
	q("
		".($id ? 'UPDATE ' : 'INSERT INTO')." `fw_users_groups` SET
		`title` = '".es($_POST['title'])."'
		".($id ? 'WHERE `id` = '.(int)$id : '')."
	");

    q("
		INSERT INTO `fw_admin_actions` SET 
		`user` = ".(int)\User::$id.",
		`action` = '".(empty($id) ? 'create' : 'update')."',
		`a_table` = 'fw_users_groups',
		`value` = '".es($id)."'
	");

	if(empty($id)) {
		$id = \DB::_()->insert_id;
		\Info::set('success', 'Группа добавлена');
	}
	else {
		\Info::set('success', 'Группа отредактирована');
	}

	q("
		DELETE FROM `fw_users_groups_rights`
		WHERE `group_id` = ".(int)$id."
	");
	q("OPTIMIZE TABLE `fw_users_groups_rights`");

	if(!empty($_POST['allow'])) {
		$allow = explode("\r\n",$_POST['allow']);
		foreach($allow as $v) {
			if(!empty($v)) {
				q("
					INSERT INTO `fw_users_groups_rights` SET
					`group_id` = ".(int)$id.",
					`right` = '".es($v)."',
					`type` = 'allow'
				");
			}
		}
	}

	if(!empty($_POST['deny'])) {
		$deny = explode("\r\n",$_POST['deny']);
		foreach($deny as $v) {
			if(!empty($v)) {
				q("
					INSERT INTO `fw_users_groups_rights` SET
					`group_id` = ".(int)$id.",
					`right` = '".es($v)."',
					`type` = 'deny'
				");
			}
		}
	}

	header("Location: /admin/users/groups-view");
	exit;
}

if(count($_POST)) {
	$row = $_POST;
} elseif(!empty($id)) {
	$res = q("
		SELECT *
		FROM `fw_users_groups`
		WHERE `id` = ".(int)$id."
	");
	$row = $res->fetch_assoc();

	$allow = [];
	$deny = [];
	$res = q("
		SELECT *
		FROM `fw_users_groups_rights`
		WHERE `group_id` = ".$row['id']."
	");
	while($tmp_row = $res->fetch_assoc()) {
		if($tmp_row['type'] == 'allow') {
			$allow[] = $tmp_row['right'];
		} else {
			$deny[] = $tmp_row['right'];
		}
	}
	$row['allow'] = implode("\r\n",$allow);
	$row['deny'] = implode("\r\n",$deny);
}
