<?php



	function isSQLSafeString($text) {
		
		$result = true;
		
		//valid text
		if ($text && is_string($text) && strlen($text)>0) {
			
			if (preg_match("/%/", $text) || preg_match("/'/", $text) || preg_match("/\\\\/", $text)) {
				$result = false;
			}
	 
		}

		return $result;
		
	} //end isSQLSafeString()




	function SQLSafeString($text) {
		
		$result = $text;
		
		//valid text
		if ($text && is_string($text) && strlen($text)>0) {
			
			$result = preg_replace("/%/", "\[%\]", $text);
			$result = preg_replace("/'/", "''", $text);
			$result = preg_replace("/\\\\/", "\\\\\\\\", $result);			 
		}

		return $text;
		
	} //end SQLSafeString()


?>