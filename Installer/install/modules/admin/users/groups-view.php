<?php
\Core::$TITLE = 'Группы';

$res = q("
	SELECT * 
	FROM `fw_users_groups`
");

\Core::$END .= '<link href="/modules/admin/users/view/css/view.css" rel="stylesheet">
<script src="/modules/admin/users/view/js/change.js"></script>';