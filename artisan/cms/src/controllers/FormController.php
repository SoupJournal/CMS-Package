<?php

	class FormController extends BaseCMSController {
		

		//public function __construct() {
			

		//} //end constructor()
		
		

		
		public function getIndex() {
			
			return View::make('cms::admin.form.list');
			
		} //end getIndex()
	
	
	
	
		public function getCreate() {
			
			return View::make('cms::admin.form.create');
			
		} //end getCreate()	
	
	
	
	
		public function getForms($appId = null) {
			
			//valid app id
			if ($appId>=0) {
			
				//build query
				$query = CMSForm::select(['id', 'name', 'type'])
						->where('status', '=', 1)
						->where('application', '=', $appId);
				
				//get paginated results
				$results = $this->paginateRequestQuery($query, $_GET);
				
				//return paginated query
				return Response::json($results);
			
			} //end if (valid app id)
			
			//no results
			return "";
		
		} //end getForms()
	
	
	
		public function getTable($appId = null, $dbConnection = null) {
			
			return View::make('cms::admin.form.table')->with('dbConnection', $dbConnection);
			
		} //end getTable()
	
	
	
	
		public function getField($appId = null, $dbConnection = null, $dbTable = null) {
			
			$parameters = Array (
				'dbConnection' => $dbConnection,
				'dbTable' => $dbTable
			);
			
			return View::make('cms::admin.form.field')->with($parameters);
			
		} //end getField()
	

			
					
	} //end class FormController


?>