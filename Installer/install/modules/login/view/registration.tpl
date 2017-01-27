<div align="center">
	<h1>Регистрация</h1>
	<?php if(!empty($error)) { ?>
		<div style="font-size:18px; color:#900; font-weight:bold; border:1px solid #CCC; background-color:white; margin:10px;"><?php echo $error; ?></div>
	<?php } ?>
	<?php if(isset($status) && $status == 'ok') { ?>
		<div>Вы успешно зарегистрировались. На ваш почтовый адрес отправлен код подтверждения.</div>
	<?php } else { ?>
		<?php echo $form->view(); ?>
	<?php } ?>
</div>