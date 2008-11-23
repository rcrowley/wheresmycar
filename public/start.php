<?php

# If no one is logged in, just show the login/signup form
loadlib('user');
if (user_id()) { redirect('/home'); }
else {
	Sd('logged_in', false);
}
