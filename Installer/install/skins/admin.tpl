<!DOCTYPE html>
<html lang="<?php echo Core::$LANGUAGE['html_locale']; ?>">
<head>
	<meta charset="UTF-8">
	<?php foreach(Core::$META['dns-prefetch'] as $v) { ?>
		<link rel="dns-prefetch" href="<?php echo $v; ?>">
	<?php } ?>
	<title><?php echo hc(Core::$META['title']); ?></title>
	<meta name="apple-mobile-web-app-title" content="<?php echo hc(Core::$META['title']); ?>">
	<meta name="description" content="<?php echo hc(Core::$META['description']); ?>">
	<meta name="keywords" content="<?php echo hc(Core::$META['keywords']); ?>">
	<meta name="author" content="Усков Станислав">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
	<meta name="robots" content="index, follow">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<?php if(Core::$LANGUAGE['status']) {foreach(Core::$LANGUAGE['allow'] as $v) { if($v != Core::$LANGUAGE['lang']) { ?>
		<link rel="alternate" hreflang="<?php echo $v; ?>" href="<?php echo createUrl('this',false,$v); ?>">
	<?php } } } ?>
	<?php if(!empty(Core::$META['prev'])) { ?>
		<link rel="prev" href="<?php echo Core::$META['prev']; ?>">
	<?php } ?>
	<?php if(!empty(Core::$META['next'])) { ?>
		<link rel="next" href="<?php echo Core::$META['next']; ?>">
	<?php } ?>
	<?php if(!empty(Core::$META['canonical'])) { ?>
		<link rel="canonical" href="<?php echo Core::$META['canonical']; ?>">
	<?php } ?>
	<?php if(!empty(Core::$META['shortlink'])) { ?>
		<link rel="shortlink" href="<?php echo Core::$META['shortlink']; ?>">
	<?php } ?>
	<?php echo Core::$META['head']; ?>
	<link rel="icon" href="/favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="apple-touch-icon" href="/touch-icon-iphone.png">
	<link rel="apple-touch-icon" sizes="76x76" href="/touch-icon-ipad.png">
	<link rel="apple-touch-icon" sizes="120x120" href="/touch-icon-iphone-retina.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/touch-icon-ipad-retina.png">
	<link href="/skins/components/bootstrap/bootstrap.min.css" rel="stylesheet">
	<link href="/skins/components/node_modules/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<link href="/skins<?php echo Core::$SKIN;?>/css/admin.min.css" rel="stylesheet">
	<script>
		var antixsrf = '<?php echo (isset($_SESSION['antixsrf']) ? $_SESSION['antixsrf'] : 'no'); ?>';
	</script>
	<!--[if lt IE 9]>
	<script src="/skins/components/bower/html5shiv/dist/html5shiv.min.js" defer></script>
	<script src="/skins/components/bower/respond/dest/respond.min.js" defer></script>
	<![endif]-->
	<script src="/skins/components/bower/jquery/dist/jquery.min.js"></script>
	<script src="/skins/components/bower/popper.js/dist/umd/popper.min.js"></script>
	<script src="/skins/components/bootstrap/bootstrap.min.js"></script>
	<script src="/vendor/schoolphp/library/Core/fw.min.js" defer></script>
	<script src="/skins<?php echo Core::$SKIN;?>/js/scripts.js"></script>
</head>
<body>
<?php
if(isAdmin()) { /* Login */ ?>
  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/">School-PHP ADMIN PANEL</a>
      </div>
      <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-right">
          <li><a href="#">Пункты меню</a></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Ядро <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="#">Создание своих модулей</a></li>
              <li><a href="#">Установка внешних модулей</a></li>
            </ul>
          </li>
          <li><a href="/login/exit">Выход</a></li>
        </ul>
        <form class="navbar-form navbar-right">
          <input type="text" class="form-control" placeholder="Search...">
        </form>
      </div>
    </div>
  </nav>
<?php } ?>
<?php echo $content; ?>
<footer>
  Разработано на Fox and Wolf Framework &copy; <?=Core::$CREATED;?> | <a href="mailto:inpost@list.ru"><i class="fa fa-envelope-o" aria-hidden="true"></i> inpost@list.ru</a>
</footer>
<?php echo \Core::$END; ?>
</body>
</html>