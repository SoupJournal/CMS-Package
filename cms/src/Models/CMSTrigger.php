<?php

namespace Soup\CMS\Models;

use Soup\CMS\Lib\Model\BaseModel;
use Soup\CMS\Models\CMSFormField;
use Soup\CMS\Models\CMSApp;


class CMSTrigger extends BaseModel {

	//set model table name
    protected $table = 'trigger';




	//==========================================================//
	//====				RELATIONSHIP METHODS				====//
	//==========================================================//	
		

	/**
     * Get the user associated with this profile.
     */
/*	public function application() {
    
        return $this->belongsTo(CMSApp::class, 'application', 'id');
        
    } //end application()
  */  


} //end class CMSTrigger


?>