<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @var array
     */
    protected $hidden = array('password');

    /**
     * @return mixed
     */
    public function getAuthIdentifier() {
        return $this->getKey();
    }

    /**
     * @return string
     */
    public function getAuthPassword() {
        return $this->password;
    }

    /**
     * @access   public
     * @return   string
     */
    public function fullName() {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * @return string
     */
    public function getReminderEmail() {
        return $this->email;
    }

    public function getRememberToken() {
        return $this->remember_token;
    }

    public function setRememberToken($value) {
        $this->remember_token = $value;
    }

    public function getRememberTokenName() {
        return 'remember_token';
    }

}
