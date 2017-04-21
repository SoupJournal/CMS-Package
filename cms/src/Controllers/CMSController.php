<?php 
	
	namespace Soup\CMS\Controllers;

	use Soup\CMS\Lib\BaseCMSController;
	use Soup\CMS\Lib\CMSAccess;
	
	use URL;
	use View;
	use Redirect;
	use Session;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Input;

	class CMSController extends BaseCMSController {
		

		//public function __construct() {
			

		//} //end constructor()
		
		
		
		
		
		
		//==========================================================//
		//====				AUTHENTICATION METHODS				====//
		//==========================================================//	
		
//		
//		public function index($appId = null) {
//
//			return View::make('cms::admin.home');
//			
//		} //end index()
		
		
		
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
			if (Auth::guard(CMSAccess::$AUTH_GUARD)->attempt(Array ('username' => $username, 'password' => $password)))
			{

				//get user
				$user = Auth::guard(CMSAccess::$AUTH_GUARD)->user();
				
				//find app id
				$appId = null;
				
				//get list of available applications
				$applications = CMSAccess::userApplications();
				if ($applications && count($applications)>0) {
					
					//check if valid default
					foreach ($applications as $application) {
						
						//valid app
						if ($application->id==$user->default_application) {
							$appId = $user->default_application;
							break;
						}
					}
					
					//no default set
					if (is_null($appId)) {
						$appId = $applications[0]->id;
					}
					
				}
				
				//found application
				if ($appId>=0) {

					//show app home page
					return Redirect::route('cms.home', array ('appId' => $appId));
				}
				//no application available
				else {

					return Redirect::route('cms.home'); 

				}
			}

			//error - redirect to login page with error message
			return Redirect::back()
				->withInput()
				->withErrors('Invalid username/password combination.');
				
				
		} //end postLogin()
	
	
	
	
		public function getLogout() {

			//logout user
			Auth::guard(CMSAccess::$AUTH_GUARD)->logout();
			
			//clear session
			Session::flush();
	
			//redirect to login
			return Redirect::route('cms.login');
			
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