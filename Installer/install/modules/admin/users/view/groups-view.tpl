<div class="row">
	<div class="col-6 col-left"><a href="/admin/users/groups-change/add" class="btn btn-primary">Добавить Группу</a></div>
	<div class="col-6 col-right">

	</div>
</div>

<?=Info::get();?>

<div>
	<div class="row row-tr-title">
		<div class="col-2 col-left">ID</div>
		<div class="col-8 col-left">Группа</div>
        <div class="col-2 col-center">Удалить</div>
	</div>
	<?php while($row = $res->fetch_assoc()) { ?>
		<div class="row row-tr">
			<div class="col-2 col-left"><?=hc($row['id']);?></div>
			<div class="col-8 col-left"><a href="/admin/users/groups-change/edit/<?=(int)$row['id'];?>"><i class="fas fa-pencil-alt" aria-hidden="true"></i> <?=hc($row['title']);?></a></div>
            <div class="col-2 col-center">
                <?php if ((int)$row['id'] != 1) { ?>
                <a href="/admin/users/groups-change/delete/<?=(int)$row['id'];?>" class="remove"><i class="fas fa-trash-alt" aria-hidden="true"></i></a>
                <?php } ?>
            </div>
		</div>
	<?php } ?>
</div>

<div><?=\FW\Pagination\Pagination::nav();?></div>
