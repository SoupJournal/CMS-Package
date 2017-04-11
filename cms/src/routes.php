<?php


	//==========================================================//
	//====						CONFIG						====//
	//==========================================================//

	//get base path for cms
	$basePath = config('cms.config.route.path');



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
	//====						CMS ROUTING					====//
	//==========================================================//	
	
	//group controllers into namespace
	Route::group(array('namespace' => 'Soup\CMS\Controllers', 'middleware' => 'web'), function() use (&$basePath) {
//	Route::group(array('namespace' => 'Soup\CMS\Controllers'), function() use (&$basePath) {

		
		
		//Applications
		//Route::get('cms/app/applications', array('before' => 'ajaxAccess', 'uses' => 'ApplicationController@getApplications'));
		//controller routes
		Route::group(array('middleware' => ['HTTPS', 'CMSAuth', 'CMSApp']), function() use (&$basePath) {
			
			//determine path
			$path = $basePath . '/app';
			
			//page actions
			Route::get($path, array('as' => 'cms.app.index', 'uses' => 'ApplicationController@getIndex'));
			Route::get($path . '/create', array('as' => 'cms.app.create', 'uses' => 'ApplicationController@getCreate'));
			
			//service actions
			Route::get($path . '/applications', array('as' => 'cms.app.applications', 'uses' => 'ApplicationController@getApplications'));
			Route::post($path . '/applicationid', array('as' => 'cms.app.applicationid', 'uses' => 'ApplicationController@postApplicationid'));
			//Route::resource($basePath . '/app', 'ApplicationController');
		});
		
		
		
		//Security Groups
		Route::group(array('middleware' => ['HTTPS', 'CMSAuth', 'CMSApp', 'P_Security']), function() use (&$basePath) {
			
			//determine path
			$path = $basePath . '/{appId}/security';
			
			//page actions
			Route::get($path, array('as' => 'cms.security.index', 'uses' => 'SecurityController@getIndex'));
			Route::get($path . '/edit', array('as' => 'cms.security.edit', 'uses' => 'SecurityController@getEdit'));
			Route::get($path . '/edit/{id}', array('as' => 'cms.security.edit', 'uses' => 'SecurityController@getEdit'));
			Route::post($path . '/edit', array('as' => 'cms.security.edit', 'uses' => 'SecurityController@postEdit'));
			Route::post($path . '/edit/{id}', array('as' => 'cms.security.edit', 'uses' => 'SecurityController@postEdit'));
			
			//service actions
			Route::get($path . '/groups/{id}', array('as' => 'cms.security.groups', 'uses' => 'SecurityController@getGroups'));
			Route::get($path . '/users/{id}', array('as' => 'cms.security.users', 'uses' => 'SecurityController@getUsers'));
			
			//Route::resource($basePath . '/{appId}/security', 'SecurityController');
		});
		
		
		
		//Forms
		Route::group(array('middleware' => ['HTTPS', 'CMSAuth', 'CMSApp']), function() use (&$basePath) {
			
			//determine path
			$path = $basePath . '/{appId}/form';
			
			//Input permission required
			Route::group(array('middleware' => ['P_Input']), function() use (&$path) {
				
				//page actions
				Route::get($path . '/input/{id}', array('as' => 'cms.form.input', 'uses' => 'FormController@getInput'));
				Route::post($path . '/input/{id}', array('as' => 'cms.form.input', 'uses' => 'FormController@postInput'));
				
				//service actions
				Route::get($path . '/templates/{id}', array('as' => 'cms.form.templates', 'uses' => 'FormController@getTemplates'));
				Route::post($path . '/export/{id}', array('as' => 'cms.form.export', 'uses' => 'FormController@postExport'));
			});
			
			//Form permission required
			Route::group(array('middleware' => ['P_Form']), function() use (&$path) {
			
				//page actions
				Route::get($path, array('as' => 'cms.form.index', 'uses' => 'FormController@getIndex'));
				Route::get($path . '/edit', array('as' => 'cms.form.create', 'uses' => 'FormController@getEdit'));
				Route::get($path . '/edit/{id}', array('as' => 'cms.form.edit', 'uses' => 'FormController@getEdit'));
				Route::post($path . '/edit', array('as' => 'cms.form.create', 'uses' => 'FormController@postEdit'));
				Route::post($path . '/edit/{id}', array('as' => 'cms.form.edit', 'uses' => 'FormController@postEdit'));
				
				//AJAX actions
				Route::get($path . '/table', array('as' => 'cms.form.table', 'uses' => 'FormController@getTable'));
				Route::get($path . '/table/{safestr}', array('as' => 'cms.form.table.id', 'uses' => 'FormController@getTable'));
				Route::get($path . '/field', array('as' => 'cms.form.field', 'uses' => 'FormController@getField'));
				Route::get($path . '/field/{safestr}/{safestr2}', array('as' => 'cms.form.field.id', 'uses' => 'FormController@getField'));
				
				//service actions
				Route::get($path . '/forms', array('as' => 'cms.form.forms', 'uses' => 'FormController@getForms'));
				Route::get($path . '/fields/{id}', array('as' => 'cms.form.fields', 'uses' => 'FormController@getFields'));
			});
			//Route::resource($basePath . '/{appId}/form', 'FormController');
		});
		
		
		
		//Settings
		Route::group(array('middleware' => ['HTTPS', 'CMSAuth', 'CMSApp']), function() use (&$basePath) {
			
			//determine path
			$path = $basePath . '/{appId}/settings';
			
			//page actions
			Route::get($path, array('as' => 'cms.settings.index', 'uses' => 'SettingsController@getIndex'));
			//Route::resource($basePath . '/{appId}/settings', 'SettingsController');
		});
		
		
		
		//CMS Login
		Route::get($basePath . '/login', ['as' => 'cms.login', 'middleware' => ['HTTPS'/*, 'web'*/], 'uses' => 'CMSController@getLogin']);
		Route::post($basePath . '/login', ['as' => 'cms.login', 'middleware' => ['HTTPS'/*, 'web'*/], 'uses' => 'CMSController@postLogin']);
		Route::get($basePath . '/logout', ['as' => 'cms.logout', 'uses' => 'CMSController@getLogout']);
	
		
		//CMS Errors
		Route::get($basePath . '/error', ['as' => 'cms.error', 'uses' => 'CMSController@getError']);
		Route::get($basePath . '/error/{safestr}', ['as' => 'cms.error', 'uses' => 'CMSController@getError']);
		
		//CMS Admin
//		Route::group(array('middleware' => ['HTTPS', 'CMSAuth', 'CMSApp']), function() use (&$basePath) {
//			Route::resource($basePath . '/', 'CMSController');
//		});
		Route::get($basePath, ['as' => 'cms.home', 'middleware' => ['HTTPS', 'CMSAuth'], 'uses' => 'CMSController@getIndex']);
		Route::get($basePath . '/{appId}' , ['as' => 'cms.home', 'middleware' => ['HTTPS', 'CMSAuth', 'CMSApp'], 'uses' => 'CMSController@getIndex']);


/*
		//CMS Login
		Route::get($basePath . '/login', array('before' => 'HTTPS', 'uses' => 'CMSController@getLogin', 'as' => 'login'));

		Route::get($basePath, array('middleware' => ['HTTPS', 'CMSAuth'], 'uses' => 'CMSController@getIndex', 'as' => 'home'));
		Route::group(array('middleware' => ['HTTPS', 'CMSAuth', 'CMSApp']), function() use (&$basePath) {
			Route::controller($basePath . '/', 'CMSController');
		});	
*/		
	
	}); //end namespace group
	
/*
	
	//FORM ROUTES
//	Route::get('form/table', array('before' => 'internalAccess', 'uses' => 'FormController@getTable'));
//	Route::get('form/table/{safestr}', array('before' => 'internalAccess', 'uses' => 'FormController@getTable'));
//	Route::get('form/field', array('before' => 'internalAccess', 'uses' => 'FormController@getField'));
//	Route::get('form/field/{safestr}', array('before' => 'internalAccess', 'uses' => 'FormController@getField'));
//	Route::get('form/field/{safestr}/{safestr2}', array('before' => 'internalAccess', 'uses' => 'FormController@getField'));
	
*/


?>
