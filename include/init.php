<?php

require_once dirname(__FILE__) . '/config.php';

require_once dirname(__FILE__) . '/sometimes/sometimes.php';
require_once dirname(__FILE__) . '/sometimes/xhtml11.php';
$GLOBALS['SOMETIMES_TEMPLATEDIR'] = dirname(__FILE__) . '/../templates';

function loadlib($name) {
	require_once dirname(__FILE__) . "/lib_$name.php";
}

function redirect($url) {
	header("Location: $url\r\n");
	echo <<<EOD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://w3.org/TR/xhtml1/DTD/xhtml1.1.dtd">
<html><head></head><body><p><a href="$url">In ur headers, sendin&rsquo; 302.</a></p></body></html>
EOD;
	exit;
}

# The debug function on the website should only print if we're in dev
function debug($msg) {
	if ('dev' != substr($_SERVER['HTTP_HOST'], 0, 3)) { return; }
	if ('json' != @$GLOBALS['FORMAT']) { echo "<!--\n$msg-->\n"; }
}
