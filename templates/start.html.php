<?php
return Snil(
	p('Keep track of where* your car is parked and get email and SMS alerts when you need to move it for street cleaning.&nbsp; (* Where in San Francisco, that is.)'),
	h1(array('id' => 'login'), 'Login'),
	Sf('_login.html.php'),
	h1(array('id' => 'signup'), 'Signup'),
	Sf('_signup.html.php')
);
