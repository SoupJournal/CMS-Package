<?php

//use \Illuminate\Database\Eloquent

class BaseModel extends Eloquent {
	
	
	
	//==========================================================//
	//====					DATA METHODS					====//
	//==========================================================//	

	
	
	public function listColumns() {
		
		$result = null;
		
		//valid table name
		if ($this->table && strlen($this->table)>0) {
			$result = Schema::getColumnListing($this->table);
		}
		
		return $result;
		
	} //end listColumns()
	
	
	
	
	//non-strict implementation of replace - if a row exists UPDATE is used (instead of DELETE/INSERT eg. REPLACE INTO)
	public static function replace($data, $updateTimestamps = false) {
		
		$result = false;
		
		//table column names used for replace
		$columns = null;
		$values = null;
		
		//valid object
		if ($data) { 
		
			//get PDO (used to make strings SQL safe)
			$pdo = DB::connection()->getPdo(); 
		
			//get model instance
		    $instance = new static();
		    
		
		
			//handle array types
			$multipleRows = false;
			if (is_array($data)) {
				
				//has values
				if (count($data)>0) {
					
					//get data template
					$template = $data[0];
					if ($template) {
					
						
						//array template
					 	if (is_array($template)) {
					 		
							//get column names
					 		$columns = array_keys($template);
					 		
					 		//indicate replacing multiple rows
					 		$multipleRows = true;
					 		
					 	}
					 	//object template
					 	else if (is_object($template) && !is_string($template)) {
					 		
					 		//get column names
							$columns = get_object_vars($template);
					 		
					 		//indicate replacing multiple rows
					 		$multipleRows = true;
					 		
					 	}
					 	//single array insert
					 	else {
					 		
					 		//get column names
					 		$columns = array_keys($data);
					 		
					 		//compile values string
					 		$values = '(' . $instance->arrayValuesString($data, $columns, $pdo) . ')';
					 		
					 	}
					 
					 
					 	//handle multiple rows
					 	if ($multipleRows) {
					 		
					 		$addedValue = false;
					 		foreach ($data as $row) {
					 			
					 			//append separator
								if ($addedValue) {
									$values .= ',(';
								}
								else {
									$values .= '(';
								}
					 			
					 			//array object
					 			if (is_array($row)) {
									$values .= $instance->arrayValuesString($row, $columns, $pdo);
					 			}
					 			//object
					 			else {
									$values .= $instance->objectValuesString($row, $columns, $pdo);					 				
					 			}
					 			
					 			//close row values
					 			$values .= ')';
					 			
					 			//indicate a value was added
								$addedValue = true;
					 			
					 		} //end for()
					 		
					 	} //end if (multiple rows)
					 
					}
					
				} //end if (found values)
				
			}
			
			//object data
			else {
				
				//get column names
				$columns = get_object_vars($data);
				
				//compile values string
				$values = '(' . $instance->objectValuesString($data, $columns, $pdo) . ')';
					 		
			}
		
		
		
			//valid fields
			if ($columns && count($columns)>0) {
				
				//update timestamps
				if ($updateTimestamps) {
					
				    //check if model uses timestamps
				    if($instance->timestamps){
				        $now = \Carbon\Carbon::now();
				        $array['created_at'] = $now;
				        $array['updated_at'] = $now;
				    }
				}
			    
			    
			    //create update string
			    $updateConditions = "";
			    $primaryKey = $instance->getKeyName();
			    $addedValue = false;
			    foreach ($columns as $field) {
			    	
			    	//TODO: quote column names?? (prevent SQL injection)
			    	
			    	//avoid adding primary key
			    	if (strcmp($primaryKey, $field)!=0) {
			    		
						//append separator
						if ($addedValue) {
							$updateConditions .= ',';
						}
			    		
			    		//append condition
			    		$updateConditions .= $field . '=VALUES(' . $field . ')';
			    		
			    		//indicate a value was added
						$addedValue = true;
			    		
			    	}
			    	
			    } //end for()
			 
			 
			    //insert / replace data
//			    $query = "REPLACE INTO " . $instance->table . " (" . implode(',', $columns) . ") values " . $values;
			    $query = "INSERT INTO " . $instance->table . " (" . implode(',', $columns) . ") values " . $values . " ON DUPLICATE KEY UPDATE " . $updateConditions;
//   			    echo "query ::::  " . $query . "<BR><BR>\n\n";
				$queryResult = DB::statement(DB::raw($query));
			    //$queryResult = DB::select(DB::raw($query));
			    
			    //indicate query was succesfull
			    $result = true;
   
		       
			} //end if (valid fields)
		       
		} //end if (valid data)
	       
	       
	    return $result;
	        
	} //end replace()
	
	
		
