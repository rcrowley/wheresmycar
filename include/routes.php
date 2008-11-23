<?php

# Routes are a regular-expression-style (without ^ or $ anchors) patterns
# of the URI-portion of a URL and point to a PHP file in public/.  Any
# sub-patterns matched will be made available in $URL_PARTS

$routes = array(

	'/confirm/email/([0-9a-f]+)' => 'confirm_email.php',
	'/confirm/sms' => 'confirm_sms.php',

	'/login' => 'login.php',
	'/logout' => 'logout.php',
	'/signup' => 'signup.php',

	'/report' => 'report.php',

	'/home' => 'home.php',
	'/start' => 'start.php',
	'/' => 'bounce.php',

	'' => '404.php'

);
