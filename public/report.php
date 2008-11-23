<?php

# If no one is logged in, just show the login/signup form
loadlib('user');
if (!user_id()) { redirect('/start'); }

# Update the address if requested
if ('POST' == $_SERVER['REQUEST_METHOD']) {
	loadlib('location');
	location_inaccurate();
}

redirect('/home');
