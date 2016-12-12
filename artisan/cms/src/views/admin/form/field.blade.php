<?php

	//db connection specified
	if (isset($dbConnection) && strlen($dbConnection)>0) {

		//db table specified
		if (isset($dbTable) && strlen($dbTable)>0) {
	
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
				   	$connection = DB::connection($dbConnection); 
				   	//$connection = Schema::connection($dbConnection); 
				   	if ($connection) {
	
				   		//get table data
				   		$tableData = null;
				   		$schema = $connection->getDoctrineSchemaManager();
				   		if ($schema) {
				   			$tableData = $schema->listTableColumns($dbTable);
				   		}
				   		
//				   		$schema = $connection->getSchemaBuilder();
//				   		if ($schema) {
//				   			$tableData = $schema->getColumnListing($dbTable);
//				   		}
			   		
//			   		$connection->getDoctrineSchemaManager();
			   		//$tableData = $connection->getPdo()->query('SELECT * FROM '.$dbTable)->getColumnMeta(0);
			   		//$tableData = $connection->select(DB::raw('SHOW COLUMNS FROM '.$dbTable));
	
						//$tableData = $connection->table('columns')->where('table_schema', $databaseName)->where('table_name',$dbTable)->get();
	
	
						//get table fields
						//$tableData = $connection->getSchemaBuilder()->getColumnListing($dbTable);
						
						//$tableData = $connection->getPdo();
						//echo "table data: " . print_r($tableData, true);
						if ($tableData && count($tableData)>0) {
						
?>
					
							{{-- select table view --}}
							<div ng-controller="FormController" ng-init=''> 
						
								<h5>SELECT FIELDS:<h5>
								<div class="table-responsive">
								
								  <table class="table table-hover">
								    
								    	<thead>
								    		<tr>
								    			<th></th>
								    			<th>Field ID</th>
								    			<th>Display Name</th>
								    			<th>Caption</th>
								    			<th>Default</th>
								    			<th>Required</th>
								    		</tr>
								    	</thead>
								    	
								    <?php
								    	//create fields
								    	foreach ($tableData as $field) {
								    	//foreach ($tableData->getColumns() as $field) {
								    ?>
								    
									    	<tr>
										    	<td><input type="checkbox" data-ng-model="database.field" ng-change="selectField()"></td>
										    	<td>{{ $field->getName() }}</td>
										    	<td><input type="text"></td>
										    	<td><input type="text"></td>
										    	<td><database-input type="{{ $field->getType() }}"></database-input></td>
										    	<td><input type="checkbox"></td>
									    	</tr>
								    	
								    <?php
								    	} //end for()
								    ?>
								    
								  </table>
								  
								</div>
						
						
							</div>
							{{-- end controller --}}
							
							
							{{-- save button --}}
							<div class="form-group">
								<save-button></save-button>
							</div>
				
<?php

					   	}
				   		//no fields found
						else {
							echo "no fields found";
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
		//no table set
		else {
			echo "no table specified";
		}
	
	}
	//no connection set
	else {
		echo "no connection specified";
	}



?>

