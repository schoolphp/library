<?php
\Core::$TITLE = 'Установка доступов к курсам';

if (!empty($_POST['save']) && !empty($_POST['open'])) {
    $i = 1;
    $insert = [];
    $oldRow = (!empty($_POST['oldRow']) ? $_POST['oldRow'] : []);
    foreach ($_POST['open'] as $k => $v) {
        if (!is_array($v)) {
            $insert[$i]['course'] = $v;
            $insert[$i]['lesson'] = 0;
            $insert[$i]['task'] = 0;
            if ($old = array_search(implode(',', $insert[$i]), $oldRow)) unset($insert[$i], $oldRow[$old]);
            $i++;
        } else {
            foreach ($v as $k2 => $v2) {
                if (!is_array($v2)) {
                    $insert[$i]['course'] = $k;
                    $insert[$i]['lesson'] = $k2;
                    $insert[$i]['task'] = 0;
                    if ($old = array_search(implode(',', $insert[$i]), $oldRow)) unset($insert[$i], $oldRow[$old]);
                    $i++;
                } else {
                    foreach ($v2 as $k3 => $v3) {
                        $insert[$i]['course'] = $k;
                        $insert[$i]['lesson'] = $k2;
                        $insert[$i]['task'] = $k3;
                        if ($old = array_search(implode(',', $insert[$i]), $oldRow)) unset($insert[$i], $oldRow[$old]);
                        $i++;
                    }
                }
            }
        }
    }
    if (!empty($insert)) {
        foreach ($insert as $value) {
            q("
            INSERT INTO `courses_pay` SET 
            `user` = " . (int)$_POST['user-edit'] . ",
            `course` = " . (int)$value['course'] . ",
            `lesson` = " . (int)$value['lesson'] . ",
            `task` = " . (int)$value['task'] . "
        ");
        }
    }
    if (!empty($oldRow)) {
        $ids = array_keys($oldRow);
        q("
            DELETE FROM `courses_pay` WHERE `id` IN (" . implode(',', $ids) . ") 
        ");
    }
    q("
        INSERT INTO `fw_admin_actions` SET 
        `user` = " . (int)\User::$id . ",
        `action` = 'update',
        `a_table` = 'courses_pay',
        `value` = '" . es($_POST['user-edit']) . "'
    ");
    \Info::set('danger', 'Доступы пользователя успешно обновлены');
    header("Location: /admin/users/accesses");
    exit;
}

if (!empty($_POST['user']) || !empty($_GET['id'])) {
    $id = (!empty($_POST['user']) ? $_POST['user'] : $_GET['id']);
    $payed['task'] = $payed['lesson'] = $payed['course'] = [];
    $user = q("
        SELECT `id`, `login`, `email` FROM `fw_users`
        WHERE `id` = " . (int)$id . "
    ")->fetch_assoc();
    $res = q("
        SELECT *
        FROM `courses_pay`
        WHERE `user` = " . (int)$user['id'] . "
    ");
    if ($res->num_rows) {
        while ($row = $res->fetch_assoc()) {
            if ($row['task'] != 0) {
                $payed['task'][] = $row['task'];
            } elseif ($row['lesson'] != 0) {
                $payed['lesson'][] = $row['lesson'];
            } else {
                $payed['course'][] = $row['course'];
            }
            $oldRow[$row['id']] = $row['course'] . ',' . $row['lesson'] . ',' . $row['task'];
        }
    }
} else {
    \FW\Pagination\Pagination::$onpage = 10;
    \FW\Pagination\Pagination::$curpage = ($_GET['page'] ?? 1);
    $res = \FW\Pagination\Pagination::q("
        SELECT `id`, `login`, `email` FROM `fw_users`
        " . (!empty($_POST['search']) ? "WHERE `id` = " . (int)$_POST['search'] . " OR `login` LIKE '%" . es($_POST['search']) . "%' OR `email` LIKE '%" . es($_POST['search']) . "%'" : '') . "
    ");
    while ($row = $res->fetch_assoc()) {
        $users[] = $row;
    }
}

if (!empty($user)) {
    $res = q("
        SELECT a.`task_id`, a.`time`, a.`date`, b.`block_id`
        FROM `courses-time` a
        LEFT JOIN `courses-tasks` b ON b.`id` = a.`task_id`
        WHERE a.`user` = " . (int)$user['id'] . "
        ORDER BY a.`id` ASC
    ");
    if ($res->num_rows) {
        $block_id = '';
        while ($row = $res->fetch_assoc()) {
            if (!empty($stat[$row['block_id']][$row['task_id']][$row['date']]) || empty($row['block_id'])) continue;
            if (!isset($stat[$row['block_id']][$row['task_id']])) {
                $stat[$row['block_id']]['best_time'][$row['task_id']] = $row['time'];
                $stat[$row['block_id']]['first_time'] = (empty($stat[$row['block_id']]['first_time']) ? $row['time'] : $row['time'] + $stat[$row['block_id']]['first_time']);

                if (empty($stat[$row['block_id']]['first_date']) || $stat[$row['block_id']]['first_date'] < $row['date']) {
                    $stat[$row['block_id']]['first_date'] = $row['date'];
                }
            } elseif ($stat[$row['block_id']]['best_time'][$row['task_id']] > $row['time']) {
                $stat[$row['block_id']]['best_time'][$row['task_id']] = $row['time'];
            }

            $stat[$row['block_id']][$row['task_id']][$row['date']] = $row['time'];

            if ($block_id && $block_id != $row['block_id']) $stat[$block_id]['best_time'] = array_sum($stat[$block_id]['best_time']);
            $block_id = $row['block_id'];
        }
        if ($block_id) $stat[$block_id]['best_time'] = array_sum($stat[$block_id]['best_time']);
    }

    $res = q("
        SELECT a.`id`, a.`block_id`, a.`value` `t_title`, b.`title` `b_title`, b.`course_id`, c.`title` `c_title`
        FROM `courses-tasks` a
        LEFT JOIN `courses-blocks` b ON b.`id` = a.`block_id`
        LEFT JOIN `courses` c ON c.`id` = b.`course_id`
        WHERE a.`type` = 'title'
        ORDER BY c.`order`, b.`order`, a.`order`
    ");

    while ($row = $res->fetch_assoc()) {
        if (!$row['b_title']) continue;
        $row['course_id'] = (int)$row['course_id'];
        $courses[$row['course_id']]['id'] = $row['course_id'];
        $courses[$row['course_id']]['title'] = ($row['course_id'] ? $row['c_title'] : 'Блоки не привязанные к курсу');
        $courses[$row['course_id']]['blocks'][$row['block_id']]['id'] = $row['block_id'];
        $courses[$row['course_id']]['blocks'][$row['block_id']]['title'] = $row['b_title'];
        $courses[$row['course_id']]['blocks'][$row['block_id']]['tasks'][$row['id']]['id'] = $row['id'];
        $courses[$row['course_id']]['blocks'][$row['block_id']]['tasks'][$row['id']]['title'] = $row['t_title'];
    }
}
function getTime($sec)
{
    $del = ['00 ч. ', '00 мин. '];
    $dtime = new DateTime('@' . $sec);
    $time = $dtime->format('H ч. i мин. s сек.');
    return str_replace($del, '', $time);
}

\Core::$END .= '<link href="/modules/admin/users/view/css/accesses.css?' . filemtime(\Core::$ROOT . '/modules/admin/users/view/css/accesses.css') . '" rel="stylesheet">
<script src="/modules/admin/users/view/js/accesses.js?' . filemtime(\Core::$ROOT . '/modules/admin/users/view/js/accesses.js') . '"></script>';