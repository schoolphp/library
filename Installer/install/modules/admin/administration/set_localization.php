<?php
$lang = ['rus', 'en'];
if (isset($_POST['id'], $_POST['text'], $_POST['lang']) && in_array($_POST['lang'], $lang)) {
    q("
        UPDATE `fw_i18n_text` SET
        `text-" . $_POST['lang'] . "` = '" . es($_POST['text']) . "'
        WHERE `id` = " . (int)$_POST['id'] . "
    ");
    q("
        INSERT INTO `fw_admin_actions` SET 
        `user` = " . (int)\User::$id . ",
        `action` = 'update',
        `a_table` = 'fw_i18n_text',
        `value` = '" . (int)$_POST['id'] . "'
    ");
    echo json_encode('ok');
}
exit;

