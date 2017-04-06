<?php

namespace Soup\CMS\Models;

use Soup\CMS\Lib\Model\BaseModel;
use Soup\CMS\Models\CMSFormField;


class CMSForm extends BaseModel {

	//set model table name
    protected $table = 'form';



	/**
     * Get the fields associated with this form.
     */
	public function fields() {
    
        return $this->hasMany(CMSFormField::class, 'form', 'id');
        
    } //end fields()


} //end class CMSForm


?>