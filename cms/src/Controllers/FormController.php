<?php

	namespace Soup\CMS\Controllers;

	use Soup\CMS\Lib\BaseCMSController;
	use Soup\CMS\Lib\CMSData;
	use Soup\CMS\Lib\CMSAccess;
	use Soup\CMS\Models\CMSApp;
	use Soup\CMS\Models\CMSForm;
	use Soup\CMS\Models\CMSFormField;
	use Soup\CMS\Models\CMSTrigger;

	use View;
	use Redirect;
	use Response;
	use Illuminate\Support\Facades\DB;

	class FormController extends BaseCMSController {
		
		
		//input actions
		const ACTION_UPDATE = 0;
		const ACTION_DELETE = 1;



		public function __construct() {
//				
//			//list of input actions
//			$inputActions = array ('getInput', 'postInput', 'getTemplates', 'postExport');
//			
//				
//			//add filter (require form edit permission)
//			$this->middleware('P_Form', array(
//				'except' => $inputActions
//			));
//				
//			//add filter (require form input permission)
//			$this->middleware('P_Input', array(
//				'only' => $inputActions
//			));

		} //end constructor()
		
		

		
		public function getIndex($appKey = null) {
			
			return View::make('cms::admin.form.list');
			
		} //end getIndex()
	
	
	
	
		public function getInput($appKey, $formId = null) {
		
			//get validated form
			$form = $this->getValidatedForm($appKey, $formId);
			
			//valid form
			if ($form) {
			
				//template input
				if ($form->type == CMSData::$FORM_TYPE_TEMPLATE) {
		
					//get form triggers
					$triggers = CMSTrigger::where('trigger_form', $form->id)
								->where('status', CMSData::STATUS_PUBLISHED)
								->get();
			
					
					//render view
					return View::make('cms::admin.form.template')->with([
						//'appKey' => $appKey,
						'form' => $form,
						'triggers' => $triggers
					]);
					
				}
				//page input
				else {
			
					//get form properties
					$fields = isset($form) ? $form->fields()->orderBy('order', 'DESC')->get() : null;
					//$fields = isset($form) ? $form->fields()->where('editable', true)->orderBy('order', 'DESC')->get() : null;
					$fieldValues = isset($form) ? dataForForm($appKey, $form->key) : null;

					//render view
					return View::make('cms::admin.form.input')->with([
						'form' => $form,
						'fields' => $fields,
						'fieldValues' => $fieldValues,
						'formURL' => route('cms.form.input', ['appKey' => $appKey, 'formId' => $formId])
					]);
						
				}
			
			} //end if (valid form)
			
			//insecure access
			return Redirect::route('cms.error', ['errorCode' => '404']);
			
		} //end getInput()	
	
	
	
	
		public function getTemplateInput($appKey, $formId = null, $rowId = null) {
			
			//get validated form
			$form = $this->getValidatedForm($appKey, $formId);
			
			//valid form
			if ($form) {
			
				//template input
				if ($form->type == CMSData::$FORM_TYPE_TEMPLATE) {
					
					//create row filter
					$filter = null;
					if (intval($rowId)>=0) {
						$filter = ['row' => $rowId];	
					}
					
					//get form properties
					$fields = isset($form) ? $form->fields()->orderBy('order', 'DESC')->get() : null;
					//$fields = isset($form) ? $form->fields()->where('editable', true)->orderBy('order', 'DESC')->get() : null;
					$fieldValues = isset($form) && $filter ? dataForForm($appKey, $form->key, $filter) : null;
			
					//get form triggers
//					$triggers = CMSTrigger::where('trigger_form', $form->id)->get();
			

					//render view
					return View::make('cms::admin.form.input')->with([
						'form' => $form,
						'fields' => $fields,
						'fieldValues' => $fieldValues,
						'filter' => $filter,
						//'triggers' => $triggers,
						'formURL' => route('cms.form.input', ['appKey' => $appKey, 'formId' => $formId]),
						'backURL' => route('cms.form.input', ['appKey' => $appKey, 'formId' => $formId])
					]);
					
				}
			
			} //end if (valid form)
			
			//insecure access
			return Redirect::route('cms.error', ['errorCode' => '404']);
			
		} //end getTemplateInput()	
		
		
		
		
		
		public function deleteTemplateInput($appKey, $formId = null, $rowId = null) {
		
			//get validated form
			$form = $this->getValidatedForm($appKey, $formId, CMSAccess::PERMISSION_FORM_DELETE);
			
			//valid form
			if ($form) {

			
				//template input
				if ($form->type == CMSData::$FORM_TYPE_TEMPLATE) {
	
					//get form properties
					$key = safeArrayValue('key', $_POST, null);
					$rows = safeArrayValue('rows', $_POST, null);

					//valid properties
					if ($rows && count($rows)>0) {
	
						//create filter
						$filter = Array (
							'key' => 'id',
							'row' => $rows[0] //TODO: support array filters
						);
	
						//delete rows
						$result = $this->processInput($form, self::ACTION_DELETE, $filter);
		
							
						//return to page
						return Redirect::route('cms.form.input', array(
							'appKey' => $appKey,
							'formId' => $formId,
						))->with(
							'message', 'Item deleted!'
						);
					
					} //end if (valid properties)
					
					//handle error
					else {
						$errors = "Invalid form properties";
					}
	
				} //end if (template form)
				
				//handle error
				else {
					$errors = "Invalid form type";
				}
				
				//error occurred
				return Redirect::back()
					->withInput()
					->withErrors($errors);
			
			} //end if (valid form)
			
			//insecure access
			return Redirect::route('cms.error', ['errorCode' => '404']);
			
		} //end deleteTemplateInput()	
	
	
	
	
		public function postInput($appKey, $formId = null) {
		
			//get validated form
			$form = $this->getValidatedForm($appKey, $formId);
		
			//get input row
			$filterRow = safeArrayValue('filter_row', $_POST, -1);
			$rowId = intval($filterRow);
		
			//create filter
			$filter = Array (
				'key' => 'id',
				'row' => $rowId
			);
		
			//process input
			$result = $this->processInput($form, self::ACTION_UPDATE, $filter);
		
			//valid result
			if ($result) {
				
				return Redirect::route('cms.form.input', array(
					'appKey' => $appKey,
					'formId' => $formId,
				))->with(
					'message', 'Form saved!'
				);
				
			}
			
			
			//insecure access
			return Redirect::route('cms.error', ['errorCode' => '404']);
			
		} //end postInput()
	
	
	
	
	
	
		public function getEdit($appKey, $formId = null) {
			
			//validate app
			if ($this->getValidatedApp($appKey)) {
			
				//get validated form
				$form = $this->getValidatedForm($appKey, $formId);
			
				//render view (new form will be created if one doesn't exist)
				return View::make('cms::admin.form.edit')->with('form', $form);
			
			} //end if (valid app)
			
			//insecure access
			return Redirect::route('cms.error', ['errorCode' => '404']);
			
		} //end getEdit()	
	
	
	
	
		public function postEdit($appKey, $formId = null) {
			
			
			//form errors
			$errors = array();
			
			//check application
			$validApplication = true;
			
			//valid application key
			if (!is_null($appKey) && strlen($appKey)>0) {

				//check application id
				$app = CMSApp::where('key', $appKey)->first();
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
								$form->application = $app->id;
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
														$fieldAttached = isset($fieldData['attached']) ? filter_var($fieldData['attached'], FILTER_VALIDATE_BOOLEAN) : false;
														
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
																
															
															//add field
															if ($fieldAttached) {
															
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
															
															}
															
															//remove field
															else if ($field) {
																$field->delete();
															}
															
															
														} //end if (valid field)
														
													
													} //end if (has field data)
													
													
												} //end for (fields)
												
											} //end for(tables)
										
										} //end for(connections)
										
									} //end if (found fields)
								
								
									
									
									//redirect user
									return Redirect::route('cms.form.index', ['appKey' => $appKey])->with('message', 'Form saved!');
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
				return Redirect::route('cms.home');
				
			}
			

			
			//error occurred
			return Redirect::back()
				->withInput()
				->withErrors($errors);
			
		} //end postEdit()	
	
	
	
	
	
		//==========================================================//
		//====					AJAX METHODS					====//
		//==========================================================//	
		
	
	
		public function getTable($appKey = null, $dbConnection = null) {
			
			return View::make('cms::admin.form.table')->with('dbConnection', $dbConnection);
			
		} //end getTable()
	
	
	
	
		public function getField($appKey = null, $dbConnection = null, $dbTable = null) {
			
			$parameters = Array (
				'dbConnection' => $dbConnection,
				'dbTable' => $dbTable
			);
			
			return View::make('cms::admin.form.field')->with($parameters);
			
		} //end getField()
	



		//==========================================================//
		//====					SERVICE METHODS					====//
		//==========================================================//	
		


		public function postTrigger($appKey = null, $triggerId = null) {
		//dd("got trigger");

			//valid trigger id
			if (isset($triggerId)) {
		
				//get trigger
				$trigger = CMSTrigger::find($triggerId);
				if ($trigger) {
			
					//get validated forms
					$triggerForm = $this->getValidatedForm($appKey, $trigger->trigger_form);
					$dataForm = $this->getValidatedForm($appKey, $trigger->data_form);
					if ($triggerForm && $dataForm) {

					
						//get trigger properties
						$triggerProperties = decodeJSON($trigger->properties);

					
						//form validation
						$valid = true;
					
						//template form
						$fieldValues = null;
						if ($dataForm->type == CMSData::$FORM_TYPE_TEMPLATE) {
		
							//get range values
							$start = safeArrayValue('start', $_POST, -1);
							$end = safeArrayValue('end', $_POST, -1);
							$limit = $end - $start;

							//form validation
							$valid = true;
							$errorMessage = null;
							
							//valid range
							if (!($start>=0)) {
								$errorMessage = "Please specify a valid start range";
								$valid = false;
							}
							if ($valid && !$end>0) {
								$errorMessage = "Please specify a valid end range";
								$valid = false;
							}
							else if ($valid && $end<$start) {
								$errorMessage = "Please specify an end range greater than the start range";
								$valid = false;
							}
							//invalid limit
							else if (!$limit>0) {
								$errorMessage = "No rows specified";
								$valid = false;
							}
								

						

						
							//create row filter
//							$filter = null;
							$filter = [
								'offset' => $start,
								'limit' => $limit
							];
//							if ($dataForm->type == CMSData::$FORM_TYPE_TEMPLATE && intval($rowId)>=0) {
//								$filter = ['row' => $rowId];	
//							}
							
							//get form properties
							//$fields = $dataForm->fields()->orderBy('order', 'DESC')->get();
							
							//get form values
							$fieldValues = dataForForm($appKey, $dataForm->key, $filter, false);
						
						}
					
			
						
						//valid form
						if ($valid) {
			
						
							//handle trigger
							switch ($trigger->type) {
								
								case CMSData::TRIGGER_TYPE_INTERNAL:
								{
									//get properties
									$class = safeObjectValue('class', $triggerProperties, null);
									
									//valid class
									if ($class && strlen($class)>0) {
										
										try {
											
											//valid implementation
											if (class_implements($class)) {
											
												//create instance
												$instance = new $class();
												if ($instance) {
	
													//run trigger
													$response = $instance->handleTrigger($fieldValues, null);	
	
													return Redirect::back()
														->withInput()
														->with(
															'message', ($response && is_string($response) ? $response : "")
														);
	
												} //end if (created instance)
											
											} //end if (valid implementation)
											
										}
										catch (Exception $ex) {
											//TODO: log error
										}
										
									} //end if (valid class)
	
								}
								break;
								
								case CMSData::TRIGGER_TYPE_URL:
								{
									//get properties
									$url = safeObjectValue('url', $triggerProperties, null);
									//$params = safeObjectValue('params', $triggerProperties, null);
									$request = safeObjectValue('request', $triggerProperties, null);
										
									//compile parameters
									
									//compile request data values
									if ($request) {
											
										//handle post data
										$post = safeObjectValue('post', $request, null);
										if ($post) {
											
											
										} //end if (found post data)
										
									} 
									
								}
								break;	
								
							} //end switch (email type)
							
							//trigger not handled
							return Redirect::back()
								->withInput()
								->withErrors("Failed to handle trigger");
				
						} //end if (valid form)
				
						//invalid form
						else {
							
							//error occurred
							return Redirect::back()
								->withInput()
								->withErrors($errorMessage);	
								
						}
				/*
						//send email
						$emailJob = new SendEmailJob([
							"recipient" => $user->email, 
							"sender" => [
								'email' => self::EMAIL_SENDER_VERIFY, 
								'name' => 'belif'
							],
							"subject" => self::EMAIL_SUBJECT_VERIFY,
							"view" => "belif::email.verify",
							"view_properties" => [
								'name' => $user->name,
								'address1' => $user->address_1,
								'address2' => $user->address_2,
								'address3' => $address3,
								'pageData' => $pageData,
								'verifyLink' => route('belif.share', ['code' => $user->verify_code]),
								'unsubscribeLink' => route('belif.unsubscribe', ['code' => $user->verify_code])
							]
						]);
						$this->dispatch($emailJob);
				*/
				
					} //end if (valid form)
			
				} //end if (valid email)
			
			} //end if (valid email id)
			
			//insecure access
			return Redirect::route('cms.error', ['errorCode' => '404']);
			
		} //end postTrigger()
		

		
		public function postExport($appKey = null, $formId = null) {
			
			//get validated form
			$form = $this->getValidatedForm($appKey, $formId);
			if ($form) {
				
				//get range values
				$start = safeArrayValue('start', $_POST, -1);
				$end = safeArrayValue('end', $_POST, -1);
				$limit = $end - $start;
				
				//form validation
				$valid = true;
				$errorMessage = null;
				
				//valid range
				if (!($start>=0)) {
					$errorMessage = "Please specify a valid start range";
					$valid = false;
				}
				if ($valid && !$end>0) {
					$errorMessage = "Please specify a valid end range";
					$valid = false;
				}
				else if ($valid && $end<$start) {
					$errorMessage = "Please specify an end range greater than the start range";
					$valid = false;
				}
				//invalid limit
				else if (!$limit>0) {
					$errorMessage = "No rows specified for export";
					$valid = false;
				}
				
				
				//valid form
				if ($valid) {
					
					//get form query
					$query = dataFromTemplateQuery($form);
					if ($query) {
						
						//process query results as array
						//$query->setFetchMode(PDO::FETCH_ASSOC);
						
						//content file name
						$formName = ($form->name && strlen($form->name)>0 ? $form->name . '-' : '');
						$filename = $formName . date('Y-m-d_His') . '.csv';

						//set query limit
						$query = $query->offset($start)->limit($limit);
						
						
						//create stream callback
						$callback = function() use ($query) {
						
							//create handle for CSV conversion
							//$handle = fopen('php://memory', 'w');
							$handle = fopen('php://output', 'w');
						
							//indicate if column headers added
							$addedHeader = false;
						
							//get results
							$query->chunk(500, function($results) use ($handle, $addedHeader) {
		
								//add data
								foreach ($results as $result) {
									
									//add column headers
									if (!$addedHeader) {
										
										//get object properties
										$props = get_object_vars($result);
										if ($props) {
											fputcsv($handle, array_keys($props));
										}
										$addedHeader = true;	
									}
									
									//add columns
									//foreach ($result as $key => $value) {
										fputcsv($handle, get_object_vars($result)); 
									//}
								}
								
							});
							
							//close stream
							fclose($handle);
						
						};
						
						//read handle contents
						//fseek($handle, 0);
  						//$csv = stream_get_contents($handle);
  							
					
						//create content headers
						$headers = [
					        'Content-type'        => 'text/csv',
					        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
					    ];
					    //return \Response::make($csv, 200, $headers);
					    return \Response::stream($callback, 200, $headers);
						
						
						/*
						
						//determine limit
						$limit = $end - $start;
						
						
						//create pagination data
						$pageData = Array (
							'index' => $start,
							'limit' => $limit
						);
						
						//get paginated results
						$results = $this->paginateRequestQuery($query, $pageData);
						if ($results && isset($results->data)) {
						
							//content file name
							$filename = ($form->name && strlen($form->name)>0 ? $form->name . '-' : '') . date('Y-m-d') . '.csv';
						

							//convert results to array data
							$arrayData = json_decode(json_encode($results->data), true);
							if ($arrayData && count($arrayData)>0) {
							
								//get headers
								$csvHeaders = array_keys($arrayData[0]);
							
								//create handle for CSV conversion
								$handle = fopen('php://memory', 'w');
							
								//convert to CSV content
								fputcsv($handle, $csvHeaders); //, ';');
								foreach ($arrayData as $row) {
									fputcsv($handle, $row); 
								}
							
								//read handle contents
								fseek($handle, 0);
	   							$csv = stream_get_contents($handle);
	   							
							
								//create content headers
								$headers = [
							        'Content-type'        => 'text/csv',
							        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
							    ];
							    return \Response::make($csv, 200, $headers);
						    
							} //end if (valid array data)
    
						} //end if (found results)
						
						//no data
						else {
							$errorMessage = "Sorry, no data was found to export";
						}
						
						*/
						
					} //end if (created query)
					
				} //end if (valid form)
				
				
				//invalid form
				return Redirect::back()
							->withInput()
							->withErrors($errorMessage);
				
			} //end if (valid form)
			
			
			//insecure access
			return Redirect::route('cms.error', ['errorCode' => '404']);
			
		} //end postExport()
		


	
		public function getForms($appKey = null) {
			
			//valid app key
			if (!is_null($appKey) && strlen($appKey)>0) {
			
				//build query
				$query = CMSForm::select(['id', 'name', 'type'])
						->whereHas('application', function ($innerQuery) use ($appKey) {
							$innerQuery->where('key', $appKey);
						})
						->where(function($whereQuery) {
							$whereQuery->orWhere('status', '=', CMSData::STATUS_DRAFT);
							$whereQuery->orWhere('status', '=', CMSData::STATUS_PUBLISHED);
						});
				
				//get paginated results
				$results = $this->paginateRequestQuery($query, $_GET);
				
				//return paginated query
				return Response::json($results);
			
			} //end if (valid app id)
			
			//no results
			return "";
		
		} //end getForms()
	
	
	
	
		public function getFields($appKey = null, $formId = null) {
			
			//valid app key
			if (!is_null($appKey) && strlen($appKey)>0) {
			
				//valid form id
				if ($formId>=0) {
			
					//check if form is valid
					$form = CMSForm::find($formId)
									->whereHas('application', function ($query) use ($appKey) {
										$query->where('key', $appKey);
									});
					if ($form) {
			
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
			
			} //end if (valid app key)
			
			//no results
			return "";
		
		} //end getFields()
			
			
			
			
		public function getTemplates($appKey = null, $formId = null) {
			
			//get validated form
			$form = $this->getValidatedForm($appKey, $formId);
			if ($form) {
				
				//get form query
				$query = dataFromTemplateQuery($form);
				if ($query) {
					
					//get paginated results
					$results = $this->paginateRequestQuery($query, $_GET);
					
					//return paginated query
					return Response::json($results);
					
				}
				
				/*
				//get tables
				$tables = $form->fields()->select(['connection', 'table'])->groupBy('connection', 'table')->get();
				if ($tables) {
					
					//only one table
					if (count($tables)==1) {
						
						//get fields
						$fields = $form->fields()->lists('field');
						if ($fields && count($fields)>0) {
							
							//get properties
							$connection = $tables[0]->connection;
							$table = $tables[0]->table;
							
							//valid properties
							if ($connection && $table && strlen($connection)>0 && strlen($table)>0) {

								//create query
								$query = DB::connection($connection)->table($table)->select($fields);
								
								//get paginated results
								$results = $this->paginateRequestQuery($query, $_GET);
								
								//return paginated query
								return Response::json($results);
								
							}
								
						} //end if (found fields)
						
					} 
					
					//multiple tables
					else {
						
						//TODO: handle join - probably use another table that list form joins (and the key/column used)
							
					}
					
				} //end if (found table data)
				*/
				
			} //end if (found form)
			
			/*
			//valid app id
			if ($appId>=0) {
			
				//valid form id
				if ($formId>=0) {
			
					//build query
					$query = CMSForm::select(['id', 'name', 'type'])
							->where('application', '=', $appId)
							->where(function($whereQuery) {
								$whereQuery->orWhere('status', '=', CMSData::STATUS_DRAFT);
								$whereQuery->orWhere('status', '=', CMSData::STATUS_PUBLISHED);
							});
					
					//get paginated results
					$results = $this->paginateRequestQuery($query, $_GET);
					
					//return paginated query
					return Response::json($results);
				
				} //end if (valid form id)
			
			} //end if (valid app id)
			*/
			
			//no results
			return "";
		
		} //end getTemplates()
			
			
			
		
		
			
		//==========================================================//
		//====					DATA METHODS					====//
		//==========================================================//	
			
				
			
		private function processInput($form, $action = self::ACTION_UPDATE, $filter = null) {
			
			//update result
			$result = false;
			
			
			//valid form
			if ($form) {

//DB::enableQueryLog();
				//get form fields (in data order)
//				$fields = CMSFormField::select(['id', 'key', 'connection', 'table', 'field', 'row'])
//										->where('form', '=', $form->id)
//										->orderBy('connection', 'table', 'row')
//										->get();
				
				$fields = $form->fields()
								->orderBy('connection')
								->orderBy('table')
								->orderBy('row')
								->get();
				if ($fields) {


					//TODO: validate form input
					
					
					
					

					//template input
//					$filter = null;
//					if ($form->type == CMSData::$FORM_TYPE_TEMPLATE && $filter) {
//						
//						//get input row
//						$filterKey = safeArrayValue('key', $filter, null);
//						$filterRow = safeArrayValue('row', $filter, -1);
//						$rowId = intval($filterRow);
//						
//						//existing row
//						if ($rowId>=0) {
//							$filter = ['row' => $rowId];	
//						}
//						
//						//new row
//						else if ($rowId<0) {
//							//$rowExists = true;
//							$filter = ['row' => -1];
//						}
//						
//					}


					//compile query
					$connection = null;
					$connectionName = null;
					$lastConnectionName = null;
					$tableName = null;
					$lastTableName = null;
					$fieldName = null;
					$row = null;
					$lastRow = null;
					$updateFields = [];
					$runQuery = false;

					//update post data
					$fieldValue = null;
					foreach ($fields as $field) {	
		
						//get field properties
						$connectionName = $field->connection;
						$tableName = $field->table;
						$fieldName = $field->field;
						$row = $field->row;
						$key = $field->key;
						$properties = $field->properties;
						
						//get field value
						$fieldValue = safeArrayValue($field->key, $_POST, null);
						
						//apply filter options
						if ($filter) {
						
							//has row
							if (array_key_exists('row', $filter)) {
								$row = $filter['row'];
							}	

							//TODO: override with table value
							
							//TODO: override with field value 
							
						}
						
						//indicate if new row exists
						$rowExists = isset($row) && $row>=0;

						//echo "con[" . $connectionName . "]table[" . $tableName . "]field[" . $fieldName . "]row[" . $row . "]<br>\n";
						
						//valid properties
						if (strlen($connectionName)>0 && strlen($tableName)>0 && strlen($fieldName)>0 && !is_null($row)) {
							
		
							//new table or connection
							if (strcmp($tableName, $lastTableName)!=0 || strcmp($connectionName, $lastConnectionName)!=0 || $row!=$lastRow) {

					
								//previous query exists
								if ($connection) {
						
									//run last query 
									$runQuery = true;
									
								}
								//no existing query
								else {
				
									//create initial query
									$connection = DB::connection($connectionName);
									if ($connection) {

										//add new row
										if (!$rowExists) {
											
											//update query
											$connection = $connection->table($tableName);
											
										}
										//existing row
										else {

											//update query
											$connection = $connection->table($tableName)->where('id', '=', $row);
										}
										//DB::connection($connectionName)->enableQueryLog();
										
									}
									
								}
								
								//store new properties
								$lastConnectionName = $connectionName;
								$lastTableName = $tableName;
								$lastRow = $row;
						
							} //end if (new table)
	
							
							if ($runQuery) {

								//valid connection
								if ($connection) {

									//handle row action
									switch ($action) {

										//delete row
										case self::ACTION_DELETE:
										{
											if ($rowExists) {
												$result = $connection->delete();
											}
										}
										break;
										
										
										//update / add row
										default:
										{

											//add new row
											if (!$rowExists) {
												$result = $connection->insert($updateFields);
											}
											//update table
											else {
												$result = $connection->update($updateFields);
												
												//indicate form was updated ('update' call returns false if no changes where made)
												$result = true;
											}
										
										}
										break;
									
									} //end switch (action)
									
									
									
									//check result
									//if (!$result) {
										//TODO: handle update error
									//}

								}
								
								//clear fields list
								$updateFields = [];
								
								//recreate connection
								$connection = DB::connection($connectionName);
								if ($connection) {
									
									
									//add new row
									if (!$rowExists) {
										
										//update query
										$connection = $connection->table($tableName);
										
									}
									//existing row
									else {

										//update query
										$connection = $connection->table($tableName)->where('id', '=', $row);
									}
									
								}
	
								//clear query state
								$runQuery = false;
								
							}
	
	
							//no value specified
							if (is_null($fieldValue)) {
	
								//get field properties
								if (!is_null($properties) && strlen($properties)>0) {
									
									//decode properties
									$fieldProperties = decodeJSON($properties, true);	
									if ($fieldProperties) {
										
										//TODO: handle required fields
										
										//TODO: handle field validation
										
										//get default value
										$fieldValue = safeArrayValue('default', $fieldProperties, $fieldValue);
										
									}
								}
							
							} //end if (null value)
	
							//add field to update query
							$updateFields[$fieldName] = $fieldValue;
							
							
						} //end if (valid properties)
						
						
					} //end for()
				
				

					//run final query
					if ($connection) {
						
						//handle row action
						switch ($action) {

							//delete row
							case self::ACTION_DELETE:
							{
								if ($rowExists) {
									$result = $connection->delete();
								}
							}
							break;
							
							
							//update / add row
							default:
							{
				
								//add new row
								if (!$rowExists) {
									$result = $connection->insert($updateFields);
								}
								//update table
								else {
									$result = $connection->update($updateFields);
									
									//indicate form was updated ('update' call returns false if no changes where made)
									$result = true;
								}
							
							}
							break;
						
						} //end switch (action)

						//echo "QUERY TO RUN111: " . $connection->toSql() . " - fields: " . print_r($updateFields, true);
								//dd(DB::connection($lastConnectionName)->getQueryLog());
				//dd(DB::getQueryLog());
					} //end if (valid connection)

						

				
				
				} //end if (found fields)


			} //end if (valid form)
			
			
			return $result;
			
		} //end processInput()
			
			
			
		private function processTriggerProperties($properties) {
			
			$result = [];
			
			//has properties
			if ($properties) {
				
				//value
				if (is_string($properties) || is_numeric($properties)) {
					$result = $properties;
				}
				
				//array
				else if (is_array($properties)) {
					
					//process array components
					foreach ($properties as $property) {
							
						//process child object
						array_push($result, $this->processTriggerProperties($property));
							
					} //end for()
					
				}
				
				//object
				else {
					
					//get properties
					$objectEncrypt = safeObjectValue('encryption', $properties, null);
					$objectProperties = safeObjectValue('properties', $properties, null);
					
					//has properties
					if ($objectProperties && is_array($objectProperties)) {
						
						//compile final value
						$propertyValue = "";
						foreach ($objectProperties as $value) {
							
							//literal
							if (is_string($value) || is_numeric($value)) {
								$propertyValue += $value;
							}
							//dynamic
							else {
								
								//process value 
								$type = safeObjectValue('type', $value, null);
								switch ($type) {
									
									case CMSData::VALUE_TIME:
									{
										//get time format
										$format = safeObjectValue('format', $value, null);
										$time = "";
										
										//append value
										$propertyValue += $time;
									}
									break;
									
								} //end switch (value type)
								
							}
							
						} //end for()
						
					} //end if (has properties)
					
					//handle encryption
					if ($encryption && strlen($encryption)>0) {
						
						//encrypt result
						
					} //end if (has encryption)
					
					/*
					//has child properties
					$children = safeObjectValue('children', $properties, null);
					if ($children && is_array($children)) {
					
						//process child objects
						$childData = [];
						foreach ($children as $child) {
							array_push($childData, $this->processTriggerProperties($property));
						}
						
						//store children
						array_push($result, $childData);
						
					} //has child properties
					*/
				}
				
			} //end if (has properties)
			
			return ($properties);
			
		} //end processTriggerProperties()
			
			
		//==========================================================//
		//====					SECURITY METHODS				====//
		//==========================================================//	
			
				
			
			
		private function getValidatedForm($appKey, $formId, $permissions = -1) {
			
			$form = null;
			
			//valid application key
			if (!is_null($appKey) && strlen($appKey)>0) {

				//check application id
				//$app = CMSApp::find($appId);
				//if ($app) {
					
					//get form
					//$form = CMSForm::find($formId);
					$form = CMSForm::where('id', '=', $formId)
						->whereHas('application', function ($query) use ($appKey) {
							$query->where('key', $appKey);
						})
						->first();
					
				//} //end if (valid app)
				
				
			} //end if (valid app id)
			
			return $form;
			
		} //end getValidatedForm()
		
					
	} //end class FormController


?>