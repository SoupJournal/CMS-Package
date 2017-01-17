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
			   		
?>

    	{{----------------- CONTENT ------------------}}
    	
        		@yield('content', $connection)
        
        {{--------------- END CONTENT ----------------}}

<?php

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