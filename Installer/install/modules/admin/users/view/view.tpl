<form action="" method="post" id="formUsers" class="row">
    <div class="col-12 col-right block-search">
        <input type="text" name="search" class="form-control search" placeholder="Введите ID или часть login или email"
               value="<?= (!empty($_POST['search']) ? hc($_POST['search']) : '') ?>">
        <input type="submit" class="btn btn-primary" value="Искать">
        <a href="/admin/users/view" class="btn btn-warning">Сбросить</a>
    </div>
    <div class="col-12 col-sm-6"><a href="/admin/users/add" class="btn btn-success">Добавить пользователя</a></div>
    <div class="col-12 col-sm-6 col-right">
        <select name="active" class="selectpicker btn btn-primary">
            <option value="0" <?= (!empty($_POST['active']) && $_POST['active'] == 0 ? 'selected' : ''); ?>>Все (кроме
                удаленных)
            </option>
            <option value="active" <?= (!empty($_POST['active']) && $_POST['active'] == 'active' ? 'selected' : ''); ?>>
                Активные
            </option>
            <option value="not active" <?= (!empty($_POST['active']) && $_POST['active'] == 'not active' ? 'selected' : ''); ?>>
                Не активные
            </option>
            <option value="deleted" <?= (!empty($_POST['active']) && $_POST['active'] == 'deleted' ? 'selected' : ''); ?>>
                Удаленные
            </option>
        </select>
    </div>
</form>

<?= Info::get(); ?>

<div class="users">
    <div class="row row-tr-title">
        <div class="col-5 col-sm-4 col-left ulist">Логин</div>
        <div class="col-5 col-sm-6 col-left ulist">E-mail</div>
        <div class="col-1 col-center ulist">Войти</div>
        <div class="col-1 col-center ulist">ID</div>
    </div>
    <?php while ($row = $res->fetch_assoc()) { ?>
        <div class="row row-tr">
            <div class="col-5 col-sm-4 ulist">
                <a href="/admin/users/edit/<?= (int)$row['id']; ?>">
                    <i class="fas fa-pencil-alt" aria-hidden="true"></i> <?= hc($row['login']); ?>
                </a>
            </div>
            <div class="col-5 col-sm-6 ulist"><?= hc($row['email']); ?></div>
            <div class="col-1 ulist col-center">
                <a href="/admin/users/authorization/<?= (int)$row['id']; ?>">
                    <i class="fas fa-sign-in-alt"></i>
                </a>
            </div>
            <div class="col-1 col-center ulist"><?= hc($row['id']); ?></div>
        </div>
    <?php } ?>
</div>

<div><?= \FW\Pagination\Pagination::nav(); ?></div>
