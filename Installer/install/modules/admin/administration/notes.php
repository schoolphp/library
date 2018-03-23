<?php
\Core::$TITLE = 'Заметки';
$id = (!empty($_GET['id']) ? (int)$_GET['id'] : 0);
$status = (!empty($_GET['status']) ? $_GET['status'] : 'show');

if (isset($_POST['title'], $_POST['note'])) {
    q("
		INSERT INTO `notes` SET
		`user` = " . (int)\User::$id . ",
		`title` = '" . es($_POST['title']) . "',
		`note` = '" . es($_POST['note']) . "',
		`status` = '" . es($status) . "'
	");
    \Info::set('success', 'Заметка успешно добавлена');
    header("Location: /admin/administration/notes");
    exit;
} elseif ($id && !empty($_GET['status'])) {
    q("
		UPDATE `notes` SET
		`status` = '" . es($status) . "'
		" . ($id ? 'WHERE `id` = ' . (int)$id : '') . "
	");
    header("Location: /admin/administration/notes");
    exit;
}

$res_tasks = q("
	SELECT *
	FROM `notes`
	WHERE `status` = '" . es($status) . "'
");