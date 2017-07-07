<?php

	
	
	
	function formSelect($name, $options, $selected, $attributes) {
		
		//open select
		$result = "<select name=\"" . $name . '" ' . arrayToHTMLAttributes($attributes) . ">\n";
		
		//draw options - TODO: handle option groups
		foreach ($options as $optionValue => $option) {
			
			//complex option
			if (is_array($option)) {
				
				//get properties
				$name = safeArrayValue('name', $option, "");
				//$value = safeArrayValue('value', $option, "");
				$attributes = safeArrayValue('attributes', $option, null);
				$result .= "<option value=\"" . $optionValue . "\" " . arrayToHTMLAttributes($attributes) . ">" . $name . "</option>\n";
			}
			//draw option
			else {
				$result .= "<option value=\"" . $optionValue . "\">" . $option . "</option>\n";
			}
			
		} //end for()
		
		//close select
		$result .= "</select>\n";
		
		return $result;
		
	} //end formSelect()
	
	
	
	
	function arrayToHTMLAttributes($attributes) {
		
		$result = "";
		
		//valid array
		if ($attributes && is_array($attributes)) {
			
			//add attributes
			$firstAttr = true;
			foreach ($attributes as $name => $value) {
				
				//valid attribute name
				if ($name && strlen($name)>0) {
				
					//append attribute key
					$result .= ($firstAttr ? '' : ' ') . $name;
				
					//valid value
					if ($value && strlen($value)>0) {
						$result .= '="' . $value . '"';
					}
				
					//indicate attribute was added
					$firstAttr = false;
				
				} //end if (valid attribute)
				
			} //end for()
			
		} //end if (valid array)
		
		return $result;
			
	} //end arrayToHTMLAttributes()
	
	
	
	
	/*
	function createTempFileDownloadResponse($fileName, $name = null, array $headers = array()) {
    
    	$response = null;
    
    	//valid file name
    	if ($fileName && strlen($fileName)>0) {
	    
	    	//create file
	        $file = new File((string) $fileName);
	        $base = $name ?: basename($fileName);
	        $content = file_get_contents($fileName);
	 
	        $response = Response::make($content);
	 
	 		//add content headers (if required)
	        if (!isset($headers['Content-Type']))
	        {
	            $headers['Content-Type'] = $file->getMimeType();
	        }
	        if (!isset($headers['Content-Length']))
	        {
	            $headers['Content-Length'] = $file->getSize();
	        }
	        if (!isset($headers['Content-disposition']))
	        {
	            $headers['Content-disposition'] = 'attachment; filename=' . $base;
	        }
	 
	 		//add custom headers
	        foreach ($headers as $headerName => $headerValue)
	        {
	            $response->header($headerName, $headerValue);
	        }
	 
	 		//delete temporaty file
	        unlink($fileName);
        
    	} //end if (valid file name)
    	
	    return $response;
    	
    } //end createTempFileDownloadResponse()
	*/
?>