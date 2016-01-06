<div align="center">
  <h1>Авторизация на сайте</h1>
  <?php if(!empty($error)) { ?>
    <div style="font-size:18px; color:#900; font-weight:bold; border:1px solid #CCC; background-color:white; margin:10px;"><?php echo $error; ?></div>
  <?php } ?>
  <?php echo $form->view(); ?>
</div>
<style>
.form-login td {
	vertical-align:middle;
}
</style>
