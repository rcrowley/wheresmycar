<?php

loadlib('db');
loadlib('user');

# Return an array containing the address and sweeping information for a user
function location_get($user_id = false) {
	if (!$user_id) { $user_id = user_id(); }
	$location = db_query("SELECT address, sweep_ts, impossible, inaccurate
		FROM users WHERE id = '" . (int)$user_id . "' LIMIT 1;");
	if (is_array($location) && 1 == sizeof($location)) {
		return $location[0];
	} else { return false; }
}

# Update the address stored for a user
function location_set_address($address, $user_id = false) {
	if (!$user_id) { $user_id = user_id(); }
	$result = db_query("UPDATE users SET address = '" . db_quote($address) .
		"', impossible = '0', sweep_ts = NULL, inaccurate = '0',
		sent_email = '0', sent_sms = '0' WHERE id = '" . (int)$user_id .
		"' LIMIT 1;");
	return $result;
}

# Save the street sweeping time
function location_set_sweep($ts, $user_id = false) {
	if (!$user_id) { $user_id = user_id(); }
	return db_query("UPDATE users SET sweep_ts = '" . (int)$ts .
		"' WHERE id = '" . (int)$user_id . "' LIMIT 1;");
}

# Mark street sweeping as inaccurate
function location_inaccurate($user_id = false) {
	if (!$user_id) { $user_id = user_id(); }

	# Mail
	$user = db_query("SELECT username, address, sweep_ts FROM users
		WHERE id = '" . (int)$user_id . "' LIMIT 1;");
	$date = date($GLOBALS['DATEFORMAT'], $user[0]['sweep_ts']);
	mail('r@rcrowley.org', 'Inaccurate street sweeping',
		"Username: {$user[0]['username']}\r\n" .
		"Street address: {$user[0]['address']}\r\n" .
		"Sweeping time: $date\r\n",
		"From: Where's my car? <nobody@car.rcrowley.org>\r\n");

	db_query("UPDATE users SET inaccurate = '1' WHERE id = '" .
		(int)$user_id . "' LIMIT 1;");
}

# Mark an impossible-to-geocode location so we don't try again
function location_impossible($user_id = false) {
	if (!$user_id) { $user_id = user_id(); }
	db_query("UPDATE users SET impossible = '1', sweep_ts = NULL
		WHERE id = '" . (int)$user_id . "';");
}
