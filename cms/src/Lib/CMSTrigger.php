<?php

	namespace Soup\CMS\Lib;
	

	interface CMSTrigger {
		
		/**
		*	process data on CMS trigger events 
		*/
		public function handleTrigger($data, $info);
		
	} //end interface CMSTrigger



?>