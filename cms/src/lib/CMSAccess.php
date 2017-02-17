<?php

	namespace Soup\CMS\Lib;
	
	use \Auth;
	use Soup\CMS\Models\CMSApp;
	use Soup\CMS\Models\CMSSecurity;
	use Soup\CMS\Models\CMSSecurityPermission;


	class CMSAccess {
		
		//auth guard
		public static $AUTH_GUARD = 'cms';
		
		//CMS permissions
//		public static $PERMISSION_EDIT_APPLICATION = 'edit_application';
//		public static $PERMISSION_EDIT_SECURITY = 'edit_security_group';
//		public static $PERMISSION_EDIT_FORM = 'edit_form';
//		public static $PERMISSION_EDIT_PAGE = 'edit_page';
		
		//Global session keys
		//public static $SESSION_KEY_APP_ID = 'application_id';
		//public static $SESSION_KEY_APP_NAME = 'application_name';
		
		
		//CMS permissions
		private static $PERMISSIONS = Array(
			'create_application',
			'edit_application',
			'edit_security_group',
			'edit_form',
			'edit_page',
			'data_entry',
			'view_app',
			'forms'
		);
			
		//permission ID's
		public static $PERMISSION_CREATE_APPLICATION 	= 0;
		public static $PERMISSION_EDIT_APPLICATION 		= 1;
		public static $PERMISSION_EDIT_SECURITY 		= 2;
		public static $PERMISSION_EDIT_FORM 			= 3;
		public static $PERMISSION_EDIT_PAGE 			= 4;
		public static $PERMISSION_DATA_ENTRY 			= 5;		
		public static $PERMISSION_VIEW_APP 				= 6;
		public static $PERMISSION_FORMS 				= 7;
		
		//form permissions
		public static $PERMISSION_FORMS_INCLUDE			= 'include';
		public static $PERMISSION_FORMS_EXCLUDE			= 'exclude';
		
		
		
		
		/**
		 *	Get the associated permission key from a permission ID
		 *
		 *	@param permissionID	the ID of the permission to check
		 *	@return the string key of the specified permission, null if no key found
		 *
		 **/
		static function permissionKey($permissionID) {
			
			$key = null;
			
			//check permission ID
			if ($permissionID>=0 && $permissionID<count(CMSAccess::$PERMISSIONS)) {
				$key = CMSAccess::$PERMISSIONS[$permissionID];
			}
			
			return $key;
			
		} //end permissionKey()
		
		
		
		
		
		/**
		 *	Checks the provided list of permissions to see if it contains the specified permission
		 *
		 *	@param permissionsList the list of permissions to check 
		 *	@param permissionID	the ID of the permission to check
		 *	@return true if the permission is contained within the provided list, otherwise false
		 *
		 **/
		static function validPermissionFromList($permissionsList, $permissionID) {
			
			$valid = false;
			
			//valid list
			if ($permissionsList && count($permissionsList)>0) { 
			
				//get the key for the permission
				$permissionKey = CMSAccess::permissionKey($permissionID);
				if ($permissionKey && strlen($permissionKey)>0) {
					
					//check permission status
					$valid = (array_key_exists($permissionKey, $permissionsList) && $permissionsList[$permissionKey]);
					
				}
				
			} //end if (valid list)
			
			return $valid;
			
		} //end validPermissionFromList()
		
		
		
		
		
		/**
		 *	Checks is the user has the specified permission
		 *
		 *	@param permissionID	the ID of the permission to check
		 *  @param appId the application used for the check
		 *	@param user optional parameter to specify the user used for the check
		 *	@return true if the user has the correct permission, false otherwise
		 *
		 **/
		static function validPermission($permissionID, $appId, $user=null) {
			
			$valid = false;
			
			//check permission ID
			if ($permissionID>=0 && $permissionID<count(CMSAccess::$PERMISSIONS)) {
			
				//retrieve permission tag
				$permissionTag = CMSAccess::$PERMISSIONS[$permissionID];
				if ($permissionTag && strlen($permissionTag)>0) {
			
					//get application ID
					//$appId = Session::get(CMSAccess::$SESSION_KEY_APP_ID);
					
					//valid app ID
					if (is_numeric($appId) && $appId>=0) {
			
			
						//retrieve user
						if (!$user) {
							$user = Auth::guard(CMSAccess::$AUTH_GUARD)->user();
						}
						
						//valid user
						if (isset($user) && is_numeric($user->id) && $user->id>=0) {
							
							//find user permissions
							$permissions = CMSSecurityPermission::select('id')
									->where('user', '=', $user->id)
									->whereHas('group', function($query) use ($permissionTag, $appId) {
										$query->where('application', '=', $appId)
											  ->where('permission', 'LIKE', '%"' . $permissionTag . '"%');
									})
									->first();
									
							//check user has permission
							$valid = $permissions ? true : false;
							
						} //end if (valid user)
			
			
					} //end if (valid application ID)
			
			
				} //end if (valid permission tag)
			
			} //end if (valid permission ID)
			
			
			return $valid;
			
		} //end validPermission()
		
		
		
		
		
		/**
		 *	Checks is the user has access to the specified application
		 *
		 *  @param appId the application used for the check
		 *	@param user optional parameter to specify the user used for the check
		 *	@return true if the user has access to the application, false otherwise
		 *
		 **/
		static function validApplication($appId, $user=null) {
		
			$valid = false;
		
		
			//valid app ID
			if (is_numeric($appId) && $appId>=0) {
		
				//retrieve user
				if (!$user) {
					$user = Auth::guard(CMSAccess::$AUTH_GUARD)->user();
				}
				
				
				//valid user
				if (isset($user) && is_numeric($user->id) && $user->id>=0) {
					
					$result = CMSSecurityPermission::select('id')
						->where('user', '=', $user->id)
						->whereHas('group', function($query) use ($appId) {
							
							//user is part of application security group
							$query->where('application', '=', $appId);
							
							//at least one permission is specified
							$query->where(function($whereQuery) {
								foreach (CMSAccess::$PERMISSIONS as $permission) {
								 	$whereQuery->orWhere('permission', 'LIKE', '%"' . $permission . '"%');
								}
							});

						})
						->count();
	
					//check user has permission
					$valid = $result>0;
					
				} //end if (valid user)
		
			} //end if (valid application ID)
		
		
			return $valid;
			
		} //end validApplication()
		
		
		
		
		
		
		
		/**
		 *	Returns a list of applications accesible to the user 
		 *
		 *	@param user optional parameter to specify the user used for the check
		 *	@return array containing details of available applications
		 *
		 **/
		static function userApplications($user=null) {
			
			$applications = null;
			
			
			//retrieve user
			if (!$user) {
				$user = Auth::guard(CMSAccess::$AUTH_GUARD)->user();
			}

			
			//valid user
			if (isset($user) && is_numeric($user->id) && $user->id>=0) {
				
				$apps = CMSApp::select(['id', 'name'])
					->whereHas('group', function($query) use ($user) {
						
						//check for matching user
						$query->whereHas('permissions', function($query) use ($user) {
							$query->where('user', '=', $user->id);
						});
						
						//check for any valid permission
						$query->where(function ($whereQuery) {
							foreach (CMSAccess::$PERMISSIONS as $permission) {
								  $whereQuery->orWhere('permission', 'LIKE', '%"' . $permission . '"%');
							}
						});
						
						//select distinct applications
						$query->groupBy('application');
						
					})
					->get();
						
				
				//get list of applications
				if ($apps) {
					$applications = $apps; 
				}
				
			} //end if (valid user)
			
			
			return $applications;
			
		} //end userApplications()
		
		
		
		
		
		/**
		 *	Returns a list of permissions for the specified user and application
		 *
		 * 	@param appId the application used for the check
		 *	@param user optional parameter to specify the user used for the check
		 *	@return array of user permissions
		 *
		 **/
		static function userPermissions($appId, $user=null) {
			
			$permissions = null;
			

			//valid app ID
			if (is_numeric($appId) && $appId>=0) {
			
				//retrieve user
				if (!$user) {
					$user = Auth::guard(CMSAccess::$AUTH_GUARD)->user();
				}
				
				
				//valid user
				if (isset($user) && is_numeric($user->id) && $user->id>=0) {
				
					//select all permissions
					$result = CMSSecurity::select(['id', 'permission'])
						->where('application', '=', $appId)
						->whereHas('permissions', function($query) use ($user) {
							$query->where('user', '=', $user->id);
						})
						->groupBy('permission')
						->get();
	
					//valid results
					if ($result && count($result)>0) {
					
						//create permissions list
						$permissions = array();
						$permissionArray = null;
						foreach($result as $permissionData) {
							
							try {
						
								//decode permission
								$permissionArray = json_decode($permissionData->permission);

								//valid permissions
								if ($permissionArray && count($permissionArray)>0) {
									
									//set permission
									foreach($permissionArray as $permission) {
										$permissions[$permission] = true;
									}
								}
							}
							catch (Exception $ex) {
								//TODO: log error
								//echo "ERROR parsing permission JSON: " . ex;
							}
							
						} //end for()
					
					}
						
				
				} //end if (valid user)
			
			} //end if (valid application ID)
			
			
			return $permissions;
			
		} //end userPermissions()
		
		
		
		
	} //end class CMSAccess



?>