<?php

	class FormController extends BaseCMSController {
		

		//public function __construct() {
			

		//} //end constructor()
		
		

		
		public function getIndex() {
			
			return View::make('cms::admin.form.list');
			
		} //end getIndex()
	
	
	
	
		public function getEdit($appId, $formId = null) {
			
			//get form properties
			$form = CMSForm::find($formId);
			
			//render view
			return View::make('cms::admin.form.create')->with('form', $form);
			
		} //end getEdit()	
	
	
	
	
		public function postEdit($appId, $formId = null) {
			
			
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
						$key = (isset($_POST['key']) && strlen($_POST['key'])>0) ? trim($_POST['key']) : null;
						$fields = (isset($_POST['field']) && count($_POST['field'])>0) ? $_POST['field'] : null; 
						
						
						//validate form name
						if ($name && strlen($name)>0 && isSQLSafeString($name)) {
							$valid = true;
						}
						else {
							array_push($errors, 'Please specify a valid form name');
						}
						
						
						//validate form key
						if ($key && strlen($key)>0 && isSQLSafeString($key)) {
							$valid &= true;
						}
						else {
							array_push($errors, 'Please specify a valid form key');
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
								$form->key = $key;

								//save form
								if ($form->save()) {
									
									
									//save form fields
									if ($fields && is_array($fields)) {
										
										//iterrate through connection
										foreach ($fields as $fieldConnection => $fieldTables) {
										
											//iterrate through tables
											foreach ($fieldTables as $fieldTable => $fieldsList) {
												
												//iterrate through fields
												foreach ($fieldsList as $fieldData) {
													
													//valid data
													if ($fieldData && is_array($fieldData)) {
						
						//echo "fieldData[" . $fieldTable . "]: " .print_r($fieldData, true);
						//exit(0);
						
														//get field data values
														$fieldId = (isset($fieldData['id']) && strlen($fieldData['id'])>0) ? trim($fieldData['id']) : null;
														$fieldKey = (isset($fieldData['key']) && strlen($fieldData['key'])>0) ? trim($fieldData['key']) : null;
														
														//valid field
														if ($fieldId && $fieldKey && strlen($fieldId)>0 && strlen($fieldKey)>0) {
															
															//TODO: validate field Key (JSON / javascript safe name??)
															//TODO: validate connection, table & field??
															
															//TODO: handle unique constraint violation
															
															//find existing field
															$field = CMSFormField
																::where('form', $form->id)
																->where('connection', $fieldConnection)
																->where('table', $fieldTable)
																->where('field', $fieldId)
																->first();
																
															
															//create field
															if (!$field) {
																$field = new CMSFormField();
																$field->form = $form->id;
																$field->connection = $fieldConnection;
																$field->table = $fieldTable;
																$field->field = $fieldId;
															}
															
															//valid field
															if ($field) {
																
																//update properties
																$field->key = $fieldKey;	
																
																//store field
																$field->save();
																
															}
															
														} //end if (valid field)
														
													
													} //end if (has field data)
													
													
												} //end for (fields)
												
											} //end for(tables)
										
										} //end for(connections)
										
									} //end if (found fields)
								
								
									
									
									//redirect user
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
			
		} //end postEdit()	
	
	
	
	
	
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
	
	
	
	
		public function getFields($appId = null, $formId = null) {
			
			//valid app id
			if ($appId>=0) {
			
				//valid form id
				if ($formId>=0) {
			
					//check if form is valid
					$form = CMSForm::find($formId);
					if ($form && $form->application==$appId) {
			
						//build query
						$query = CMSFormField::select(['id', 'key', 'connection', 'table', 'field', 'row'])
								->where('form', '=', $formId);
								//->where('application', '=', $appId)
								//->with('fields');
	//							->whereHas('fields', function($fieldQuery){
	//								$fieldQuery->select(['id', 'name']);
	//							});
						
						//get paginated results
						$results = $this->paginateRequestQuery($query, $_GET);
						
						//return paginated query
						return Response::json($results);
					
					} //end if (valid form)
				
				} //end if (valid form id)
			
			} //end if (valid app id)
			
			//no results
			return "";
		
		} //end getFields()
			
					
	} //end class FormController


?>