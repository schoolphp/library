<form action="" method="post" enctype="multipart/form-data" autocomplete="off">
    <input name="fake_email" class="visually-hidden" type="text">
    <input name="fake_password" class="visually-hidden" type="password">

    <div class="form-group">
        <label for="active">Статус</label>
        <select name="active" class="btn form-control" id="active" style="border: 1px solid #ccc;">
            <option value="active" <?= (!empty($row['active']) && $row['active'] == 'active' ? 'selected' : ''); ?>>
                Активен
            </option>
            <option value="not active" <?= (!empty($row['active']) && $row['active'] == 'not active' ? 'selected' : ''); ?>>
                Не активен
            </option>
        </select>
    </div>

    <div class="form-group">
        <label for="login">Login</label>
        <input type="text" class="form-control" id="login" placeholder="Введите Login" name="login" required
               value="<?php if (isset($row['login'])) echo htmlspecialchars($row['login']); ?>">
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="text" class="form-control" id="email" placeholder="Введите Email" name="email" required
               value="<?php if (isset($row['email'])) echo htmlspecialchars($row['email']); ?>">
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" placeholder="Введите Password" name="password"
               value="">
    </div>

    <fieldset class="form-group">
        <legend>Группы пользователей:</legend>
        <?php while ($row_tmp = $res_groups->fetch_assoc()) { ?>
            <div class="form-check">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" name="groups[]"
                           value="<?= $row_tmp['id']; ?>" <?php if (isset($row['groups']) && in_array($row_tmp['id'], $row['groups'])) echo ' checked'; ?>>
                    <?= htmlspecialchars($row_tmp['title']); ?>
                </label>
            </div>
        <?php } ?>
    </fieldset>
    <input type="submit" name="submit" class="btn btn-success" value="Сохранить">
    <a href="/admin/users/view" class="btn btn-warning">Отменить</a>
    <?php if (!empty($row['id'])) { ?>
        <a href="/admin/users/del/<?= $row['id']; ?>" class="btn btn-danger remove">Удалить</a>
    <?php } ?>
</form>
<style>
    .form-control:disabled, .form-control[readonly] {
        background-color: #fff;
    }

    .visually-hidden {
        margin: -1px;
        padding: 0;
        width: 1px;
        height: 1px;
        overflow: hidden;
        clip: rect(0 0 0 0);
        clip: rect(0, 0, 0, 0);
        position: absolute;
    }

    .btn {
        cursor: pointer;
        margin-right: 30px;
    }
</style>