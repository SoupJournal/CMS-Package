<?php


class CMSSecurity extends BaseModel {

	//set model table name
    protected $table = 'security_group';



	/**
     * Get the application record associated with this security group.
     */
	public function application() {
    
        return $this->hasOne('CMSApp', 'id', 'application');
        
    } //end application()



	/**
     * Get the permission records associated with this security group.
     */
	public function permissions() {
    
        return $this->hasMany('CMSSecurityPermission', 'security_group', 'id');
        
    } //end permissions()
    
    

} //end class CMSSecurity


?>