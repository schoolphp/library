<?php if(empty(User::$data['role']) || User::$data['role'] !== 'admin') { /* Login */ ?>
	<form action="/<?=$_GET['route']; ?>" method="post" class="admin-auth-form">
		<div class="admin-auth-form-title">Доступ к админскому разделу закрыт</div>
		<div class="admin-auth-form-body">
			<?php if(!empty(User::$data['role'])) {?><h1>У Вас нет прав для доступа в админку</h1><?php } ?>
			<div class="form-group">
				<label for="admLogin">Логин</label>
				<input type="text" name="login" class="form-control" id="admLogin" placeholder="Логин">
			</div>

			<div class="form-group">
				<label for="admPass">Пароль</label>
				<input type="password" name="pass" class="form-control" id="admPass" placeholder="Пароль">
			</div>
			<?php if(isset($error)) {
				echo '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> <span class="sr-only">Ошибка авторизации:</span> '.$error.' </div>';
			} ?>
		</div>
		<div class="admin-auth-form-footer">
			<input type="submit" class="btn btn-primary" value="Войти">
		</div>
	</form>
<?php } else { ?>
	<h1>Добро пожаловать в админку</h1>
<?php } ?>