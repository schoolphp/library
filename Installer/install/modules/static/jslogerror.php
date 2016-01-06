<?php
if(isset($_POST['logJSErr'])) {
	file_put_contents('./logs/js.log', strip_tags(
		'Date: '.date("Y-m-d H:i:s")."\r\n".
		'Message: '.$_POST['message']."\r\n".
		'url: '.$_POST['url']."\r\n".
		'line: '.$_POST['line']."\r\n".
		"===================================\r\n\r\n"
	),FILE_APPEND);
}

echo '+';
exit;
