<?php

	//==========================================================//
	//====						PATTERNS					====//
	//==========================================================//


	//pattern regex
	$safeStringPattern = '[0-9a-zA-Z_\-]+';
	
	//patterns
	Route::pattern('safestr', $safeStringPattern);
	Route::pattern('safestr2', $safeStringPattern);
	Route::pattern('id', '[0-9]+');
	




	//==========================================================//
	//====					ROUTE FILTERS					====//
	//==========================================================//	


	Route::filter('internalAccess', function()
	{
	    //ensure IP's match, else deny access - 404
	    if (Request::server('SERVER_ADDR') != Request::server('REMOTE_ADDR'))
	    {
	        return App::abort(404);
	    }
	});
	
	
	Route::filter('secureAccess', function()
	{		
	    //ensure user is logged in
	    //$user = Auth::CMSuser()->user();
	    //echo "USER: " . print_r(Auth::CMSuser()->guest(), true);
	    //exit(0);
		if (Auth::CMSuser()->guest()) {
	        return Redirect::to('/cms/login');
	    }
	    
	});
	



	//==========================================================//
	//====						CMS ROUTING					====//
	//==========================================================//	

	
	Route::group(array('before' => 'secureAccess'), function() {
		Route::controller('cms/app', 'ApplicationController');
	//});
	
	//Route::group(array('before' => 'secureAccess'), function() {
		Route::controller('cms/form', 'FormController');
	});
	
	
	
	Route::get('cms/login', 'CMSController@getLogin');
	Route::post('cms/login', 'CMSController@postLogin');
	
	Route::group(array('before' => 'secureAccess'), function() {
		Route::controller('cms', 'CMSController');
	});
	
	//FORM ROUTES
//	Route::get('form/table', array('before' => 'internalAccess', 'uses' => 'FormController@getTable'));
//	Route::get('form/table/{safestr}', array('before' => 'internalAccess', 'uses' => 'FormController@getTable'));
//	Route::get('form/field', array('before' => 'internalAccess', 'uses' => 'FormController@getField'));
//	Route::get('form/field/{safestr}', array('before' => 'internalAccess', 'uses' => 'FormController@getField'));
//	Route::get('form/field/{safestr}/{safestr2}', array('before' => 'internalAccess', 'uses' => 'FormController@getField'));
	



?>
