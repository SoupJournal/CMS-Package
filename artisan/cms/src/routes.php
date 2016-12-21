<?php

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
	        return Redirect::to('/cms/login');
	    }
	   
	   	//ensure https connection 
	    if (!Request::secure()) {
	    	return Redirect::secure( Request::path('/toSecureURL') );
	    }
	    
	});
	
	
	
	Route::filter('CMSApp', function($route)
	{		
	   	//get appID
		$appID = isset($route) ? $route->getParameter('appId') : null;
	    
	    //invalid app ID
		if (!is_numeric($appID) || $appID<0 || !CMSAccess::validApplication($appID)) {
			return Redirect::to('/cms');
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
		$appID = isset($route) ? $route->getParameter('appId') : null;
		
		//valid app ID
		if (is_numeric($appID) && $appID>0) {
		
			//ensure user has permission
			if (!CMSAccess::validPermission(CMSAccess::$PERMISSION_EDIT_SECURITY, $appID)) { 
				
				//no security permission - redirect to overview
				return Redirect::to('/cms/' . $appID);
			}
		
		}
		//invalid app ID
		else {
			return Redirect::to('/cms/error/404');
		}
	    
	});
	
	
	
	Route::filter('P_Form', function($route)
	{	
		//get appID
		$appID = isset($route) ? $route->getParameter('appId') : null;
		
		//valid app ID
		if (is_numeric($appID) && $appID>0) {
		
			//ensure user has permission
			if (!CMSAccess::validPermission(CMSAccess::$PERMISSION_EDIT_FORM, $appID)) { 
				
				//no security permission - redirect to overview
				return Redirect::to('/cms/' . $appID);
			}
		
		}
		//invalid app ID
		else {
			return Redirect::to('/cms/error/404');
		}
	    
	});
	




	//==========================================================//
	//====						CMS ROUTING					====//
	//==========================================================//	
	
	
	//Applications
	//Route::get('cms/app/applications', array('before' => 'ajaxAccess', 'uses' => 'ApplicationController@getApplications'));
	//controller routes
	Route::group(array('before' => 'CMSAuth'), function() {
		Route::controller('cms/app', 'ApplicationController');
	});
	
	
	
	//Security Groups
	Route::group(array('before' => 'CMSAuth|P_Security'), function() {
		Route::controller('cms/{appId}/security', 'SecurityController');
	});
	
	
	
	//Forms
	//Route::get('cms/form/table/{safestr}', array('before' => 'CMSAuth|Ajax', 'uses' => 'FormController@getTable'));
	//Route::get('cms/form/field/{safestr}/{safestr2}', array('before' => 'CMSAuth|Ajax', 'uses' => 'FormController@getField'));
	Route::group(array('before' => 'CMSAuth|P_Form'), function() {
		Route::controller('cms/{appId}/form', 'FormController');
	});
	
	
	
	//Settings
	Route::group(array('before' => 'CMSAuth'), function() {
		Route::controller('cms/{appId}/settings', 'SettingsController');
	});
	
	
	
	//CMS Login
	Route::get('cms/login', array('before' => 'HTTPS', 'uses' => 'CMSController@getLogin'));
	Route::post('cms/login', array('before' => 'HTTPS', 'uses' => 'CMSController@postLogin'));
	Route::get('cms/logout', 'CMSController@getLogout');

	
	//CMS Errors
	Route::get('cms/error', 'CMSController@getError');
	Route::get('cms/error/{safestr}', 'CMSController@getError');
	
	//CMS Admin
	Route::get('cms', array('before' => 'CMSAuth', 'uses' => 'CMSController@getIndex'));
	Route::group(array('before' => 'CMSAuth|CMSApp'), function() {
		//Route::get('cms', 'CMSController@getIndex'); //TODO: have landing page when no app specified
		Route::controller('cms/{appId}', 'CMSController');
	});
	
	

	
	//FORM ROUTES
//	Route::get('form/table', array('before' => 'internalAccess', 'uses' => 'FormController@getTable'));
//	Route::get('form/table/{safestr}', array('before' => 'internalAccess', 'uses' => 'FormController@getTable'));
//	Route::get('form/field', array('before' => 'internalAccess', 'uses' => 'FormController@getField'));
//	Route::get('form/field/{safestr}', array('before' => 'internalAccess', 'uses' => 'FormController@getField'));
//	Route::get('form/field/{safestr}/{safestr2}', array('before' => 'internalAccess', 'uses' => 'FormController@getField'));
	



?>
