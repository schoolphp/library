<!DOCTYPE html>
<html lang="<?php echo Core::$LANGUAGE['lang']; ?>">
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
<link rel="icon" href="/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

<!--
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/manifest.json">
<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#ffffff">
-->

<link rel="apple-touch-icon" href="/touch-icon-iphone.png">
<link rel="apple-touch-icon" sizes="76x76" href="/touch-icon-ipad.png">
<link rel="apple-touch-icon" sizes="120x120" href="/touch-icon-iphone-retina.png">
<link rel="apple-touch-icon" sizes="152x152" href="/touch-icon-ipad-retina.png">
<style><?php include './skins/css/normalize.min.css'; include './skins/css/begin.min.css'; ?></style>
<?php echo Core::$META['head']; ?>
</head>
<body>
<header>
	<div>FrameWork</div>
	<img src="/vendor/schoolphp/library/Installer/install/skins/img/logo2.jpg" alt="School-PHP FrameWork">
	<nav class="clearfix">
		<a href="/">На главную</a>
	</nav>
</header>
<main>
	<?=$content;?>
</main>
<footer>
	Подвал сайта
</footer>
<?php /**
 * !Note:
 * Пожалуйста, пересоздайте новую версию bootstrap на SASS для будущей поддержи в новых версиях проекта!
 * Сейчас подключена бета версия, пересобранная:
 * 1) убираем normalize, так как мы используем другой. Я бы советовал последнюю версию закачать с официального сайта. Единственное я добавил в сборку: убрал отступы для абзацев и заголовков, добавил font-size и line-height для body!
 * 2) Убираем глобальные свойства для части тегов в некоторых sass файлах, такие как html, body, a, h1-h6, p и другие. Делается очень просто, достаточно все файлы по очереди открыть и просмотреть глазками, не более 10-15 минут!
<link href="/skins/components/bower/bootstrap-new/scss/bootstrap.min.css" rel="stylesheet">
 * Ну а для самых ленивых я подключу ту самую старую сборку от версии 4.0.бета:
 * И ещё, не забудьте подключить клёвые шрифты, которые необходимо сказать будет с офф-сайта:
 * <link href="/skins/components/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
 */ ?>
<link href="/skins/css/bootstrap.min.css" rel="stylesheet">
<link href="/skins/css/other-end.min.css" rel="stylesheet">
<link href="/skins/components/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
<?php if(count(Core::$CSS)) {echo '<link href="'.implode('" rel="stylesheet">'."\n".'<link href="',Core::$CSS).'" rel="stylesheet">';} ?>
<script>
	var antixsrf = '<?php echo (isset($_SESSION['antixsrf']) ? $_SESSION['antixsrf'] : 'no'); ?>';
</script>
<!--[if lt IE 9]>
<script src="/skins/components/bower/html5shiv/dist/html5shiv.min.js" defer></script>
<script src="/skins/components/bower/respond/dest/respond.min.js" defer></script>
<![endif]-->
<script src="/skins/components/bower/jquery/dist/jquery.min.js"></script>
<script src="/skins/components/bower/popper.js/dist/umd/popper.min.js"></script>
<script src="/skins/components/bower/bootstrap/dist/js/bootstrap.min.js" defer></script>
<script src="/vendor/schoolphp/library/Core/fw.min.js" defer></script>
<script src="/skins<?php echo Core::$SKIN;?>/js/scripts.js"></script>
<?php if(count(Core::$JS)) {echo '<script src="'.implode('"></script>'."\n".'<script src="',Core::$JS).'" defer></script>';} ?>
<?php echo Core::$END; ?>
</body>
</html>