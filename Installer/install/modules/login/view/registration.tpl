<div align="center">
  <h1>Регистрация</h1>
  <?php if(isset($_GET['success'])) { ?>
    <div>Вы успешно зарегистрировались. На ваш почтовый адрес отправлен код подтверждения.</div>
  <?php } else { ?>
    <?php echo $form->view(); ?>
  <?php } ?>
</div>