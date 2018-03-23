<?php
\Core::$TITLE = 'Пользователи';

$active = (!empty($_POST['active']) ? $_POST['active'] : '0');
\FW\Pagination\Pagination::$onpage = 25;
\FW\Pagination\Pagination::$curpage = ($_GET['page'] ?? 1);
$res = \FW\Pagination\Pagination::q("
	SELECT * FROM `fw_users`
	WHERE `active` " . (!empty($active) ? '= \''. es($active).'\'' : '<> \'deleted\'') . (!empty($_POST['search']) ? " AND (`id` = " . (int)$_POST['search'] . " OR `login` LIKE '%" . es($_POST['search']) . "%' OR `email` LIKE '%" . es($_POST['search']) . "%')" : '') . "
");

\Core::$END .= '<link href="/modules/admin/users/view/css/view.css" rel="stylesheet">
<script src="/modules/admin/users/view/js/view.js"></script>';