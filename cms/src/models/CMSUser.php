<?php

use Illuminate\Auth\UserInterface;

class CMSUser extends BaseModel implements UserInterface {

	//set model table name
    protected $table = 'user';




	/**
     * Get the security permission records associated with this user.
     */
	public function permissions() {
    
        return $this->hasMany('CMSSecurityPermission', 'user', 'id');
        
    } //end permissions()




    /**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
    public function getAuthIdentifier()
    {
        return $this->getKey();
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