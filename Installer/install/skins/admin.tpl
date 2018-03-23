<!DOCTYPE html>
<html lang="<?php echo Core::$LANGUAGE['html_locale']; ?>">
<head>
	<meta charset="UTF-8">
	<?php foreach (Core::$META['dns-prefetch'] as $v) { ?>
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
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<?php if (Core::$LANGUAGE['status']) {
		foreach (Core::$LANGUAGE['allow'] as $v) {
			if ($v != Core::$LANGUAGE['lang']) { ?>
				<link rel="alternate" hreflang="<?php echo $v; ?>" href="<?php echo createUrl('this', false, $v); ?>">
			<?php }
		}
	} ?>
	<?php if (!empty(Core::$META['prev'])) { ?>
		<link rel="prev" href="<?php echo Core::$META['prev']; ?>">
	<?php } ?>
	<?php if (!empty(Core::$META['next'])) { ?>
		<link rel="next" href="<?php echo Core::$META['next']; ?>">
	<?php } ?>
	<?php if (!empty(Core::$META['canonical'])) { ?>
		<link rel="canonical" href="<?php echo Core::$META['canonical']; ?>">
	<?php } ?>
	<?php if (!empty(Core::$META['shortlink'])) { ?>
		<link rel="shortlink" href="<?php echo Core::$META['shortlink']; ?>">
	<?php } ?>
	<link href="/skins/css/normalize.min.css" rel="stylesheet">
	<link href="/skins/components/bootstrap/bootstrap.min.css" rel="stylesheet">
	<link href="/skins/components/fontawesome-free-5.0.8/web-fonts-with-css/css/fontawesome-all.min.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans|Oswald" rel="stylesheet">
	<link href="/skins/css/admin.min.css<?=filemtime(\Core::$ROOT . '/skins/css/admin.min.css');?>" rel="stylesheet">
	<?php echo \Core::$META['head']; ?>
</head>
<body>
<?php
if (\FW\Access\Access::isAllowAdmin('admin')) { ?>
	<div class="header">
		<div class="row">
			<div class="col-8 col-sm-6">
				<span class="fa fa-bars admin-mobile-showblock" id="mobile-menu-icon" aria-hidden="true"></span>
				<span class="admin-mobile-hideblock"><?= \Core::$SITENAME; ?> • </span>
				Admin Panel
			</div>
			<div class="col-4 col-sm-6">
				<a href="/" class="admin-mobile-hideblock">Перейти на сайт •</a>
				<a href="/login/exit" id="exit">Выход</a>
			</div>
		</div>
	</div>
	<div id="mobile-freezone"></div>
	<nav id="main-nav">
		<div class="user clearfix">
			<img src="/uploads/avatar/<?= \User::$avatar; ?>" alt="">
			<?= \User::$login; ?>
			<br><span class="role"><?= \User::$role; ?></span>
		</div>
		<div class="menu">
			<a href="/admin" <?= ($_GET['_module'] == 'main' ? 'class="active"' : ''); ?>><i class="fas fa-tachometer-alt"></i> Главная страница</a>

			<?php if (\FW\Access\Access::isAllowKeys(['/admin/users/*'])) { ?>
				<span <?= ($_GET['_module'] == 'users' ? 'class="active"' : ''); ?> subMenu="nav-users"	class="clearfix subMenu">
					<i class="fas fa-user"></i> Пользователи
					<span class="submenu fas fa-caret-left"></span>
				</span>
				<div class="submenu <?php if ($_GET['_module'] != 'users') {echo 'hidden';} ?>" id="nav-users">
					<a href="/admin/users/view" <?= ($_GET['_page'] == 'view' ? 'class="active"' : ''); ?>>Пользователи</a>
					<a href="/admin/users/groups-view" <?= ($_GET['_page'] == 'groups-view' ? 'class="active"' : ''); ?>>Группы</a>
				</div>
			<?php } ?>

			<?php if (\FW\Access\Access::isAllowKeys(['/admin/administration/*'])) { ?>
				<span <?= ($_GET['_module'] == 'administration' ? 'class="active"' : ''); ?> subMenu="nav-administration" class="clearfix subMenu">
					<i class="fas fa-user-secret"></i> Администрирование
					<span class="submenu fas fa-caret-left"></span>
				</span>
				<div class="submenu <?php if ($_GET['_module'] != 'administration') {echo 'hidden';} ?>" id="nav-administration">
					<a href="/admin/administration/phpinfo" <?= ($_GET['_page'] == 'phpinfo' ? 'class="active"' : ''); ?>>PHPinfo</a>
					<a href="/admin/administration/c-s" <?= ($_GET['_page'] == 'c-s' ? 'class="active"' : ''); ?>>Cookie & Session</a>
					<a href="/admin/administration/localization" <?= ($_GET['_page'] == 'localization' ? 'class="active"' : ''); ?>>Localization</a>
				</div>
			<?php } ?>
		</div>
	</nav>
	<main>
		<h1><?=htmlspecialchars(\Core::$TITLE); ?></h1>
		<div><?=$content; ?></div>
	</main>
<?php } else {
	echo $content;
} ?>

<footer>
	<?=\Core::$SITENAME; ?> &copy; <?=\Core::$CREATED.(\Core::$CREATED !== date('Y') ? '-'.date('Y') : ''); ?> |
	<a href="mailto:<?=\Core::$ADMIN; ?>"><i class="far fa-envelope" aria-hidden="true"></i> <?= \Core::$ADMIN; ?></a>
</footer>


<script>
	var antixsrf = '<?php echo(isset($_SESSION['antixsrf']) ? $_SESSION['antixsrf'] : 'no'); ?>';
</script>
<script src="/skins/components/node_modules/jquery/dist/jquery.min.js"></script>
<script src="/skins/components/node_modules/popper.js/dist/umd/popper.min.js"></script>
<script src="/skins/components/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="/vendor/schoolphp/library/Core/fw.js"></script>
<script src="/skins<?php echo Core::$SKIN; ?>/js/scripts.js?<?=filemtime(\Core::$ROOT . '/skins'.\Core::$SKIN.'/js/scripts.js');?>"></script>
<script src="/skins/js/admin.js?<?=filemtime(\Core::$ROOT . '/skins/js/admin.js');?>"></script>
<?php echo \Core::$END; ?>
</body>
</html>