<?php

namespace Soup\CMS\Models;

use Soup\CMS\Lib\Model\BaseModel;
use Soup\CMS\Models\CMSUser;
use Soup\CMS\Models\CMSSecurity;


class CMSSecurityPermission extends BaseModel {

	//set model table name
    protected $table = 'security_group_permission';




	/**
     * Get the security group record associated with this permission.
     */
	public function group() {
    
        return $this->hasOne(CMSSecurity::class, 'id', 'security_group');
        
    } //end group()


	/**
     * Get the user record associated with this permission.
     */
	public function user() {
    
        return $this->hasOne(CMSUser::class, 'id', 'user');
        
    } //end user()
    


} //end class CMSSecurityPermission


?>