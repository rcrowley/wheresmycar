<?php

loadlib('user');

# Don't bother if we're signed in (how did they get here anyway?)
if (user_id()) { redirect('/'); }

# Must POST login information
if ('POST' != $_SERVER['REQUEST_METHOD']) { redirect('/start'); }

# Try to login
if (user_login(@$_POST['username'], @$_POST['password'])) {
	redirect('/home');
} else {
	Sd('username', @$_POST['username']);
}
