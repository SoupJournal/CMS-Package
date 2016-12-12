<?php

	class ApplicationController extends BaseCMSController {
		

		//public function __construct() {
			

		//} //end constructor()
		
		

		
		public function getIndex() {
			
			return View::make('cms::admin.application.list');
			
		} //end getIndex()
	
	
	
	
		public function getCreate() {
			
			return View::make('cms::admin.application.create');
			
		} //end getCreate()	
	
	
			
					
	} //end class ApplicationController


?>