{{-- @extends('cms::layouts.database') --}}
<?php

	//db connection specified
	if (isset($dbConnection) && strlen($dbConnection)>0) {

		try{
			
		   	//get database name from connection
		   	$databaseName = null;	
			$databaseConnections = Config::get('database.connections');
			if ($databaseConnections && is_array($databaseConnections) && isset($databaseConnections[$dbConnection]) && array_key_exists('database', $databaseConnections[$dbConnection])) {
				$databaseName = $databaseConnections[$dbConnection]['database'];
			}
			
			//valid database name
			if ($databaseName && strlen($databaseName)>0) {
			
				//check if valid connection
			   	$connection = DB::connection($dbConnection); //->getDatabaseName();
			   	if ($connection) {
			   		
			   		
			   		
			   	//echo "connection:: " . $dbConnection . "- databaseName: " . $databaseName; // print_r($connection, true);

			 
			   	
			   		//retrieve tables list
				   	$tables = $connection->select('SHOW TABLES');
				   	//echo "tables: " . print_r($tables, true);
				   	//$tables = $connection->raw("select * from information_schema.tables"); // where table_schema=\"". $databaseName ."\"");
				   	if ($tables && count($tables)>0) {
				   		
				   		
				   		//list of available tables
				   		$tableNames = Array();
				   		
				   		//compile table query name
				   		$tableDBName = "Tables_in_" . strtolower($databaseName);
				   		$tableName = null;
					   	foreach($tables as $table) {
					   		
					   		try {
						    	$tableName = $table->$tableDBName;
						    	if ($tableName && strlen($tableName)>0) {
						    		array_push($tableNames, $tableName);
						    	}
					   		}
							catch(Exception $e){
								//invalid table - skip
							   //echo "ERROR: " . $e->getMessage();
							}
							
						} //end for()
					   	
					   	
					   	//TODO: handle user permissions on tables
					   	
					   	
				   		//echo "tables:: " . print_r($tableNames, true);
				   		
				   		//found table names
				   		if (count($tableNames)>0) {
				   			
				   			//convert to JS variable
				   			$tableNamesJS = convertObjectToJS($tableNames, true);
		   
			?>
			
							{{-- select table view --}}
							<div ng-controller="FormController" ng-init='tables={{ $tableNamesJS }};'> 
			
								<div class="form-group">
								
									<h5>SELECT TABLE:<h5>
									<select data-ng-model="database.table" ng-options="str for str in tables" ng-change="selectTable()">
									</select>
								
									
								</div>
			
							</div>
							{{-- end controller --}}
		
			<?php	   
			
					   	}
				   		//no valid table names found
						else {
							echo "no valid tables found in database";
						}
			
			
				   	}
			   		//no tables found
					else {
						echo "no tables found in database";
					}	
	   
			   	}
		   		//unable to connect
				else {
					echo "invalid connection";
				}
			
		   	}
	   		//no database name specified
			else {
				echo "database name not specified in connection";
			}
	   
		}
		catch(Exception $e){
		   echo "ERROR: " . $e->getMessage();
		}
		
	}
	//no connection set
	else {
		echo "no connection specified";
	}


?>


