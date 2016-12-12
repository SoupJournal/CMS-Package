<?php

function convertObjectToJS($phpObject, $htmlEncode = false) {
	
	$output = "";
	
	//valid object
	if (isset($phpObject)) {
		
		try {
			
			//convert object to JS variable string
			$encoded = json_encode($phpObject);
			if ($encoded && strlen($encoded)>0) {
				
				//html encode
				if ($htmlEncode) {
					$output = htmlspecialchars($encoded);
				}
				
				//set output
				$output = $encoded;

			}
			
		
		}
		catch(Exception $e){
			
		   //TODO: log error
			$output = "";
		   
		}
		
	} //end if (valid object)
	
	return $output;
	
} //end convertObjectToJS()


?>