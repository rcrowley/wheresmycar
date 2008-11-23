<?php
return html(
	head(
		meta(array(
			'http-equiv' => 'Content-Type',
			'content' => 'text/html; charset=UTF-8'
		)),
		meta(array(
			'id' => 'viewport',
			'name' => 'viewport',
			'content' => 'width=320; initial-scale=1.0; ' .
				'maximum-scale=1.0; user-scalable=0;'
		)),
		title(Sc('title'), Sd('title'), ' &mdash; Where&rsquo;s my car?'),
		title(Sc('title', false), 'Where&rsquo;s my car?'),
		S('link', array(array(
			'type' => 'text/css',
			'rel' => 'stylesheet',
			'href' => '/css/style.css'
		)))
	),
	body(
		div(array('id' => 'head'),
			a(array('href' => '/'), 'Where&rsquo;s my car?')
		),
		div(array('id' => 'content'),
			h1(Sc('title'), Sd('title')),
			p(array('class' => 'error'), Sc('error'), Sd('error')),
			p(array('class' => 'status'), Sc('status'), Sd('status')),
			p(array('class' => 'success'), Sc('success'), Sd('success')),

			Sl()

		),
		div(array('id' => 'foot'),
			p(Sc('logged_in'), a(array('href' => '/logout'), 'Logout')),
			p('By ', a(array('href' => 'http://rcrowley.org/'),
				'Richard Crowley'
			))
		)
	)
);
