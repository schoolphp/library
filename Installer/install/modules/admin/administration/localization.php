<?php
\Core::$TITLE = 'Локализация';

$res = q("
	SELECT *
	FROM `fw_i18n_text`
");

$result = [];
while($row = $res->fetch_assoc()) {
    $result[$row['source']][$row['group']][$row['key']]['id'] = $row['id'];
    $result[$row['source']][$row['group']][$row['key']]['rus'] = $row['text-rus'];
    if (isset($row['text-en'])) $result[$row['source']][$row['group']][$row['key']]['en'] = $row['text-en'];
}
$res->close();

\Core::$END .= '<link href="/modules/admin/administration/view/css/localization.css" rel="stylesheet">
<script src="/modules/admin/administration/view/js/localization.js"></script>';