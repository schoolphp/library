<div id="localization">
    <?php foreach ($result as $source => $v) { ?>
        <div class="source"><i class="fas fa-plus hideView" aria-hidden="true"> <?= $source ?></i>
            <?php foreach ($v as $group => $v2) { ?>
                <div class="group"><i class="fas fa-plus hideView" aria-hidden="true"> <?= $group ?></i>
                    <?php foreach ($v2 as $key => $v3) { ?>
                        <div class="key"><i class="fas fa-plus hideView" aria-hidden="true"> <?= $key ?></i>
                            <?php foreach ($v3 as $local => $v4) {
                                if ($local == 'id') continue; ?>
                                <div class="local"><i class="fas fa-pencil-alt edit" aria-hidden="true"></i> <span><?= $local ?>:</span> <?= $v4 ?></div>
                                <div class="local hidden">
                                    <textarea class="text form-control"><?= $v4 ?></textarea>
                                    <a class="btn btn-primary save" id="<?= $v3['id'] ?>" lang="<?= $local ?>">Сохранить</a>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>
