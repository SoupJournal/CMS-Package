<?php

	namespace Soup\CMS\Controllers;

	use Soup\CMS\Lib\BaseCMSController;
	

	class ApplicationController extends BaseCMSController {
		

		//public function __construct() {
			

		//} //end constructor()
		
		

		
		public function getIndex() {
			
			return View::make('cms::admin.application.list');
			
		} //end getIndex()
	
	
	
	
		public function getCreate() {
			
			return View::make('cms::admin.application.create');
			
		} //end getCreate()	
	
	
		
		
			
			
		//==========================================================//
		//====					SERVICE METHODS					====//
		//==========================================================//	
		
			
			
		public function getApplications() {
			
			
			//build query
			$query = CMSApp::select(['id', 'name'])->where('status', '=', 1);
			
			//get paginated results
			$results = $this->paginateRequestQuery($query, $_GET);
			
			//return paginated query
			return Response::json($results);
			
			
		} //end getApplications()
			
			
			
		public function postApplicationid($appID = null) {
			
			
		} //end postApplicationid()
		
		
		
					
	} //end class ApplicationController


?>