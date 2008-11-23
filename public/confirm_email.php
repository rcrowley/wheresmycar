<?php

loadlib('user');

# Must have a hash in the URL
if (1 != sizeof($URL_PARTS)) { Sd('confirm', false); }

# Try to mark the email address as confirmed
#   TODO: Indexed query
else {
	Sd('confirm', db_query("UPDATE users SET confirm_email = '1' WHERE
		SHA1(CONCAT('$SALT', email)) = '{$URL_PARTS[0]}' LIMIT 1;"));
}

Sd('logged_in', user_id() ? true : false);
