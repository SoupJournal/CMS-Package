<?php

	class SecurityController extends BaseCMSController {
		




		//list of all permissions
		private $permissionsList = null;



		public function __construct() {
			
			//build permissions list
			$this->permissionsList = Array (
				CMSAccess::$PERMISSION_EDIT_APPLICATION => 'Edit applications',
				CMSAccess::$PERMISSION_EDIT_SECURITY 	=> 'Edit security groups',
				CMSAccess::$PERMISSION_EDIT_FORM 		=> 'Edit forms',
				CMSAccess::$PERMISSION_EDIT_PAGE		=> 'Edit pages'		
			);

		} //end constructor()


		
		public function getIndex() {
			
			return View::make('cms::admin.security.list');
			
		} //end getIndex()
	
	
	
	
		public function getEdit($securityGroupID = null) {
			
			return View::make('cms::admin.security.edit')->with(array(
				'securityGroupID' => $securityGroupID,
				'availablePermissions' => $this->permissionsList
			));
			
		} //end getEdit()	
	
	
		
		
		public function postEdit($securityGroupID = null) {
			
			//form errors
			$errors = array();
			
			//validate form
			if (isset($_POST)) {
				
				//form validity
				$valid = false;
				
				//get form values
				$name = (isset($_POST['name']) && strlen($_POST['name'])>0) ? trim($_POST['name']) : null;
				
				
				//validate form
				if ($name && strlen($name)>0 && isSQLSafeString($name)) {
					$valid = true;
				}
				else {
					array_push($errors, 'Please specify a valid group name');
				}
				
				
				//valid form
				if ($valid) {
					
					//TODO: handle update existing group
					
					//store group
					$group = new CMSSecurity();
					
					//set name
					$group->name = $name;
					
					
					//set permissions
					//$this->permissionsList
					array_push($errors, 'test: ' . $group->permission);
					
					//if ($group->save()) {
						return Redirect::secure('/cms/security');
					//}
				}
				
			}
			
			//error occurred
			return Redirect::back()
				->withInput()
				->withErrors($errors);

			
		} //end postEdit()	
		
			
			
		//==========================================================//
		//====					SERVICE METHODS					====//
		//==========================================================//	
		
			
			
		public function getGroups() {
			
			
			//build query
			$query = CMSSecurity::select(['id', 'name', 'permission'])->where('status', '=', 1);
			
			//get paginated results
			$results = $this->paginateRequestQuery($query, $_GET);
			
			//return paginated query
			return Response::json($results);
					
			
		} //end getGroups()
			
					
	} //end class SecurityController


?>