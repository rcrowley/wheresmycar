<?php

# If no one is logged in, just show the login/signup form
loadlib('user');
if (user_id()) { Sd('logged_in', true); }
else { redirect('/start'); }

if ('POST' == $_SERVER['REQUEST_METHOD']
	&& isset($_POST['code'])) {

	# Grab the SMS address to generate and compare the code
	$user = db_query("SELECT sms FROM users WHERE id = '" .
		user_id() . "' LIMIT 1;");
	Sd('confirm',
		substr(sha1($SALT . $user[0]['sms']), 0, 6) == $_POST['code']
		&& db_query("UPDATE users SET confirm_sms = '1' WHERE id = '" .
		user_id() . "' LIMIT 1;"));

} else { Sd('confirm', false); }
