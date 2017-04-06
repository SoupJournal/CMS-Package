<?php

namespace Soup\CMS\Models;

use Soup\CMS\Lib\Model\BaseModel;
use Soup\CMS\Models\CMSSecurityPermission;

//use Illuminate\Auth\UserInterface;
use Illuminate\Foundation\Auth\User;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

//class CMSUser extends User { // BaseModel implements AuthenticatableContract { //UserInterface {
class CMSUser extends BaseModel implements AuthenticatableContract {

	//set model table name
    protected $table = 'user';




		//==========================================================//
		//====					DATA METHODS					====//
		//==========================================================//	
			



	/**
     * Get the security permission records associated with this user.
     */
	public function permissions() {
    
        return $this->hasMany(CMSSecurityPermission::class, 'user', 'id');
        
    } //end permissions()





		//==========================================================//
		//====				AUTHENTICATION METHODS				====//
		//==========================================================//	
			


    /**
	 * Get the unique identifier name for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifierName()
	{
	    return $this->getKeyName(); //return column name 'id'
	}


    /**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
    public function getAuthIdentifier()
    {
        return $this->getKey(); //return user id
    }


    /**
	 * Get the password for the user.
	 *
	 * @return string
	 */
    public function getAuthPassword()
    {
        return $this->password;
    }




    /**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
//    public function getReminderEmail()
//    {
//        return $this->email;
//    }
        

    public function getRememberToken()
    {
        return $this->remember_token;
    }


    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }
    

} //end class CMSUser


?>