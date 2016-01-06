<div align="center">
  <h1>Активация</h1>
  <?php if(empty($error)) { ?>
    <div>Вы успешно активировали аккаунт, теперь можете авторизироваться.</div>
  <?php } else { ?>
    <div><?php echo $error; ?></div>
  <?php } ?>
</div>