<?php


	use Soup\CMS\Models\CMSApp;
	use Soup\CMS\Models\CMSForm;
	use Soup\CMS\Lib\CMSAccess;

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
		$appKey = null;
		$appName = null;
		
		//process route variables
	    if ($currentRoute) {
	    	$appKey = $currentRoute->getParameter('appKey');
	    	
	    	//valid app key
	    	if (!is_null($appKey) && strlen($appKey)>0) {
	    		
	    		//get application data
	    		$appData = CMSApp::select('id', 'name')->where('key', $appKey)->first();
	    		if ($appData) {
	    			$appId = $appData->id;
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
	    //$view->with('user', Auth::CMSuser()->user());
	    $view->with('user', Auth::guard(CMSAccess::$AUTH_GUARD)->user());
//echo "SET UUESSSER: ";
// print_r(Auth::guard(CMSAccess::$AUTH_GUARD));
 
 		//make list of applications available
		$view->with('applications', CMSAccess::userApplications());
 
    	//make app id available
    	$view->with('appId', $appId);
    	
    	//make app key available
    	$view->with('appKey', $appKey);
    	
    	//make app name available
    	$view->with('appName', $appName);
	    
	    //make user permissions available
    	$view->with('userPermissions', CMSAccess::userPermissions($appId));

	    //make form data available - TODO: handle permissions - allow access only to specific forms
	    $forms = CMSForm::where('application', '=', $appId)->get();
	    $view->with('forms', $forms);

	    
	    //set scripts asset path
	    $view->with('assetPath', URL::asset('soup/cms/'));

	    //set controllers namespace
	    $view->with('controllerNamespace', 'Soup\\CMS\\Controllers\\');
	    
	});
	
	
	
//	View::composer($cmsGroup, function($view)
//	{
//		
//	});


?>