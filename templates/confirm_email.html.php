<?php
Sd('title', 'Confirm your email address');
return Snil(
	p(Sc('confirm', true), array('class' => 'success'), 'Thanks!'),
	p(Sc('confirm', false), array('class' => 'error'),
		'The signature didn&rsquo;t match.'
	),
	p(Sc('logged_in', true), a(array('href' => '/home'), 'Home')),
	p(Sc('logged_in', false), a(array('href' => '/start'), 'Login')),
);
