<?php

	//extend auth config
	return array(
	
		//insert CMS user authentication
		'CMSuser' => array(
			'driver' => 'eloquent',
			'model' => 'CMSUser',
			'table' => 'user',
			//'reminder' => array(
			//	'email' => 'cms::emails.auth.reminder',
			//	'table' => 'password_reminders',
			//	'expire' => 60,
			//),
		),
		
		
	); //end 'multi'


?>