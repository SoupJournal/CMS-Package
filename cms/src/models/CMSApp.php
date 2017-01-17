<?php


class CMSApp extends BaseModel {

	//set model table name
    protected $table = 'application';


	/**
     * Get the security group records associated with this application.
     */
	public function group() {
    
        return $this->hasMany('CMSSecurity', 'application', 'id');
        
    } //end group()


} //end class CMSApp


?>