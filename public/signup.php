<?php

loadlib('user');

# Don't bother if we're signed in (how did they get here anyway?)
if (user_id()) { redirect('/'); }

# Must POST login information
if ('POST' != $_SERVER['REQUEST_METHOD']) { redirect('/'); }

# Try to create a user
if ($create = user_create(@$_POST['username'], @$_POST['email'],
	@$_POST['phone'], @$_POST['carrier'],
	@$_POST['password'], @$_POST['password2'])) {
	Sd('create', $create);
	Sd('username', @$_POST['username']);
	Sd('email', @$_POST['email']);
} else { redirect('/'); }
