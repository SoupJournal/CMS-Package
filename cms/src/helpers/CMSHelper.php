<?php

	use Soup\CMS\Models\CMSForm;


	function dataForForm($appKey, $formKey, $filterOptions = null, $arrayFormat = true) {
		

		//form data
		$data = null;
		
		
		//valid app key
		if (!is_null($appKey) && strlen($appKey)>0) {
			
			//valid form key
			if ($formKey && strlen($formKey)>0) {
			
				//find form
//				$form = CMSForm::where('key', $formKey)->first();
				$form = CMSForm::whereHas('application', function($query) use ($appKey) {
						$query->where('key', $appKey);
					}) 
					->where('key', $formKey)
					->first(); 
				
				//valid form
				if ($form) {
	
					//select form fields (in optimised data order)
					$fields = $form->fields()
									//->orderBy('connection', 'table', 'row')
									->orderBy('connection')
									->orderBy('table')
									->orderBy('row')
									->get(); 
		
					
					//select form fields (in optimised data order)
	//				$fields = CMSFormField::select(['id', 'key', 'connection', 'table', 'field', 'row'])
	//										->where('form', '=', $form->id)
	//										->orderBy('connection', 'table', 'row')
	//										->get();
					
	
					//valid fields
					if ($fields && count($fields)>0) {
						
						
						//compile query
						$connection = null;
						$connectionName = null;
						$lastConnectionName = null;
						$tableName = null;
						$lastTableName = null;
						$fieldName = null;
						$row = null;
						$lastRow = null;
						$selectFields = [];
						$runQuery = false;
						foreach ($fields as $column) {
							
							//get field properties
							$connectionName = $column->connection;
							$tableName = $column->table;
							$fieldName = $column->field;
							$row = $column->row;
							$offset = null;
							$limit = null;
							$key = $column->key;
							$multipleResults = false;
							
							//apply filter options
							if ($filterOptions) {
							
								//has row
								if (array_key_exists('row', $filterOptions)) {
									$row = $filterOptions['row'];
								}
								
								//has limit
								if (array_key_exists('offset', $filterOptions)) {
									$offset = $filterOptions['offset'];
									$multipleResults = true;
								}	
								if (array_key_exists('limit', $filterOptions)) {
									$limit = $filterOptions['limit'];
									$multipleResults = true;
								}
								

								//TODO: override with table value
								
								//TODO: override with field value 
								
							}
							
		//echo "FORM[" . $form->id . "] con[" . $connectionName . "][" . $tableName . "][" . $fieldName . "][" . $row . "]<br>\n";	
			
							//valid properties
							if (strlen($connectionName)>0 && strlen($tableName)>0 && strlen($fieldName)>0) {// && $row!=null) {
								
								//new table or connection
								if (strcmp($tableName, $lastTableName)!=0 || strcmp($connectionName, $lastConnectionName)!=0 || $row!=$lastRow) {
					
						
									//previous query exists
									if ($connection) {
							
										//run last query 
										$runQuery = true;
										
									}
									//no existing query
									else {
					
										try {
					
											//create initial query
											$connection = DB::connection($connectionName);
											if ($connection) {
												
												//user array results
												if ($arrayFormat) {
													$connection->setFetchMode(PDO::FETCH_ASSOC);
													//$connection->setFetchMode(PDO::FETCH_CLASS);
												}
												
												//update query
												$connection = $connection->table($tableName);
												if (isset($row)) {
													$connection->where('id', '=', $row);
												}
												if (isset($offset)) {
													$connection->offset($offset);
												}
												if (isset($limit)) {
													$connection->limit($limit);
												}
											}
										
										}
										//invalid database connection
										catch (Exception $ex) {
											//TODO: log error
										}
										
									}
							
									//select table
									//$connection = DB::connection($connectionName)->table($tableName);
									
									//store new properties
									$lastConnectionName = $connectionName;
									$lastTableName = $tableName;
									$lastRow = $row;
							
								} //end if (new table)
		
								
								if ($runQuery) {
		
									//valid connection
									if ($connection) {
	
										//fetch results for old row
										if ($multipleResults) {
											$result = $connection->select($selectFields)->get();	
										}									
										else {
											$result = $connection->select($selectFields)->first();
										}
										if ($result) {
											
											//ensure data array exists
											if (!$data) {
												$data = array();	
											}
											
											//convert to array
											//if ($arrayFormat) {
											//	$result = $result->toArray();	
											//}
											
											//store results
											if ($arrayFormat) {
												$data = array_merge($data, $result);	
											}
											else {
												$data = (object) array_merge((array)$data, (array)$result);
											}
											
										}
									
									}
	
									//clear fields list
									$selectFields = [];
									
									//recreate connection
									$connection = DB::connection($connectionName);
									if ($connection) {
										
										//user array results
										if ($arrayFormat) {
											$connection->setFetchMode(PDO::FETCH_ASSOC);
										}
										
										//update query
										$connection = $connection->table($tableName);
										if (isset($row)) {
											$connection->where('id', '=', $row);
										}
										if (isset($offset)) {
											$connection->offset($offset);
										}
										if (isset($limit)) {
											$connection->limit($limit);
										}
										
									}
		
									//clear query state
									$runQuery = false;
									
								}
		
									
								//add current field to select query
								array_push($selectFields, $fieldName . ' AS ' . $key);
	
								
								
							} //end if (valid properties)
							
							
						} //end for()
						
	
	
						//run final query
						if ($connection) {

							//fetch results for old row
							if ($multipleResults) {
								$result = $connection->select($selectFields)->get();
							}										
							else {
								$result = $connection->select($selectFields)->first();
							}												
							if ($result) {
								
								//ensure data array exists
								if (!$data) {
									$data = array();	
								}
								
								//store results
								if ($arrayFormat) {
									$data = array_merge($data, $result);	
								}
								else {
									$data = (object) array_merge((array)$data, (array)$result);
								}
								
							} //end if (valid results)
						
						} //end if (valid connection)
						
						
					} //end if (found fields)
				
				
				} //end if (valid form)
			
			} //end if (valid form key)
		
		} //end if (valid app key)

		return $data;
		
	} //end dataForForm()
	
	
	
	function dataFromTemplate($formKey, $arrayFormat = true) {
		
		//valid form key
		if ($formKey && strlen($formKey)>0) {
		
			//find form
			$form = CMSForm::where('key', $formKey)->first(); 
			if ($form) {
				
				//get query
				$query = dataFromTemplateQuery($form);
				
				//TODO: get data
				
			} //end if (valid form)
		
		} //end if (valid form key)		
		
	} //end dataFromTemplate()
	
	
	
	function dataFromTemplateQuery($form) {
		
		//form query
		$query = null;
		
		
		//valid form 
		if ($form) {
		
			//get tables
			$tables = $form->fields()->select(['connection', 'table'])->groupBy('connection', 'table')->get();
			if ($tables) {
				
				//only one table
				if (count($tables)==1) {
					
					//get fields
					$fields = $form->fields()->orderBy('order', 'DESC')->orderBy('id', 'ASC')->lists('field');
					if ($fields && count($fields)>0) {
						
						//get properties
						$connection = $tables[0]->connection;
						$table = $tables[0]->table;
						
						//valid properties
						if ($connection && $table && strlen($connection)>0 && strlen($table)>0) {

							//create query
							$query = DB::connection($connection)->table($table)->select($fields->toArray());
							
						}
							
					} //end if (found fields)
					
				} 
				
				//multiple tables
				else {
					
					//TODO: handle join - probably use another table that list form joins (and the key/column used)
						
				}
				
			} //end if (found table data)
		
		} //end if (valid form)
	
		
		return $query;
		
	} //end dataFromTemplate()

?>