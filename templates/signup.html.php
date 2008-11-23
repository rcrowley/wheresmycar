<?php
Sd('title', 'Signup');
switch (Sd('create')) {
	case '1':
		Sd('error', 'Usernames must be between 4 and 40 characters.');
	break;
	case '2':
		Sd('error', 'Please confirm your password.');
	break;
	case '3':
		Sd('error', 'Passwords must be between 4 and 40 characters.');
	break;
	case '4':
		Sd('error', 'Something went wrong here.&nbsp; Are you sure you&rsquo;re not already registered?');
	break;
}
return Snil(
	Sf('_signup.html.php'),
	p(
		'Already have an account?&nbsp; ',
		a(array('href' => '/start#login'), 'Login')
	)
);
