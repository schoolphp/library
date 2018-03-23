<?php

if (!empty($_GET['id']) && \User::$role == 'admin') {
    $auth = new \FW\User\Authorization();
    $auth->authByField(['id' => (int)$_GET['id']]);
    \Info::set('success', 'Вы успешно авторизовались под пользователем');
}

header("Location: /admin/users/view");
exit;