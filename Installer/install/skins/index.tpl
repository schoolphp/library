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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
<link href="/skins/components/bower/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="/skins/components/bower/bootstrap/dist/css/bootstrap-theme.min.css" rel="stylesheet">
<link href="/skins/<?=Core::$SKIN;?>/css/styles.min.css" rel="stylesheet">
<?php if(count(Core::$CSS)) {echo '<link href="'.implode('" rel="stylesheet">'."\n".'<link href="',Core::$CSS).'" rel="stylesheet">';} ?>
<script>
var antixsrf = '<?php echo (isset($_SESSION['antixsrf']) ? $_SESSION['antixsrf'] : 'no'); ?>';
</script>
<!--[if lt IE 9]>
<script src="/skins/js/html5shiv.js" defer></script>
<script src="/skins/js/respond.min.js" defer></script>
<![endif]-->
<script src="/skins/components/bower/jquery/dist/jquery.min.js" defer></script>
<script src="/skins/components/bower/bootstrap/dist/js/bootstrap.min.js" defer></script>
<script src="/skins<?=Core::$SKIN;?>/js/fw.min.js" defer></script>
<?php if(count(Core::$JS)) {echo '<script src="'.implode('"></script>'."\n".'<script src="',Core::$JS).'" defer></script>';} ?>
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
</body>
</html>