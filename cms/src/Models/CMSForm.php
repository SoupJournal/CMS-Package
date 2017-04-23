<?php

namespace Soup\CMS\Models;

use Soup\CMS\Lib\Model\BaseModel;
use Soup\CMS\Models\CMSFormField;
use Soup\CMS\Models\CMSApp;


class CMSForm extends BaseModel {

	//set model table name
    protected $table = 'form';




	//==========================================================//
	//====				RELATIONSHIP METHODS				====//
	//==========================================================//	
		

	/**
     * Get the user associated with this profile.
     */
	public function application() {
    
        return $this->belongsTo(CMSApp::class, 'application', 'id');
        
    } //end application()
    


	/**
     * Get the fields associated with this form.
     */
	public function fields() {
    
        return $this->hasMany(CMSFormField::class, 'form', 'id');
        
    } //end fields()


} //end class CMSForm


?>