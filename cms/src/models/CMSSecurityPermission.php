<?php


class CMSSecurityPermission extends BaseModel {

	//set model table name
    protected $table = 'security_group_permission';




	/**
     * Get the security group record associated with this permission.
     */
	public function group() {
    
        return $this->hasOne('CMSSecurity', 'id', 'security_group');
        
    } //end group()



} //end class CMSSecurityPermission


?>