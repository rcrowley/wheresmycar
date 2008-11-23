<?php
return form(array('action' => '/signup', 'method' => 'post'),
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
		label(array('for' => 'email'), 'E-Mail:'),
		br(),
		input(array(
			'id' => 'email',
			'name' => 'email',
			'type' => 'text',
			'value' => Sd('email'),
			'class' => 'text stretch'
		))
	),
	p(
		label(array('for' => 'phone'), 'Cell phone number:'),
		br(),
		input(array(
			'id' => 'phone',
			'name' => 'phone',
			'type' => 'text',
			'value' => Sd('phone'),
			'class' => 'text stretch'
		))
	),
	p(
		label(array('for' => 'carrier'), 'Cell phone carrier:'),
		br(),
		select(array('id' => 'carrier', 'name' => 'carrier'),
			option(array('value' => '@txt.att.net'), 'AT&amp;T'),
			option(array('value' => '@messaging.sprintpcs.com'), 'Sprint'),
			option(array('value' => '@tmomail.net'), 'T-Mobile'),
			option(array('value' => '@vtext.com'), 'Verizon')
		)
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
	p(
		label(array('for' => 'password2'), 'Confirm password:'),
		br(),
		input(array(
			'id' => 'password2',
			'name' => 'password2',
			'type' => 'password',
			'class' => 'text stretch'
		))
	),
	p(input(
		array('type' => 'submit', 'value' => 'Signup', 'class' => 'button')
	))
);
