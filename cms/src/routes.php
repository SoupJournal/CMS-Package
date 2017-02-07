<?php

	//==========================================================//
	//====						CONFIG						====//
	//==========================================================//

	//get base path for cms
	$basePath = \Config::get('cms::config.route.path');




	//==========================================================//
	//====						PATTERNS					====//
	//==========================================================//


	//pattern regex
	$safeStringPattern = '[0-9a-zA-Z_\-]+';
	
	//patterns
	Route::pattern('appId', '[0-9]+');
	Route::pattern('safestr', $safeStringPattern);
	Route::pattern('safestr2', $safeStringPattern);
	Route::pattern('id', '[0-9]+');
	




	//==========================================================//
	//====					ROUTE FILTERS					====//
	//==========================================================//	

	
	Route::filter('HTTPS', function()
	{		
	   
	   	//ensure https connection 
	    if (!Request::secure()) {
	    	return Redirect::secure( Request::path('/toSecureURL') );
	    }
	    
	});
	
	
	
	Route::filter('CMSAuth', function()
	{		
	    //ensure user is logged in
		if (Auth::CMSuser()->guest()) {
	        return Redirect::action('CMSController@getLogin');
	    }
	   
	   	//ensure https connection 
	    if (!Request::secure()) {
	    	return Redirect::secure( Request::path('/toSecureURL') );
	    }
	    
	});
	
	
	
	Route::filter('CMSApp', function($route)
	{		
	   	//get appID
		$appId = isset($route) ? $route->getParameter('appId') : null;
	    
	    //invalid app ID
		if (!is_numeric($appId) || $appId<0 || !CMSAccess::validApplication($appId)) {
			return Redirect::action('CMSController@getIndex');
			//return Redirect::to('/cms/error/404');
		}
	    
	});
	
	Route::filter('Ajax', function()
	{		
//	    //ensure user is logged in
//		if (Auth::CMSuser()->guest()) {
//	        return Redirect::to('/cms/login');
//	    }
//	    
//	    //check hashed timestamp??
//	    
	});
	

	//== PERMISSION FILTERS ==//
	
	Route::filter('P_Security', function($route)
	{	
		//get appID
		$appId = isset($route) ? $route->getParameter('appId') : null;
		
		//valid app ID
		if (is_numeric($appId) && $appId>0) {
		
			//ensure user has permission
			if (!CMSAccess::validPermission(CMSAccess::$PERMISSION_EDIT_SECURITY, $appId)) { 
				
				//no security permission - redirect to overview
				return Redirect::action('CMSController@getIndex', array('appId' => $appId));
			}
		
		}
		//invalid app ID
		else {
			return Redirect::action('CMSController@getError', array('errorCode' => '404'));
		}
	    
	});
	
	
	
	Route::filter('P_Form', function($route)
	{	
		//get appID
		$appId = isset($route) ? $route->getParameter('appId') : null;
		
		//valid app ID
		if (is_numeric($appId) && $appId>0) {
		
			//ensure user has permission
			if (!CMSAccess::validPermission(CMSAccess::$PERMISSION_EDIT_FORM, $appId)) { 
				
				//no security permission - redirect to overview
				return Redirect::action('CMSController@getIndex', array('appId' => $appId));
			}
		
		}
		//invalid app ID
		else {
			return Redirect::action('CMSController@getError', array('errorCode' => '404'));
		}
	    
	});
	


	Route::filter('P_Input', function($route)
	{	
		//get appID
		$appId = isset($route) ? $route->getParameter('appId') : null;
		
		//valid app ID
		if (is_numeric($appId) && $appId>0) {
		
			//ensure user has permission
			if (!CMSAccess::validPermission(CMSAccess::$PERMISSION_FORMS, $appId)) { 
				
				//no security permission - redirect to overview
				return Redirect::action('CMSController@getIndex', array('appId' => $appId));
			}
		
		}
		//invalid app ID
		else {
			return Redirect::action('CMSController@getError', array('errorCode' => '404'));
		}
	    
	});




	//==========================================================//
	//====						CMS ROUTING					====//
	//==========================================================//	
	
	
	//Applications
	//Route::get('cms/app/applications', array('before' => 'ajaxAccess', 'uses' => 'ApplicationController@getApplications'));
	//controller routes
	Route::group(array('before' => 'CMSAuth|CMSApp'), function() use (&$basePath) {
		Route::controller($basePath . '/app', 'ApplicationController');
	});
	
	
	
	//Security Groups
	Route::group(array('before' => 'CMSAuth|CMSApp|P_Security'), function() use (&$basePath) {
		Route::controller($basePath . '/{appId}/security', 'SecurityController');
	});
	
	
	
	//Forms
	//Route::get('cms/form/table/{safestr}', array('before' => 'CMSAuth|Ajax', 'uses' => 'FormController@getTable'));
	//Route::get('cms/form/field/{safestr}/{safestr2}', array('before' => 'CMSAuth|Ajax', 'uses' => 'FormController@getField'));
	Route::group(array('before' => 'CMSAuth|CMSApp'), function() use (&$basePath) {
		Route::controller($basePath . '/{appId}/form', 'FormController');
	});
	
	
	
	//Settings
	Route::group(array('before' => 'CMSAuth|CMSApp'), function() use (&$basePath) {
		Route::controller($basePath . '/{appId}/settings', 'SettingsController');
	});
	
	
	
	//CMS Login
	Route::get($basePath . '/login', array('before' => 'HTTPS', 'uses' => 'CMSController@getLogin'));
	Route::post($basePath . '/login', array('before' => 'HTTPS', 'uses' => 'CMSController@postLogin'));
	Route::get($basePath . '/logout', 'CMSController@getLogout');

	
	//CMS Errors
	Route::get($basePath . '/error', 'CMSController@getError');
	Route::get($basePath . '/error/{safestr}', 'CMSController@getError');
	
	//CMS Admin
	Route::get($basePath, array('before' => 'CMSAuth', 'uses' => 'CMSController@getIndex'));
	Route::group(array('before' => 'CMSAuth|CMSApp'), function() use (&$basePath) {
		//Route::get('cms', 'CMSController@getIndex'); //TODO: have landing page when no app specified
		Route::controller($basePath . '/{appId}', 'CMSController');
	});
	
	

	
	//FORM ROUTES
//	Route::get('form/table', array('before' => 'internalAccess', 'uses' => 'FormController@getTable'));
//	Route::get('form/table/{safestr}', array('before' => 'internalAccess', 'uses' => 'FormController@getTable'));
//	Route::get('form/field', array('before' => 'internalAccess', 'uses' => 'FormController@getField'));
//	Route::get('form/field/{safestr}', array('before' => 'internalAccess', 'uses' => 'FormController@getField'));
//	Route::get('form/field/{safestr}/{safestr2}', array('before' => 'internalAccess', 'uses' => 'FormController@getField'));
	



?>
