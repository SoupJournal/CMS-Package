<?php

	
	function safeValue($key, $object, $default = null) {
		
		$result = $default;
		
		//valid object
		if ($object) {
			
			//array object
			if (is_array($object)) {
				$result = safeArrayValue($key, $object, $default);
			}
			//object value
			else {
				$result = safeObjectValue($key, $object, $default);
			}
			
		} //end if (valid object)
		
		return $result;
		
	} //end safeValue()
	
	
	
	
	function safeArrayValue($key, $array, $default = null) {
		
		$result = $default;
		
		//valid array
		if ($array && is_array($array)) {
		
			//valid key
			if ($key && strlen($key)>0) {
				
				//key/value exists
				if (array_key_exists($key, $array) && isset($array[$key])) {
					$result = $array[$key];	
				}
				
			} //end if (valid key)
		
		} //end if (valid array)
		
		return $result;
		
	} //end safeArrayValue()
	
	
	
	
	function safeObjectValue($key, $object, $default = null) {
		
		$result = $default;
		
		//valid object
		if ($object) {
		
			//valid key
			if ($key && strlen($key)>0) {
				
				//key/value exists
				if (property_exists($object, $key) && isset($object->$key)) {
					$result = $object->$key;	
				}
				//handle __get properties
				else if (isset($object->$key)) {
					$result = $object->$key;
				}
				
			} //end if (valid key)
		
		} //end if (valid object)
		
		return $result;
		
	} //end safeObjectValue()
	
	
	
	
	function decodeJSON($json, $decodeAsArray = false, $default = null) {
		
		$result = $default;
		
		//valid JSON string
		if ($json && strlen($json)>0) {
			
			try {
				
				//decode json
				$result = json_decode($json, $decodeAsArray);
				
			}
			catch (Exception $ex) {
				Log::warning("Error decoding JSON: " . $ex);	
			}
			
		} //end if (valid string)
		
		return $result;
		
	} //end decodeJSON()


?>