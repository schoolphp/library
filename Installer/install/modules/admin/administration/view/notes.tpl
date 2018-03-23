<?= Info::get(); ?>
<div class="filtr">
    <a href="/admin/administration/notes?status=<?= ($status == 'show' ? 'hidden' : 'show') ?>"
       class="hidden-show btn btn-info"><?= ($status == 'show' ? 'Скрытые' : 'Активные') ?></a>
</div>
<?php while ($row = $res_tasks->fetch_assoc()) { ?>
    <div class="row note">
        <div class="note-header col-12">
            <div class="title float-left"><?= $row['title']; ?></div>
            <div class="float-right"><?= $row['date']; ?></div>
        </div>
        <div class="col-12 text"><?= $row['note']; ?></div>
        <a href="/admin/administration/notes?id=<?= $row['id'] ?>&status=<?= ($status == 'show' ? 'hidden' : 'show') ?>"
           class="col-12 col-right"><?= ($status == 'show' ? 'Скрыть' : 'Показать') ?></a>
    </div>
<?php } ?>

<div class="task-form note addnote">
    <form action="" method="post">
        <div class="form-group row">
            <label class="col-12">Заголовок:
                <input type="text" class="form-control" placeholder="Введите заголовок" name="title" required>
            </label>
        </div>
        <div class="form-group row">
            <label class="col-12">Заметка:
                <textarea class="form-control" rows="5" name="note"></textarea>
            </label>
        </div>
        <div>
            <input type="submit" class="btn btn-success" value="Сохранить">
        </div>
    </form>
</div>

<style>
    .btn {
        cursor: pointer;
    }

    .title {
        font-weight: bold;
    }

    main > div {
        background-color: #eee;
        box-shadow: none;
    }

    .note {
        border: 1px solid #ccc;
        border-radius: 5px;
        margin: 20px 0px;
        background-color: #fff;
        box-shadow: 4px 4px 10px 0 #ccc;
    }

    .addnote {
        background-color: #d6d6d6;
        padding: 10px;
    }

    .note-header {
        background-color: #d6d6d6;
        padding: 4px 15px;
        border-radius: 3px 3px 0 0;
    }

    .text {
        padding: 10px 15px 0px;
    }

    .note a:hover {
        color: red;
    }
    .filtr {
        text-align: center;
    }
    .btn-info {
        padding: 2px 30px;
    }
</style>