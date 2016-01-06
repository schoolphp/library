<?php
CORE::$META['title'] = 'TITLE: School-PHP FrameWork';
CORE::$META['description'] = 'DESCRIPTION: School-PHP FrameWork';
CORE::$META['keywords'] = 'KEYWORDS: School-PHP FrameWork';

\FW\Pagination\Pagination::$onpage = 2;
\FW\Pagination\Pagination::$curpage = (isset($_GET['page']) ? $_GET['page'] : 1);

$res = \FW\Pagination\Pagination::q("
	SELECT *
	FROM `fw_unsubscribe`
");
