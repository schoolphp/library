<?php if(!isset($_SESSION['user']['id'])) { ?>
	<div align="center">
		<h1>Авторизация на сайте</h1>
		<?php if(!empty($form->error)) { ?>
			<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> <?php echo $form->error; ?></div>
		<?php } ?>
		<?php echo $form->view(); ?>
	</div>
	<style>
		.form-login td {
			vertical-align:middle;
		}
	</style>
<?php } else { ?>
	<div>Вы авторизированы. Здравствуйте, ID: <?=htmlspecialchars($_SESSION['user']['id']);?></div>
<?php } ?>