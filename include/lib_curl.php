<?php

function curl_quickie($url, $post = false, $cookie = false,
	$headers = false, $print = false) {
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	if (false !== $post) {
		curl_setopt($ch, CURLOPT_POST, true);
		if (is_array($post)) { $post = http_build_query($post); }
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}
	if ($cookie) {
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cookie' => $cookie));
	}
	if ($headers) { curl_setopt($ch, CURLOPT_HEADER, true); }
	if ($print) {
		curl_exec($ch);
		return curl_getinfo($ch, CURLINFO_HTTP_CODE);
	}
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$raw = curl_exec($ch);
	return array(curl_getinfo($ch, CURLINFO_HTTP_CODE), &$raw);
}
