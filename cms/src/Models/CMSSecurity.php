<?php

namespace Soup\CMS\Models;

use Soup\CMS\Lib\Model\BaseModel;
use Soup\CMS\Models\CMSApp;
use Soup\CMS\Models\CMSSecurityPermission;


class CMSSecurity extends BaseModel {

	//set model table name
    protected $table = 'security_group';




	//==========================================================//
	//====				RELATIONSHIP METHODS				====//
	//==========================================================//



	/**
     * Get the application record associated with this security group.
     */
	public function application() {
    
        return $this->belongsTo(CMSApp::class, 'application', 'id');
        
    } //end application()



	/**
     * Get the permission records associated with this security group.
     */
	public function permissions() {
    
        return $this->hasMany(CMSSecurityPermission::class, 'security_group', 'id');
        
    } //end permissions()
    
    

} //end class CMSSecurity


?>