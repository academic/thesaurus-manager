<?php

return array(

	'driver' => 'eloquent',
	'model' => 'User',
	'table' => 'users',
	'reminder' => array(

		'email' => 'emails.auth.reminder', 'table' => 'password_reminders',

	),

);