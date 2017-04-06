<?php
	
	namespace Soup\CMS\Controllers;

	use Soup\CMS\Lib\BaseCMSController;
	use Soup\CMS\Lib\CMSAccess;
	use Soup\CMS\Models\CMSSecurity;

	use View;
	use Redirect;

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


		
		public function getIndex($appId = null) {
			
			return View::make('cms::admin.security.list');
			
		} //end getIndex()
	
	
	
	
		public function getEdit($appId = null, $securityGroupId = null) {
			
			//get security group
			$group = CMSSecurity::find($securityGroupId);
			
			return View::make('cms::admin.security.edit')->with(array(
				'securityGroup' => $group,
				'availablePermissions' => $this->permissionsList
			));
			
		} //end getEdit()	
	
	
		
		
		public function postEdit($appId = null, $securityGroupId = null) {
			
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
						return Redirect::action('SecurityController@getIndex'); 
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
		
			
			
		public function getGroups($appId = null) {
			
			
			//build query
			$query = CMSSecurity::select(['id', 'name', 'permission'])
					->where('application', '=', $appId)
					->where('status', '=', 1);
			
			//get paginated results
			$results = $this->paginateRequestQuery($query, $_GET);
			
			//return paginated query
			return Response::json($results);
					
			
		} //end getGroups()
		
		
		
		
		
		public function getUsers($appId = null, $securityGroupId = null) {
			
			
			//build query
			$query = CMSUser::select([
						'first_name', 
						'last_name'
					])
					->whereHas('permissions', function($innerQuery) use (&$securityGroupId) {
							$innerQuery->where('security_group', '=', $securityGroupId);
					});
			//$query = CMSSecurityPermission::where('security_group', '=', $securityGroupId);
//			$query = CMSUser::leftjoin()
			
			//TODO: get users associated with permissions
			
			//get paginated results
			$results = $this->paginateRequestQuery($query, $_GET);
			
			//return paginated query
			return Response::json($results);
					
			
		} //end getUsers()
			
					
	} //end class SecurityController


?>