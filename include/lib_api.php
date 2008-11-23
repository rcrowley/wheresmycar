<?php

# Force the output format to JSON
header('Content-Type: text/plain');
$GLOBALS['FORMAT'] = 'json';

function api($out) {
	if (is_array($out)) {
		die(json_encode(array(
			'stat' => 'ok',
			'rsp' => $out
		)));
	} else {
		die(json_encode(array(
			'stat' => 'fail',
			'error' => $out
		)));
	}
}
