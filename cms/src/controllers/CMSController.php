<?php

	class CMSController extends BaseCMSController {
		

		//public function __construct() {
			

		//} //end constructor()
		
		
		
		
		
		
		//==========================================================//
		//====				AUTHENTICATION METHODS				====//
		//==========================================================//	
		
		
		
		
		public function getIndex($appId = null) {
			
			return View::make('cms::admin.home');
			
		} //end getIndex()
	
	
	
	
		public function getLogin() {
		
			return View::make('cms::admin.login');
			
		} //end getLogin()
	
	
	
	
		public function postLogin() {
		
			$username = Input::get('username');
			$password = Input::get('password');
	
	
			//validate login
			if (Auth::CMSuser()->attempt(Array ('username' => $username, 'password' => $password)))
			{
				//set current application
				//$appID = Session::get(CMSAccess::$SESSION_KEY_APP_ID);
				//if (!isset($appID)) {
					
					//find first user application
					
					
				//}
				
				//find app id
				$appId = null;
				
				//get list of available applications
				$applications = CMSAccess::userApplications();
				if ($applications && count($applications)>0) {
					$appId = $applications[0]->id;
				}
				
				//found application
				if ($appId>=0) {
					//NB. for index path passing appId as parameter is treated as GET parameter instead of named parameter
					return Redirect::secure(URL::action('CMSController@getIndex') . '/' . $appId);  
				}
				//no application available
				else {
					return Redirect::action('CMSController@getIndex');
				}
			}


			//error - redirect to login page with error message
			return Redirect::back()
				->withInput()
				->withErrors('Invalid username/password combination.');
				
				
		} //end postLogin()
	
	
	
	
		public function getLogout() {

			//logout user
			Auth::CMSuser()->logout();
			
			//clear session
			Session::flush();
	
			//redirect to login
			return Redirect::action('CMSController@getLogin');
			
		} //end getLogout()

		
		
		
		public function getError($errorCode = null) {
		
			//compile error message
			$errorTitle = null;
			$errorMessage = null;
			
			switch ($errorCode) {
				
				case 404:
				{
					$errorTitle = "Permission Denied";
					$errorMessage = "You do not have permission to view this page";
				}
				break;
					
			} //end switch (errorCode)
		
		
			//show error view
			return View::make('cms::admin.error')->with(array(
				'errorTitle' => $errorTitle,
				'errorMessage' => $errorMessage
			));
			
			
		} //end getError()
		
		
		
		
		//==========================================================//
		//====					SERVICE METHODS					====//
		//==========================================================//	
			
		
					
	} //end class CMSController


?>