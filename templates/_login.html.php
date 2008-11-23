<?php
return form(array('action' => '/login', 'method' => 'post'),
	p(
		label(array('for' => 'username'), 'Username:'),
		br(),
		input(array(
			'id' => 'username',
			'name' => 'username',
			'type' => 'text',
			'value' => Sd('username'),
			'class' => 'text stretch'
		))
	),
	p(
		label(array('for' => 'password'), 'Password:'),
		br(),
		input(array(
			'id' => 'password',
			'name' => 'password',
			'type' => 'password',
			'class' => 'text stretch'
		))
	),
	p(input(array('type' => 'submit', 'value' => 'Login', 'class' => 'button')))
);
