<?php

/*
$GLOBALS['_cache'] = memcache_connect('localhost', 11211);

function cache_set($key, $value, $expire = 0) {
	return memcache_set($GLOBALS['_cache'], $key, $value, $expire);
}

function cache_get($key) {
	return memcache_get($GLOBALS['_cache'], $key);
}
*/

function cache_set($key, $value, $expire = 0) {
	return false;
}

function cache_get($key) {
	return false;
}
