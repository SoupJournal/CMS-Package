<?php


class CMSForm extends BaseModel {

	//set model table name
    protected $table = 'form';



	/**
     * Get the fields associated with this form.
     */
	public function fields() {
    
        return $this->hasMany('CMSFormField', 'form', 'id');
        
    } //end fields()


} //end class CMSForm


?>