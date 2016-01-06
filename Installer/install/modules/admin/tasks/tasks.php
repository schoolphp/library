<?php
Core::$JS[] = '/skins/components/bower/ckeditor/ckeditor.js';
Core::$JS[] = '/modules/admin/tasks/view/js/scripts.js';
if(isset($_POST['st'],$_POST['title'],$_POST['text']) && $_POST['st'] == 8) {
	q("
		INSERT INTO `tasks` SET
		`title`  = '".es($_POST['title'])."',
		`text`  = '".es($_POST['text'])."',
		`importance`  = '".(int)$_POST['imp']."',
		`status`  = '".(int)$_POST['stat']."',
		`date`  = NOW()
	");
	echo 8;
	exit;
}

if(isset($_POST['st'],$_POST['title'],$_POST['text'],$_POST['id']) && $_POST['st'] == 12) {
	$res_count = q("
		SELECT COUNT(*) as `cnt`
		FROM `tasks`
	");
	$res = $res_count->fetch_assoc();
	if($res['cnt'] == 0) {
		echo 0;
	} else {
		q("
			UPDATE `tasks` SET
				`title`  = '".es($_POST['title'])."',
				`text`  = '".es($_POST['text'])."',
				`importance`  = '".(int)$_POST['imp']."',
				`status`  = '".(int)$_POST['stat']."',
				`date`  = NOW()
			WHERE `id` = ".(int)$_POST['id']
		);
	}
	echo 12;
	exit;
}

if(isset($_POST['st'],$_POST['id']) && $_POST['st'] == 9) {
	q("DELETE FROM `tasks` WHERE `id` = ".(int)$_POST['id']);
	echo 9;
	exit;
}

if(isset($_POST['st'],$_POST['id']) && $_POST['st'] == 11) {
	$res = q("
		SELECT *
		FROM `tasks`
		WHERE `id` = ".(int)$_POST['id']
	);
	
	if($res->num_rows != 0) {
		while($row = $res->fetch_assoc()) {
			$s_output['tasks'] = array ('id' => $row['id'],'title' => $row['title'],'text' => $row['text'],'imp' => $row['importance'],'stat' => $row['status']);
		}
		$res->close();
	} else {
		$s_output['s'] = 0;
	}
	$s_output['s'] = 11;
    echo json_encode($s_output);
    exit;
}

$step = 5;
if(isset($_GET['ajax'],$_POST['st']) && $_POST['st'] == 5) {
	$i = 0;
	if(isset($_POST['cat_im1']) && !empty($_POST['cat_im1'])) {
		$cat_im1 = 'AND `importance` = "'.$_POST['cat_im1'].'"';
		$im_array[] = $_POST['cat_im1'];
		++$i;
	} else {
		$cat_im1 = '';
	}
	if(isset($_POST['cat_im2']) && !empty($_POST['cat_im2'])) {
		$cat_im2 = 'AND `importance` = "'.$_POST['cat_im2'].'"';
		$im_array[] = $_POST['cat_im2'];
		++$i;
	} else {
		$cat_im2 = '';
	}
	if(isset($_POST['cat_im3']) && !empty($_POST['cat_im3'])) {
		$cat_im3 = ' AND `importance` = "'.$_POST['cat_im3'].'"';
		$im_array[] = $_POST['cat_im3'];
		++$i;
	} else {
		$cat_im3 = '';
	}
	if($i >= 2) {
		$cat_im = ' `importance` in('. implode(',', $im_array).')';
	} else {
		$cat_im = $cat_im1.$cat_im2.$cat_im3;
	}
	$s = 0;
	if(isset($_POST['cat_st1']) && !empty($_POST['cat_st1'])) {
		$cat_st1 = ' AND `status` = "'.$_POST['cat_st1'].'"';
		$st_array[] = $_POST['cat_st1'];
		++$s;
	} else {
		$cat_st1 = '';
	}

	if(isset($_POST['cat_st2']) && !empty($_POST['cat_st2'])) {
		$cat_st2 = ' AND `status` = "'.$_POST['cat_st2'].'"';
		$st_array[] = $_POST['cat_st2'];
		++$s;
	} else {
		$cat_st2 = '';
	}
	
	if($s >= 2) {
		$cat_st = ' `status` in('. implode(',', $st_array).')';
		if(!empty($cat_im)) {
			$cat_st = ' AND '.$cat_st;
		}
	} else {
		$cat_st = $cat_st1.$cat_st2;
	}
		// с - по
	if(isset($_POST['s_date1']) && !empty($_POST['s_date1']) && isset($_POST['s_date2']) && !empty($_POST['s_date2'])) {
		if($i != 0 || $s != 0) {
			$date = " AND `date` BETWEEN STR_TO_DATE('".es($_POST['s_date1'])." 00:00:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('".es($_POST['s_date2'])." 23:59:59', '%Y-%m-%d %H:%i:%s')";
		} else {
			$date = " `date` BETWEEN STR_TO_DATE('".es($_POST['s_date1'])." 00:00:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('".es($_POST['s_date2'])." 23:59:59', '%Y-%m-%d %H:%i:%s')";
		}
  	} else {
		$date = '';
	}
	$sort = $cat_im.$cat_st.$date;
	$sort = preg_replace('#^\s*AND#usU','',$sort);
	$sort = 'WHERE'.$sort;
	if($sort == 'WHERE') {$sort = '';}
} else {
	$sort = '';
} 

// проверяем есть ли записи
$res_count = q("
	SELECT COUNT(*) as `cnt`
	FROM `tasks`
	".$sort."
");
$res_c = $res_count->fetch_assoc();

if($res_c['cnt'] != 0) {
	Pagination::$onpage = $step;
	Pagination::$curpage = (isset($_GET['page']) ? $_GET['page'] : 1);
	$res = Pagination::q("
		SELECT *
		FROM `tasks`
		".$sort."
	");
}

if(isset($_GET['ajax'],$_POST['st']) && $_POST['st'] == 5) {
	if($res_c['cnt'] != 0) {
		while($row = $res->fetch_assoc()) {
			$s_output['tasks'][] = array ('id' => $row['id'],'title' => $row['title'],'text' => $row['text'],'importance' => $row['importance'],'status' => $row['status'],'date' => $row['date']);
		}
		$s_output['pag'] = Pagination::nav(false);
		$res->close();
	} else {
		$s_output['pag'] = 0;
	}
	$s_output['s'] = 1;
    echo json_encode($s_output);
    exit;
}

if(isset($_GET['ajax'],$_POST['st']) && $_POST['st'] == 6) {
	if(isset($_POST['edit_p']) && !empty($_POST['edit_p'])) {
		$edit = '`importance` = '.(int)$_POST['edit_p'];
	}
	if(isset($_POST['edit_s']) && !empty($_POST['edit_s'])) {
		$edit = '`status` = '.(int)$_POST['edit_s'];
	}
	
	q("
		UPDATE `tasks` SET						
			".$edit."
		WHERE `id` = ".(int)$_POST['edit_id']."	
	");
	echo 67;
    exit;
}