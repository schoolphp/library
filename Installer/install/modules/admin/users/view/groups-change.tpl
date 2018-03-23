<form action="" method="post" enctype="multipart/form-data">
	<div class="form-group">
		<label for="title">Title</label>
		<input type="text" class="form-control" id="title" placeholder="Введите Title" name="title" required value="<?php if(isset($row['title'])) echo htmlspecialchars($row['title']);?>">
	</div>

	<div class="form-group">
		<label for="allow">Разрешения (Обязательные для админки!)</label>
		<textarea class="form-control" id="allow" rows="10" name="allow"><?php if(isset($row['allow'])) echo htmlspecialchars($row['allow']); ?></textarea>
	</div>


	<div class="form-group">
		<label for="deny">Запреты</label>
		<textarea class="form-control" id="deny" rows="10" name="deny"><?php if(isset($row['deny'])) echo htmlspecialchars($row['deny']); ?></textarea>
	</div>



	<input type="submit" class="btn btn-success" value="Отправить!">
</form>
