<?php
if (isset($_POST['delete']) && empty($_GET['id'])) {
    header("Location: /admin/users/view");
    exit;
}
$id = ($_GET['id'] ?? 0);
\Core::$TITLE = 'Пользователь • '.(isset($_GET['id']) ? 'Редактирование: ' : 'Создание');

if ($_GET['action'] == 'del') {
    q("
        UPDATE `fw_users` SET
        `active` = 'deleted'
        ".($id ? 'WHERE `id` = '.(int)$id : '')."
    ");
    q("
        INSERT INTO `fw_admin_actions` SET 
        `user` = ".(int)\User::$id.",
        `action` = 'delete',
        `a_table` = 'fw_users',
        `value` = '".es($id)."'
    ");
    \Info::set('success', 'Пользователь успешно удален');
    header("Location: /admin/users/view");
    exit;
}


if(isset($_POST['submit'])) {
	$errors = [];
	$data = '';

	if(!empty($_POST['login'])) {
		$res = q("
			SELECT 1
			FROM `fw_users`
			WHERE `login` = '".es($_POST['login'])."'
			".($id ? "AND `id` <> ".(int)$id : '')."
		");
		if($res->num_rows) {
			$errors['login'] = 'Логин занят';
		} else {
			$data .= "`login` = '".es($_POST['login'])."',";
		}
	}

	if(!empty($_POST['email'])) {
		$res = q("
			SELECT 1
			FROM `fw_users`
			WHERE `email` = '".es($_POST['email'])."'
			".($id ? "AND `id` <> ".(int)$id : '')."
		");
		if($res->num_rows) {
			$errors['email'] = 'Логин занят';
		} else {
			$data .= "`email` = '".es($_POST['email'])."',";
		}
	}

	if(!count($errors) && !empty($data)) {
		if(!empty($_POST['password'])) {
			$data .= "`password` = '".es(password_hash($_POST['password'],PASSWORD_DEFAULT))."',";
		}
        if(!empty($_POST['active'])) {
            $data .= "`active` = '".es($_POST['active'])."',";
        }

		q("
			".($id ? 'UPDATE ' : 'INSERT INTO')." `fw_users` SET
			".$data."
			`access` = 1
	
			".($id ? 'WHERE `id` = '.(int)$id : '')."
		");

		if($id) {
			q("
				DELETE FROM `fw_users2groups`
				WHERE `user_id` = ".$id."
			");
		}

		if(empty($id)) {
			$id = \DB::_()->insert_id;
			\Info::set('success', 'Пользователь добавлен');
		}
		else {
			\Info::set('success', 'Пользователь отредактирован');
		}

		if(isset($_POST['groups']) && count($_POST['groups'])) {
			foreach($_POST['groups'] as $v) {
				q("
					INSERT INTO `fw_users2groups` SET 
					`user_id` = ".$id.",
					`group_id` = ".(int)$v."
				");
			}
		}

		q("
			INSERT INTO `fw_admin_actions` SET 
			`user` = ".(int)\User::$id.",
			`action` = 'update',
			`a_table` = 'fw_users',
			`value` = '".es($id)."'
		");

		header("Location: /admin/users/view");
		exit;
	}
}

if(count($_POST)) {
	$row = $_POST;
} elseif(!empty($id)) {
	$res = q("
		SELECT *
		FROM `fw_users`
		WHERE `id` = ".(int)$id."
	");
	$row = $res->fetch_assoc();
	\Core::$TITLE .= hc($row['login']);

	$row['groups'] = [];
	$res = q("
		SELECT `group_id`
		FROM `fw_users2groups`
		WHERE `user_id` = ".$row['id']."
	");
	while($tmp_row = $res->fetch_assoc()) {
		$row['groups'][] = $tmp_row['group_id'];
	}
}

$res_groups = q("
	SELECT *
	FROM `fw_users_groups`
");

\Core::$END .= '<script src="/modules/admin/users/view/js/change.js"></script>';