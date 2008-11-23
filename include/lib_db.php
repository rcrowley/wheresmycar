<?php

$GLOBALS['_db_link'] = false;

function db_connect() {
	global $_db_link;
	$_db_link = mysql_connect(
		$GLOBALS['DB_HOST'],
		$GLOBALS['DB_USER'],
		$GLOBALS['DB_PASS'],
		true
	);
	if (!is_resource($_db_link)) { return false; }
	return mysql_select_db($GLOBALS['DB_NAME'], $_db_link);
}

function db_query($sql, $debug = false) {
	global $_db_link;
	if (!is_resource($_db_link)) {
		if ($debug) { debug("[db] db_connect\n"); }
		db_connect();
	}
	$result = mysql_query($sql, $_db_link);
	if ($debug) { debug("[db] db_query sql: $sql\n"); }
	if (0 != mysql_errno($_db_link)) {
		if ($debug) {
			debug('[db] db_query error: ' . mysql_error($_db_link) . "\n");
		}
		return false;
	}
	$op = substr($sql, 0, 6);
	if ('SELECT' == $op) {
		$rows = array();
		if (0 == mysql_num_rows($result)) { return $rows; }
		while ($row = mysql_fetch_assoc($result)) { $rows[] = $row; }
		return $rows;
	} else if ('INSERT' == $op) {
		$id =  mysql_insert_id($_db_link);
		if ($id) { return $id; }
	}
	return 0 == mysql_errno($_db_link);
}

function db_quote($unsafe) {
	return addslashes($unsafe);
}
