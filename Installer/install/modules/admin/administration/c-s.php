<?php
\Core::$TITLE = 'Cookie & Session';

if (isset($_POST['delete_cookie'])) {
    if (isset($_SERVER['HTTP_COOKIE'])) {
        $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
        foreach($cookies as $cookie) {
            $parts = explode('=', $cookie);
            $name = trim($parts[0]);
            setcookie($name, '', time()-1000);
            setcookie($name, '', time()-1000, '/');
        }
    }
    header("Location: http://localhost/admin/administration/c-s");
} elseif (isset($_POST['delete_session'])) {
    session_destroy();
    header("Location: http://localhost/admin/administration/c-s");
}
