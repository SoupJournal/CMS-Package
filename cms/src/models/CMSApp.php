<?php

namespace Soup\CMS\Models;

use Soup\CMS\Lib\Model\BaseModel;
use Soup\CMS\Models\CMSSecurity;



class CMSApp extends BaseModel {

	//set model table name
    protected $table = 'application';


	/**
     * Get the security group records associated with this application.
     */
	public function group() {
    
        return $this->hasMany(CMSSecurity::class, 'application', 'id');
        
    } //end group()


} //end class CMSApp


?>