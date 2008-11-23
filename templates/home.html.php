<?php
return Snil(
	form(
		array('action' => '/report', 'method' => 'post'),
		Sc('address'),

		# It'd be nice to have conditions for what node name will be
		#Sc('inaccurate', false),

		h1(
			small('Currently:&nbsp; '),
			Sd('address'),
			br(),
			Snil(Sc('sweep', true),
				small('That street is swept:&nbsp; '),
				Sd('sweep'),
				'&nbsp; ',
				Sif(Sc('inaccurate', true), '(inaccurate)'),
				Sif(Sc('inaccurate', false),
					small(small(input(array(
						'type' => 'submit',
						'value' => 'Report as inaccurate',
						'class' => 'link'
					))))
				)
			),
			Sif(Sc('sweep', false), Sc('impossible', true),
				small('This address was impossible to geocode')
			),
			Sif(Sc('sweep', false), Sc('impossible', false),
				small(
					'Street sweeping information will be available shortly&nbsp; ',
					small(a(array('href' => '/home'), 'Refresh'))
				)
			)
		)
	),
	form(array('action' => Sd('URL'), 'method' => 'post'),
		p(
			label(array('for' => 'address'), 'My car is at...'),
			br(),
			input(array(
				'id' => 'address',
				'name' => 'address',
				'type' => 'text',
				'value' => Sd('new_address'),
				'class' => 'text stretch'
			)),
			br(),
			'Only the closest street number and the street name, please.',
			br(),
			small('Don&rsquo;t choose a corner address because SF GIS might return inaccurate street sweeping data.&nbsp; Make sure you use the proper side of the street so we can accurately predict the street sweeping schedule.')
		),
		p(input(array(
			'type' => 'submit',
			'value' => 'Update',
			'class' => 'button'
		)))
	),
	Sif(Sc('need_confirm_sms'),
		h2('Confirm your cell phone number for SMS'),
		form(array('action' => '/confirm/sms', 'method' => 'post'),
			p(
				label(array('for' => 'code'), 'SMS confirmation code:'),
				br(),
				input(array(
					'id' => 'code',
					'name' => 'code',
					'type' => 'text',
					'class' => 'text stretch'
				)),
				br(),
				small('Enter the code sent to your cell phone to have reminders sent to your phone.')
			),
			p(input(array(
				'type' => 'submit',
				'value' => 'Confirm',
				'class' => 'button'
			)))
		)
	)
);
