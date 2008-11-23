<?php

loadlib('db');

$GLOBALS['_user_id'] = false;

function user_id($force = false) {
	global $_user_id;
	if ((!$_user_id || $force) && isset($_COOKIE['car'])) {
		$cookie = unserialize($_COOKIE['car']);
		if (is_array($cookie)) {
			$user = db_query("SELECT id FROM users WHERE id = '" .
				(int)@$cookie['user_id'] . "' AND hash = '" .
				db_quote(@$cookie['hash']) . "' AND hash IS NOT NULL LIMIT 1;");
			if (is_array($user) && sizeof($user)) {
				$_user_id = (int)$user[0]['id'];
			} else {
				setcookie('car', '', strtotime('+1 year'), '/',
					'car.rcrowley.org');
			}
		}
	}
	return $_user_id;
}

function user_login($username, $password) {
	$user = db_query("SELECT id, hash FROM users WHERE username = '" .
		db_quote($username) . "' AND password = SHA1('" .
		db_quote($GLOBALS['SALT'] . $password) . "');");
	if (is_array($user) && sizeof($user)) {
		if ($user[0]['hash']) {
			$hash = $user[0]['hash'];
		} else {
			$hash = sha1($GLOBALS['SALT'] . $username . time());
			$success = db_query("UPDATE users SET hash = '" . db_quote($hash) .
				"' WHERE id = '" . (int)$user[0]['id'] . "' LIMIT 1;");
			if (!$success) {
				return false;
			}
		}
		global $_user_id;
		$_user_id = (int)$user[0]['id'];
		setcookie('car', serialize(array(
			'user_id' => $_user_id,
			'hash' => $hash
		)), strtotime('+1 year'), '/', 'car.rcrowley.org');
		return true;
	}
	return false;
}

function user_logout() {
	if ($id = user_id()) {
		db_query("UPDATE users SET hash = NULL WHERE id = '" .
			(int)$id . "' LIMIT 1;");
	}
	$GLOBALS['_user_id'] = false;
	setcookie('car', '', strtotime('+1 day'), '/', 'car.rcrowley.org');
}

# Create a user and return 0 or an error code
function user_create($username, $email, $sms, $carrier, $password,
	$confirm_password) {

	# A bit of error checking
	$len = strlen($username);
	if ('' == $username || 4 > $len || 40 < $len) {
		return 1;
	}
	if ($password != $confirm_password) { return 2; }
	$len = strlen($password);
	if (4 > $len || 40 < $len) { return 3; }

	# Only digits in the phone number
	$sms = preg_replace('/[^0-9]/', '', $sms);

	# Insert the user
	global $_user_id;
	$hash = sha1($GLOBALS['SALT'] . $username . time());
	if ($_user_id = db_query("INSERT INTO users (username, email, sms,
		password, hash, created) VALUES ('" . db_quote($username) . "', '" .
		db_quote($email) . "', '" . db_quote("{$sms}{$carrier}") .
		"', SHA1('" . db_quote($GLOBALS['SALT'] . $password) .
		"'), '$hash', NOW());")) {

		# Send email confirmation
		mail($email, "Where's my car? - Email confirmation",
			"Click the link below to confirm your email address and start " .
			"receiving email reminders to move your car.\n" .
			"http://car.rcrowley.org/confirm/email/" .
			sha1($GLOBALS['SALT'] . $email),
			"From: Where's my car? <nobody@car.rcrowley.org>\r\n");

		# Send SMS confirmation
		mail("{$sms}{$carrier}", '', 'Enter the confirmation code ' .
			substr(sha1($GLOBALS['SALT'] . "{$sms}{$carrier}"), 0, 6) .
			' at car.rcrowley.org',
			"From: Where's my car?\r\n");

		# Login
		setcookie('car', serialize(array(
			'user_id' => $_user_id,
			'hash' => $hash
		)), strtotime('+1 year'), '/', 'car.rcrowley.org');

		return 0;
	}

	# Key error when INSERTing
	return 4;

}
