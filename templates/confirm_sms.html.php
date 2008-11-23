<?php
Sd('title', 'Confirm your cell phone number');
return Snil(
	p(Sc('confirm', true), array('class' => 'success'), 'Thanks!'),
	p(Sc('confirm', false), array('class' => 'error'),
		'The codedidn&rsquo;t match.'
	),
	p(a(array('href' => '/home'), 'Home'))
);