		/*
	public static function insertIgnore($array){
	    $a = new static();
	    if($a->timestamps){
	        $now = \Carbon\Carbon::now();
	        $array['created_at'] = $now;
	        $array['updated_at'] = $now;
	    }
	    DB::insert('INSERT IGNORE INTO '.$a->table.' ('.implode(',',array_keys($array)).
	        ') values (?'.str_repeat(',?',count($array) - 1).')',array_values($array));
	}	
		*/
	
	
	
	public function foreignKeys() {
		
		$keys = null;
		
		//list all foreign keys associated with model
		if ($this->table && strlen($this->table)>0) {
			//$keys = DB::select(DB::raw("EXEC sp_fkeys " . $this->table));
			//$keys = DB::select(DB::raw("select TABLE_NAME,COLUMN_NAME,CONSTRAINT_NAME, REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME from INFORMATION_SCHEMA.KEY_COLUMN_USAGE where REFERENCED_TABLE_NAME = \"" . $this->table . "\" and referenced_column_name is not NULL"));
			$keys = DB::select(DB::raw("select REFERENCED_TABLE_NAME as 'table', COLUMN_NAME as 'local', REFERENCED_COLUMN_NAME as 'foreign' from INFORMATION_SCHEMA.KEY_COLUMN_USAGE where TABLE_SCHEMA = (SELECT DATABASE()) and TABLE_NAME = \"" . $this->table . "\" and referenced_column_name is not NULL"));
		}
		
		return $keys;
		
	} //end foreignKeys()
	
	
	
	
	//==========================================================//
	//====					UTIL METHODS					====//
	//==========================================================//	

	
	public function arrayValuesString($data, $columns = null, $pdo = null) {
		
		$values = "";
		
		//valid object
		if ($data) {
			
			//get column names (if required)
			if (!$columns) {
				$columns = array_keys($data); 	
			}
			
			//valid columns
			if ($columns) {
			
				//get PDO (used to make strings SQL safe)
				if (!$pdo) {
					$pdo = DB::connection()->getPdo(); 
				}

				//compile values string
				$addedValue = false;
				foreach ($columns as $key) {

					//TODO: compare with output buffer concatenation ( ob_start(); ob_get_contents(); ob_end_clean(); )
	
					//append separator
					if ($addedValue) {
						$values .= ',';
					}
					
					//valid key
					if (array_key_exists($key, $data)) {
						$values .= (is_numeric($data[$key]) ? $data[$key] : (strlen($data[$key])<=0 ? 'DEFAULT' : $pdo->quote($data[$key])));
					}
					//add empty value
					else {
						$values .= 'DEFAULT';	
					}
					
					//indicate a vlue was added
					$addedValue = true;		
					
				} //end for()

			} //end if (found columns)
		
		} //end if (valid object)
		
		return $values;
		
	} //end arrayValuesString()
	
	
	
	
	public function objectValuesString($data, $columns = null, $pdo = null) {
		
		$values = "";
		
		//valid object
		if ($data) {
			
			//get column names (if required)
			if (!$columns) {
				$columns = get_object_vars($data); 	
			}
			
			//valid columns
			if ($columns) {
			
				//get PDO (used to make strings SQL safe)
				if (!$pdo) {
					$pdo = DB::connection()->getPdo(); 
				}
			
				//compile values string
				$addedValue = false;
				foreach ($columns as $key) {
					
					//TODO: compare with output buffer concatenation ( ob_start(); ob_get_contents(); ob_end_clean(); )
					
					//append separator
					if ($addedValue) {
						$values .= ',';
					}
					
					//valid key
					if (property_exists($data, $key)) {
						$values .= (is_numeric($data->$key) ? $data->$key : (strlen($data->$key)<=0 ? 'DEFAULT' : $pdo->quote($data->$key)));
					}
					//add empty value
					else {
						$values .= 'DEFAULT';	
					}
						
					//indicate a vlue was added
					$addedValue = true;
				
				} //end for()
				
			} //end if (found columns)
		
		} //end if (valid object)
		
		return $values;
		
	} //end objectValuesString()
	
	
	    
} //end class BaseModel


?>