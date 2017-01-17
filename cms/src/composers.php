<?php


	//composer groups
	$adminGroup = ['cms::admin.*'];
	//$cmsGroup = ['cms::layouts.master', 'cms:page', 'cms::admin.*'];



	//==========================================================//
	//====					CMS COMPOSERS					====//
	//==========================================================//	



	View::composer($adminGroup, function($view)
	{
		//get route 
		$currentRoute = Route::current();
		
		//define parameters
		$appId = null;
		$appName = null;
		
		//process route variables
	    if ($currentRoute) {
	    	$appId = $currentRoute->getParameter('appId');
	    	
	    	//valid app id
	    	if ($appId>=0) {
	    		
	    		//get application data
	    		$appData = CMSApp::select('name')->where('id', '=', $appId)->first();
	    		if ($appData) {
	    			$appName = $appData->name;
	    		}
	    		
	    	}
	    }
		
		
		
		//add user permissions information
		//$permissions = null;
	    //$view->with('userPermissions', $permissions);
		
		
		//add support for dynamic angular modules
	    $view->with('pageModules', Array()); // (isset($pageModules) ? $pageModules : Array()));
	    

	    //make user data available - TODO: check exposed properties
	    $view->with('user', Auth::CMSuser()->user());

    	//make app id available
    	$view->with('appId', $appId);
    	
    	//make app name available
    	$view->with('appName', $appName);
	    
	    //make user permissions available
    	$view->with('userPermissions', CMSAccess::userPermissions($appId));
	    
	    //set scripts asset path
	    $view->with('assetPath', URL::asset('packages/soup/cms/'));
	    
	});
	
	
	
//	View::composer($cmsGroup, function($view)
//	{
//		
//	});


?>