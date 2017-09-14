<div align="center">
<h2>Отписка от email писем</h2>
<?php if(!isset($_SESSION['info']) && (!isset($_GET['key']) || !isset($_GET['hash']) || !isset($_GET['email']))) { ?>
  Для запрета E-mail адресов, пожалуйста, пройдите по ссылке, которая была отправлена Вам на почту
<?php } elseif(isset($_SESSION['info'])) { ?> 
    <div class="alert alert-danger" role="alert">
		<i class="fa fa-exclamation" aria-hidden="true"></i>
        <span class="sr-only">Уведомление:</span>
        <?php echo $_SESSION['info']; unset($_SESSION['info']); ?>
    </div>
<?php } else { ?>
  <div style="padding-bottom:10px; font-size:16px;">Вы можете отписаться от рассылок на почту.<br>Если Вы запретите получать письма, то больше ни одно письмо не придет на Ваш почтовый ящик с нашего сайта!</div>
<form action="" method="post">
  <input type="hidden" name="antixsrf" value="<?php echo hc($_SESSION['antixsrf']); ?>">
  <input type="hidden" name="key" value="<?php echo hc($_GET['key']); ?>">
  <input type="hidden" name="hash" value="<?php echo hc($_GET['hash']); ?>">
  <div>Ваш E-mail: <input type="text" name="email" value="<?php echo hc($_GET['email']); ?>" readonly></div>
  <div><input type="submit" name="submit" value="Запретить E-mail рассылки" style="margin:10px; padding:10px;"></div>
</form>
<?php } ?>
</div>