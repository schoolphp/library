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
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="format-detection" content="telephone=no">
<meta name="format-detection" content="address=no">

<meta name="robots" content="index, follow">
<meta http-equiv="Content-Language" content="ru">

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

<link rel="apple-touch-icon" href="touch-icon-iphone.png">
<link rel="apple-touch-icon" sizes="76x76" href="touch-icon-ipad.png">
<link rel="apple-touch-icon" sizes="120x120" href="touch-icon-iphone-retina.png">
<link rel="apple-touch-icon" sizes="152x152" href="touch-icon-ipad-retina.png">

<!--[if lt IE 9]>
  <script src="/skins/js/html5shiv.js"></script>
  <script src="/skins/js/respond.min.js"></script>
<![endif]-->

<link href="/skins/components/bower/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="/skins/components/bower/bootstrap/dist/css/bootstrap-theme.min.css" rel="stylesheet">
<script src="/skins/components/bower/jquery/dist/jquery.min.js"></script>
<script src="/skins/components/bower/bootstrap/dist/js/bootstrap.min.js"></script>

<link href="/skins<?php echo Core::$SKIN;?>/css/admin.css" rel="stylesheet">
<script src="/skins<?php echo Core::$SKIN;?>/js/fw.js"></script>
<script src="/skins<?php echo Core::$SKIN;?>/js/scripts.js"></script>

<?php if(count(Core::$CSS)) {echo '<link href="'.implode('" rel="stylesheet">'."\n".'<link href="',Core::$CSS).'" rel="stylesheet">';} ?>
<?php if(count(Core::$JS)) {echo '<script src="'.implode('"></script>'."\n".'<script src="',Core::$JS).'"></script>';} ?>

<script>
var antixsrf = '<?php echo $_SESSION['antixsrf']; ?>';
</script>

<link rel="stylesheet" href="/skins/components/hl/github.css">
<script src="/skins/components/hl/highlight.pack.js"></script>
<script>
$(document).ready(function() {
	$('pre code').each(function(i, block) {
		console.log(block);
		hljs.highlightBlock(block);
	});
});
</script>
<style>
h1 {
	text-align:left;
	color:#793862;
	border-bottom:1px dotted #793862;
	margin-bottom:20px;
	padding-bottom:10px;
}
code {
	border: 1px #EEE solid;
	background-color:white;
	padding:5px;
	margin:5px;
	display:block;
	padding-left:20px;
}
.codelines {
	display:none;
}

</style>
</head>

<body>

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
        <li><a href="/admin/tasks">Заметки</a></li>
        <li><a href="#">Help</a></li>
      </ul>
      <form class="navbar-form navbar-right">
        <input type="text" class="form-control" placeholder="Search...">
      </form>
    </div>
  </div>
</nav>

<div class="container-fluid">
  <div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
      <ul class="nav nav-sidebar">
        <li class="active"><a href="#">Подразделы</a></li>
      </ul>
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
      <?php echo $content; ?>

      <h2 class="sub-header">Section title</h2>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Header</th>
              <th>Header</th>
              <th>Header</th>
              <th>Header</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1,001</td>
              <td>Lorem</td>
              <td>ipsum</td>
              <td>dolor</td>
              <td>sit</td>
            </tr>
            <tr>
              <td>1,002</td>
              <td>amet</td>
              <td>consectetur</td>
              <td>adipiscing</td>
              <td>elit</td>
            </tr>
            <tr>
              <td>1,003</td>
              <td>Integer</td>
              <td>nec</td>
              <td>odio</td>
              <td>Praesent</td>
            </tr>
            <tr>
              <td>1,003</td>
              <td>libero</td>
              <td>Sed</td>
              <td>cursus</td>
              <td>ante</td>
            </tr>
            <tr>
              <td>1,004</td>
              <td>dapibus</td>
              <td>diam</td>
              <td>Sed</td>
              <td>nisi</td>
            </tr>
            <tr>
              <td>1,005</td>
              <td>Nulla</td>
              <td>quis</td>
              <td>sem</td>
              <td>at</td>
            </tr>
            <tr>
              <td>1,006</td>
              <td>nibh</td>
              <td>elementum</td>
              <td>imperdiet</td>
              <td>Duis</td>
            </tr>
            <tr>
              <td>1,007</td>
              <td>sagittis</td>
              <td>ipsum</td>
              <td>Praesent</td>
              <td>mauris</td>
            </tr>
            <tr>
              <td>1,008</td>
              <td>Fusce</td>
              <td>nec</td>
              <td>tellus</td>
              <td>sed</td>
            </tr>
            <tr>
              <td>1,009</td>
              <td>augue</td>
              <td>semper</td>
              <td>porta</td>
              <td>Mauris</td>
            </tr>
            <tr>
              <td>1,010</td>
              <td>massa</td>
              <td>Vestibulum</td>
              <td>lacinia</td>
              <td>arcu</td>
            </tr>
            <tr>
              <td>1,011</td>
              <td>eget</td>
              <td>nulla</td>
              <td>Class</td>
              <td>aptent</td>
            </tr>
            <tr>
              <td>1,012</td>
              <td>taciti</td>
              <td>sociosqu</td>
              <td>ad</td>
              <td>litora</td>
            </tr>
            <tr>
              <td>1,013</td>
              <td>torquent</td>
              <td>per</td>
              <td>conubia</td>
              <td>nostra</td>
            </tr>
            <tr>
              <td>1,014</td>
              <td>per</td>
              <td>inceptos</td>
              <td>himenaeos</td>
              <td>Curabitur</td>
            </tr>
            <tr>
              <td>1,015</td>
              <td>sodales</td>
              <td>ligula</td>
              <td>in</td>
              <td>libero</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

</body>

</html>