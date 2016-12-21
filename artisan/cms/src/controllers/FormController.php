<?php

	class FormController extends BaseCMSController {
		

		//public function __construct() {
			

		//} //end constructor()
		
		

		
		public function getIndex() {
			
			return View::make('cms::admin.form.list');
			
		} //end getIndex()
	
	
	
	
		public function getCreate($appId, $formId = null) {
			
			return View::make('cms::admin.form.create')->with('formId', $formId);
			
		} //end getCreate()	
	
	
	
	
		public function postCreate($appId, $formId = null) {
			
			
			//form errors
			$errors = array();
			
			//check application
			$validApplication = true;
			
			//valid application id
			if ($appId>=0) {

				//check application id
				$app = CMSApp::find($appId);
				if ($app) {
	
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
							array_push($errors, 'Please specify a valid form name');
						}
						
	
						//valid form
						if ($valid) {
							
							$form = null;
							
							//find existing form
							if (isset($formId) && $formId>=0) {
								$form = CMSForm::find($formId);
							}
							//new form
							else {
								$form = new CMSForm();
								$form->application = $appId;
							}
			
							//valid form model
							if ($form) {
							
								//set attributes
								$form->name = $name;
	
								//TODO: set form fields
								
								
								//save form
								if ($form->save()) {
									return Redirect::to('/cms/' . $appId . '/form')->with('message', 'Form saved!');
								}
								//error saving
								else {
									array_push($errors, 'Error saving form');
								}
								
							
							} //end if (valid form model)
							
							//invalid form
							else {
								array_push($errors, 'Invalid form id: ' . $formId);
							}
								
							
						} //end if (form passed validation)
						
						
					} //end if (valid post data)
					
					//no post data
					else {
						array_push($errors, 'No form data found');
					}
					
				} //end if (valid application)
				
				//invalid application
				else {
					$validApplication = false;	
				}
				
			
			} //end if (valid application id)
			
			//invalid application id
			else {
				$validApplication = false;	
			}
			
			
			//invalid application
			if (!$validApplication) {
				
				//redirect to home page (filters will handle authentication)
				Redirect::to('/cms');
				
			}
			

			
			//error occurred
			return Redirect::back()
				->withInput()
				->withErrors($errors);
			
		} //end postCreate()	
	
	
	
	
	
		//==========================================================//
		//====					AJAX METHODS					====//
		//==========================================================//	
		
	
	
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
	



		//==========================================================//
		//====					SERVICE METHODS					====//
		//==========================================================//	
		


	
		public function getForms($appId = null) {
			
			//valid app id
			if ($appId>=0) {
			
				//build query
				$query = CMSForm::select(['id', 'name', 'type'])
						->where('application', '=', $appId)
						->where(function($whereQuery) {
							$whereQuery->orWhere('status', '=', CMSData::$STATUS_DRAFT);
							$whereQuery->orWhere('status', '=', CMSData::$STATUS_PUBLISHED);
						});
				
				//get paginated results
				$results = $this->paginateRequestQuery($query, $_GET);
				
				//return paginated query
				return Response::json($results);
			
			} //end if (valid app id)
			
			//no results
			return "";
		
		} //end getForms()
	
	
	
	
		public function getFields($appId = null, $formID = null) {
		/*	
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
		*/	
			//no results
			return "";
		
		} //end getFields()
			
					
	} //end class FormController


?>