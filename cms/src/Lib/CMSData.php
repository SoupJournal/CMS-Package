<?php

	namespace Soup\CMS\Lib;
	

	class CMSData {
		
		//content status types
		const STATUS_DRAFT 		= 0;
		const STATUS_PUBLISHED 	= 1;
		const STATUS_DELETED 	= 2;
		
		
		//form types
		public static $FORM_TYPE_PAGE 		= 0;
		public static $FORM_TYPE_TEMPLATE	= 1;
		
		
		//trigger types
		const TRIGGER_TYPE_INTERNAL 	= 0;
		const TRIGGER_TYPE_URL 			= 1;
		
		
		//email types
		const EMAIL_TYPE_VIEW 	= 0;
		const EMAIL_TYPE_HTML 	= 1;
		const EMAIL_TYPE_URL 	= 2;
		
		
		//value types
		const VALUE_LITERAL		 = 0;
		const VALUE_TIME 		 = 1;
		const VALUE_RANDOM		 = 2;
		
	} //end class CMSData



?>