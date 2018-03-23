<?= Info::get(); ?>
<?php if (empty($user)) { ?>
    <form action="" method="post" id="foerm-users">
        <div class="row">
            <div class="form-group col-12 col-right">
                <input type="text" name="search" placeholder="Введите ID или часть login или email пользователя"
                       class="form-control col-10 search"
                       value="<?= (!empty($_POST['search']) ? hc($_POST['search']) : '') ?>">
                <input type="submit" class="btn btn-primary col-2" value="Искать">
            </div>
        </div>
        <br>
        <?php if (!empty($users)) { ?>
            <h5>Выберите пользователя:</h5>
            <table class="table" id="users-table">
                <tr class="row-tr-title">
                    <th class="hidden"></th>
                    <th class="text-center">id</th>
                    <th class="text-left">login</th>
                    <th class="text-left">email</th>
                </tr>
                <?php foreach ($users as $user) { ?>
                    <tr class="u-list row-tr">
                        <td class="hidden">
                            <input type="radio" value="<?= $user['id']; ?>" name="user" class="check">
                        </td>
                        <td class="text-center ulist"><?= $user['id']; ?></td>
                        <td class="text-left ulist"><?= $user['login']; ?></td>
                        <td class="text-left ulist"><?= $user['email']; ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } ?>
    </form>
<?php } ?>

<?php if (!empty($courses)) { ?>
    <h5>Изменение доступов для пользователя: <?= $user['login'] . ' (' . $user['email'] . ')'; ?></h5>
    <div class="btn btn-danger trig-stat">Cтатистика</div>
    <form action="" method="post">
        <table class="table table-responsive table-hover" id="table-courses">
            <tr class="row-tr-title">
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Наименование</th>
            </tr>
            <?php foreach ($courses as $k => $v) {
                $c_chack = (in_array($v['id'], $payed['course']) ? true : false); ?>
                <tr class="course <?= ($c_chack ? 'yes' : '') ?>">
                    <td><i class="fas fa-plus hide-view" aria-hidden="true" course="<?= $v['id']; ?>"></i></td>
                    <td><input type="checkbox" value="<?= $v['id']; ?>"
                               name="open[<?= $v['id']; ?>]" <?= ($c_chack ? 'checked' : '') ?> class="check"></td>
                    <td colspan="3" class="name">Уровень: <?= hc($v['title']); ?></td>
                </tr>
                <?php if (count($v['blocks'])) {
                    foreach ($v['blocks'] as $k2 => $v2) {
                        $l_chack = (in_array($v2['id'], $payed['lesson']) ? true : false); ?>
                        <tr course="<?= $v['id'] ?>" bl="<?= $v2['id'] ?>"
                            class="block <?= ($l_chack || $c_chack ? 'yes' : '') ?>">
                            <td></td>
                            <td><i class="fas fa-plus hide-view" aria-hidden="true" block="<?= $v2['id'] ?>"></i></td>
                            <td><input type="checkbox" value="<?= $v2['id']; ?>"
                                       name="open[<?= $v['id']; ?>][<?= $v2['id'] ?>]" <?= ($l_chack ? 'checked' : '') ?>
                                       class="check"></td>
                            <td colspan="2" class="name"><span>Урок: </span><?= hc($v2['title']); ?>
                                <?php if (!empty($stat[$v2['id']]['first_date'])) {
                                    $task_count = count($v2['tasks']);
                                    $task_passed = count($stat[$v2['id']]) - 3;
                                    $pass = ($task_count == $task_passed ? true : false); ?>
                                    <div class="stat hidden">
                                        <?php if ($pass) { ?>
                                            <div><span>Пройден: </span><?= hc($stat[$v2['id']]['first_date']); ?>
                                                <span>, время: </span><?= hc(getTime($stat[$v2['id']]['first_time'])); ?>
                                            </div>
                                            <div>
                                                <span>Лучшее время урока: </span><?= hc(getTime($stat[$v2['id']]['best_time'])); ?>
                                                <span>
                                            </div>
                                        <?php } else { ?>
                                            <div><span>В процессе: </span><?= hc($stat[$v2['id']]['first_date']); ?>
                                            </div>
                                            <div>
                                                <span>Осталось пройти заданий: </span><?= $task_count - $task_passed; ?>
                                                <span>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php if (count($v2['tasks'])) {
                            foreach ($v2['tasks'] as $k3 => $v3) {
                                $t_chack = (in_array($v3['id'], $payed['task']) ? true : false); ?>
                                <tr block="<?= $v2['id'] ?>"
                                    class="task <?= ($t_chack || $l_chack || $c_chack ? 'yes' : '') ?>">
                                    <td></td>
                                    <td></td>
                                    <td>•</td>
                                    <td><input type="checkbox" value="<?= $v3['id']; ?>"
                                               name="open[<?= $v['id']; ?>][<?= $v2['id'] ?>][<?= $v3['id']; ?>]" <?= ($t_chack ? 'checked' : '') ?>
                                               class="check"></td>
                                    <td class="name"><span> Задание: </span><?= hc($v3['title']); ?>
                                        <?php if (!empty($stat[$v2['id']][$v3['id']])) {
                                            echo '<div class="stat hidden">';
                                            foreach ($stat[$v2['id']][$v3['id']] as $tk3 => $t3) {
                                                echo '<div><span>Пройдено: </span>' . hc($tk3) . '<span>, время: </span>' . hc(getTime($t3)) . '</div>';
                                            }
                                            echo '</div>';
                                        } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
        </table>
        <?php
        if (!empty($oldRow)) {
            foreach ($oldRow as $k => $v) { ?>
                <input type="hidden" name="oldRow[<?= (int)$k; ?>]" value="<?= hc($v); ?>">
            <?php }
        } ?>
        <input type="hidden" name="user-edit" value="<?= (int)$user['id']; ?>">
        <input type="hidden" name="search" value="<?= (!empty($_POST['search']) ? hc($_POST['search']) : ''); ?>">
        <input type="submit" name="save" class="btn btn-success" value="Сохранить">
        <a href="/admin/users/accesses" class="btn btn-warning">Назад</a>
    </form>
<?php } ?>
