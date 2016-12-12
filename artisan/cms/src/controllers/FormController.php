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
	
	
	
	
		public function getTable($dbConnection = null) {
			
			return View::make('cms::admin.form.table')->with('dbConnection', $dbConnection);
			
		} //end getTable()
	
	
	
	
		public function getField($dbConnection = null, $dbTable = null) {
			
			$parameters = Array (
				'dbConnection' => $dbConnection,
				'dbTable' => $dbTable
			);
			
			return View::make('cms::admin.form.field')->with($parameters);
			
		} //end getField()
	

			
					
	} //end class FormController


?>