<?php

	namespace Soup\CMS\Controllers;

	use Soup\CMS\Lib\BaseCMSController;
	use Soup\CMS\Lib\CMSData;
	use Soup\CMS\Models\CMSApp;
	use Soup\CMS\Models\CMSForm;
	use Soup\CMS\Models\CMSFormField;

	use View;
	use Redirect;
	use Response;
	use Illuminate\Support\Facades\DB;

	class FormController extends BaseCMSController {
		

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
		
		

		
		public function getIndex($appId = null) {
			
			return View::make('cms::admin.form.list');
			
		} //end getIndex()
	
	
	
	
		public function getInput($appId, $formId = null) {
		
			//get validated form
			$form = $this->getValidatedForm($appId, $formId);
			
			//valid form
			if ($form) {
			
				//template input
				if ($form->type == CMSData::$FORM_TYPE_TEMPLATE) {
					
					//render view
					return View::make('cms::admin.form.template')->with('form', $form);
					
				}
				//page input
				else {
			
					//get form properties
					$fields = isset($form) ? $form->fields()->where('editable', true)->orderBy('order', 'DESC')->get() : null;
					$fieldValues = isset($form) ? dataForForm(/*$appId,*/ $form->key) : null;

					//render view
					return View::make('cms::admin.form.input')->with([
						'form' => $form,
						'fields' => $fields,
						'fieldValues' => $fieldValues
					]);
						
				}
			
			} //end if (valid form)
			
			//insecure access
			return Redirect::route('cms.error', ['errorCode' => '404']);
			
		} //end getInput()	
	
	
	
	
		public function getTemplateInput($appId, $formId = null, $rowId = null) {
			
			//get validated form
			$form = $this->getValidatedForm($appId, $formId);
			
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
					$fields = isset($form) ? $form->fields()->where('editable', true)->orderBy('order', 'DESC')->get() : null;
					$fieldValues = isset($form) && $filter ? dataForFormData(/*$appId,*/ $form->key, $filter) : null;
			
					//render view
					return View::make('cms::admin.form.input')->with([
						'form' => $form,
						'fields' => $fields,
						'fieldValues' => $fieldValues,
						'filter' => $filter,
						'formURL' => route('cms.form.input', ['appId' => $appId, 'formId' => $formId]),
						'backURL' => route('cms.form.input', ['appId' => $appId, 'formId' => $formId])
					]);
					
				}
			
			} //end if (valid form)
			
			//insecure access
			return Redirect::route('cms.error', ['errorCode' => '404']);
			
		} //end getTemplateInput()	
		
		
			
	
	
		public function postInput($appId, $formId = null) {
		
			//get validated form
			$form = $this->getValidatedForm($appId, $formId);
			
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
					
					
					//indicate if new row should be added
					$addRow = false;

					//template input
					$filter = null;
					if ($form->type == CMSData::$FORM_TYPE_TEMPLATE) {
						
						//get input row
						$filterRow = safeArrayValue('filter_row', $_POST, -1);
						$rowId = intval($filterRow);
						
						//existing row
						if ($rowId>=0) {
							$filter = ['row' => $rowId];	
						}
						
						//new row
						else if ($rowId<0) {
							$addRow = true;
							$filter = ['row' => -1];
						}
						
					}


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
										if ($addRow) {
											
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

									//add new row
									if ($addRow) {
										$result = $connection->insert($updateFields);
									}
									//update table
									else {
										$result = $connection->update($updateFields);
									}
									
									//indicate form was updated (force value as 'update' call returns false if no changes where made)
									$result = true;
									
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
									if ($addRow) {
										
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
	
							//add field to update query
							$updateFields[$fieldName] = $fieldValue;
							
							
						} //end if (valid properties)
						
						
					} //end for()
				
				

					//run final query
					if ($connection) {
						
						//add new row
						if ($addRow) {
							$result = $connection->insert($updateFields);
						}
						//update table
						else {
							$result = $connection->update($updateFields);
						}
						
						//indicate form was updated (force value as 'update' call returns false if no changes where made)
						$result = true;

						//echo "QUERY TO RUN111: " . $connection->toSql() . " - fields: " . print_r($updateFields, true);
								//dd(DB::connection($lastConnectionName)->getQueryLog());
				//dd(DB::getQueryLog());
					} //end if (valid connection)

						

				
				
				} //end if (found fields)


				//valid result
				if ($result) {
					
					return Redirect::route('cms.form.input', array(
						'appId' => $appId,
						'formId' => $formId,
					))->with(
						'message', 'Form saved!'
					);
					
				}

			} //end if (valid form)
			
			
			//insecure access
			return Redirect::route('cms.error', ['errorCode' => '404']);
			
		} //end postInput()
	
	
	
	
	
	
		public function getEdit($appId, $formId = null) {
			
			//validate app
			if ($this->getValidatedApp($appId)) {
			
				//get validated form
				$form = $this->getValidatedForm($appId, $formId);
			
				//render view (new form will be created if one doesn't exist)
				return View::make('cms::admin.form.edit')->with('form', $form);
			
			} //end if (valid app)
			
			//insecure access
			return Redirect::route('cms.error', ['errorCode' => '404']);
			
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
				return Redirect::action('CMSController@getLogin');
				
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
		

		
		public function postExport($appId = null, $formId = null) {
			
			//get validated form
			$form = $this->getValidatedForm($appId, $formId);
			if ($form) {
				
				//get range values
				$start = safeArrayValue('start', $_POST, -1);
				$end = safeArrayValue('end', $_POST, -1);
				
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
				
				
				//valid form
				if ($valid) {
					
					//get form query
					$query = dataFromTemplateQuery($form);
					if ($query) {
						
						//process query results as array
						//$query->setFetchMode(PDO::FETCH_ASSOC);
						
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
			
			
			
			
		public function getTemplates($appId = null, $formId = null) {
			
			//get validated form
			$form = $this->getValidatedForm($appId, $formId);
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
								$whereQuery->orWhere('status', '=', CMSData::$STATUS_DRAFT);
								$whereQuery->orWhere('status', '=', CMSData::$STATUS_PUBLISHED);
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
		//====					SECURITY METHODS				====//
		//==========================================================//	
			
				
			
			
		private function getValidatedForm($appId, $formId) {
			
			$form = null;
			
			//valid application id
			if ($appId>=0) {

				//check application id
				$app = CMSApp::find($appId);
				if ($app) {
					
					//get form
					//$form = CMSForm::find($formId);
					$form = CMSForm::where('id', '=', $formId)->where('application', '=', $appId)->first();
					
				} //end if (valid app)
				
				
			} //end if (valid app id)
			
			return $form;
			
		} //end getValidatedForm()
		
					
	} //end class FormController


?>