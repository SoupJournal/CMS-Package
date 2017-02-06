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


	/**
     * Get the user record associated with this permission.
     */
	public function user() {
    
        return $this->hasOne('CMSUser', 'id', 'user');
        
    } //end user()
    


} //end class CMSSecurityPermission


?>