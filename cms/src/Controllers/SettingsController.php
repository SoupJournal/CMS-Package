<?php

	namespace Soup\CMS\Controllers;

	use Soup\CMS\Lib\BaseCMSController;
	
	
//	use URL;
	use View;
//	use Redirect;
//	use Illuminate\Support\Facades\Auth;
//	use Illuminate\Support\Facades\Input;

	class SettingsController extends BaseCMSController {
		

		//public function __construct() {
			

		//} //end constructor()
		
		/*
		public function index() {
			
			return View::make('cms::admin.settings.list');
			
		} //end index()
		
		*/
		
		public function getIndex() {
			
			return View::make('cms::admin.settings.list');
			
		} //end getIndex()
	
	
	
		
			
			
		//==========================================================//
		//====					SERVICE METHODS					====//
		//==========================================================//	
		
			
		
					
	} //end class SettingsController


?>