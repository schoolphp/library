<?php if(!isAdmin()) { /* Login */ ?>
	<form action="/<?=$_GET['route']; ?>" method="post" class="admin-auth-form">
		<div class="admin-auth-form-title">Доступ к админскому разделу закрыт</div>
		<div class="admin-auth-form-body">
			<?php if(!empty(User::$data['role'])) {?><h1>У Вас нет прав для доступа в админку</h1><?php } ?>
			<div class="form-group">
				<label for="admLogin">Логин</label>
				<input type="text" name="login" class="form-control" id="admLogin" placeholder="Логин">
				<?php if(!empty($error['login'])) {echo '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> '.$error['login'].'</div>';} ?>
			</div>

			<div class="form-group">
				<label for="admPass">Пароль</label>
				<input type="password" name="pass" class="form-control" id="admPass" placeholder="Пароль">
				<?php if(!empty($error['password'])) {echo '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> '.$error['password'].'</div>';} ?>
			</div>
		</div>
		<div class="admin-auth-form-footer">
			<input type="submit" class="btn btn-primary" value="Войти">
		</div>
	</form>
<?php } else { ?>
	<div class="admin-container" style="min-height:400px;">
		<h1>Добро пожаловать в админку!</h1>
	</div>
<?php } ?>