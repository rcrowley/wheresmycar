<?php

# Firefox and most phones will cache aggressively without this
header("Cache-Control: no-cache, must-revalidate\r\n");

# If no one is logged in, just show the login/signup form
loadlib('user');
if (user_id()) { Sd('logged_in', true); }
else { redirect('/start'); }

# Update the address if requested
loadlib('location');
if ('POST' == $_SERVER['REQUEST_METHOD']
	&& !location_set_address(@$_POST['address'])) {
	Sd('error', 'Error updating location.');
}

$location = location_get();
if (is_array($location)) {
	Sd('address', $location['address']);
	if ($location['sweep_ts']) {
		Sd('sweep', date($DATEFORMAT, $location['sweep_ts']));
	}
	Sd('impossible', (bool)$location['impossible']);
	Sd('inaccurate', (bool)$location['inaccurate']);
}

# See if we still need the SMS confirmation form
$c = db_query("SELECT confirm_sms FROM users WHERE id = '" .
	user_id() . "' LIMIT 1;");
Sd('need_confirm_sms', !is_array($c) || !sizeof($c) || !$c[0]['confirm_sms']);
