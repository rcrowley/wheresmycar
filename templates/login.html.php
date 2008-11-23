<?php
Sd('title', 'Login');
Sd('error',
	'Something went wrong.&nbsp; Are you sure that&rsquo;s your password?');
return Snil(
	Sf('_login.html.php'),
	p(
		'Need to create an account?&nbsp; ',
		a(array('href' => '/start#signup'), 'Signup')
	)
);
