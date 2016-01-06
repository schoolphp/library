<style>
	html {background-color: grey;}
	body {background-color: white; max-width: 1000px; margin: 0 auto; padding: 30px;}
	header {text-align:center; background: url(/vendor/schoolphp/library/Installer/install/skins/img/logo2-bg.jpg) repeat-x; position:relative; margin: -40px -30px 0px -30px; padding-bottom: 0px;}
	header div {position:absolute;top: 259px;left: 41%;font-family: Georgia;font-size: 30px;color: #605C5D;font-style: oblique;}
	nav {margin-bottom: 20px;}
	nav a {display: inline-block;padding: 10px;border: 2px solid #eee;}
</style>
<?php if(!$res->num_rows) { ?>
	<div>Отсутствуют записи</div>
<?php } else { ?>
	<ul style="width:150px; margin:auto;">
		<?php while($row = $res->fetch_assoc()) { ?>
			<li><?php echo $row['email']; ?></li>
		<?php } ?>
	</ul>
<?php } ?>

<div style="text-align:center;"><?=\FW\Pagination\Pagination::nav();?></div>
<hr>
