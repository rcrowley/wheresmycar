<?php

require_once dirname(__FILE__) . '/config.php';

function loadlib($name) {
	require_once dirname(__FILE__) . "/lib_$name.php";
}

# On the command line, the debug function is just echo
function debug($msg) { echo $msg; }
