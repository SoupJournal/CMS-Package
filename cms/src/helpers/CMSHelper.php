<?php


	function dataForForm($formKey, $arrayFormat = true) {
		

		//form data
		$data = null;
		
		
		//valid form key
		if ($formKey && strlen($formKey)>0) {
		
			//find form
			$form = CMSForm::where('key', $formKey)->first(); 
			
			//valid form
			if ($form) {
				
				//select form fields (in optimised data order)
				$fields = $form->fields()->orderBy('connection', 'table', 'row')->get(); 
	
				
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
						$key = $column->key;
	//echo "con[" . $connectionName . "][" . $tableName . "][" . $fieldName . "][" . $row . "]<br>\n";	
		
						//valid properties
						if (strlen($connectionName)>0 && strlen($tableName)>0 && strlen($fieldName)>0 && $column->row!=null) {
							
		
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
										
										//user array results
										if ($arrayFormat) {
											$connection->setFetchMode(PDO::FETCH_ASSOC);
											//$connection->setFetchMode(PDO::FETCH_CLASS);
										}
										
										//update query
										$connection = $connection->table($tableName)->where('id', '=', $row);
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
									$result = $connection->select($selectFields)->first();
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
									$connection = $connection->table($tableName)->where('id', '=', $row);
								}
	
								//clear query state
								$runQuery = false;
								
							}
	
							//add fields to current query						
							else {
								
								//add fields to select query
								array_push($selectFields, $fieldName . ' AS ' . $key);
							
							}
							
							
						} //end if (valid properties)
						
						
					} //end for()
					
		
					//run final query
					if ($connection) {
					
						//fetch results for old row
						$result = $connection->select($selectFields)->first(); 												if ($result) {
							
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
		
		
		return $data;
		
	} //end dataForForm()

?>