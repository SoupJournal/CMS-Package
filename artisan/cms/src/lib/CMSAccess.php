<?php


	class CMSAccess {
		
		
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
			'edit_application',
			'edit_security_group',
			'edit_form',
			'edit_page',
			'data_entry',
			'view_app'
		);
			
		//permission ID's
		public static $PERMISSION_EDIT_APPLICATION 	= 0;
		public static $PERMISSION_EDIT_SECURITY 	= 1;
		public static $PERMISSION_EDIT_FORM 		= 2;
		public static $PERMISSION_EDIT_PAGE 		= 3;
		public static $PERMISSION_DATA_ENTRY 		= 4;		
		public static $PERMISSION_VIEW_APP 			= 5;
		
		
		
		
		/**
		 *	Get the associated permission key from a permission ID
		 *
		 *	@param permissionID	the ID of the permission to check
		 *	@return the associated query key or null if no matching key is found
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
		 *	Checks is the user has the specified permission
		 *
		 *	@param permissionID	the ID of the permission to check
		 *  @param appID the application used for the check
		 *	@param user optional parameter to specify the user used for the check
		 *	@return true if the user has the correct permission, false otherwise
		 *
		 **/
		static function validPermission($permissionID, $appID, $user=null) {
			
			$valid = false;
			
			//check permission ID
			if ($permissionID>=0 && $permissionID<count(CMSAccess::$PERMISSIONS)) {
			
				//retrieve permission tag
				$permissionTag = CMSAccess::$PERMISSIONS[$permissionID];
				if ($permissionTag && strlen($permissionTag)>0) {
			
					//get application ID
					//$appID = Session::get(CMSAccess::$SESSION_KEY_APP_ID);
					
					//valid app ID
					if (is_numeric($appID) && $appID>=0) {
			
			
						//retrieve user
						if (!$user) {
							$user = Auth::CMSuser()->user();
						}
						
						//valid user
						if (isset($user) && is_numeric($user->id) && $user->id>=0) {
							
							//find user permissions
							$permissions = CMSSecurityPermission::select('id')
									->where('user', '=', $user->id)
									->whereHas('group', function($query) use ($permissionTag, $appID) {
										$query->where('application', '=', $appID)
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
		 *  @param appID the application used for the check
		 *	@param user optional parameter to specify the user used for the check
		 *	@return true if the user has access to the application, false otherwise
		 *
		 **/
		static function validApplication($appID, $user=null) {
		
			$valid = false;
		
		
			//valid app ID
			if (is_numeric($appID) && $appID>=0) {
		
				//retrieve user
				if (!$user) {
					$user = Auth::CMSuser()->user();
				}
				
				
				//valid user
				if (isset($user) && is_numeric($user->id) && $user->id>=0) {
					
					$result = CMSSecurityPermission::select('id')
						->where('user', '=', $user->id)
						->whereHas('group', function($query) use ($appID) {
							
							//user is part of application security group
							$query->where('application', '=', $appID);
							
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
				$user = Auth::CMSuser()->user();
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
		 * 	@param appID the application used for the check
		 *	@param user optional parameter to specify the user used for the check
		 *	@return array of user permissions
		 *
		 **/
		static function userPermissions($appID, $user=null) {
			
			$permissions = null;
			
			
			//valid app ID
			if (is_numeric($appID) && $appID>=0) {
			
				//retrieve user
				if (!$user) {
					$user = Auth::CMSuser()->user();
				}
				
				
				//valid user
				if (isset($user) && is_numeric($user->id) && $user->id>=0) {
				
					//select all permissions
					$result = CMSSecurity::select(['id', 'permission'])
						->where('application', '=', $appID)
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