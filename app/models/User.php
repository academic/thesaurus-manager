<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

    use ValidationTrait;

    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * Fields to protect from mass assign
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * Fields to mass assign
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = [
        'first_name'            => 'Required',
        'last_name'             => 'Required',
        'email'                 => 'Required|Email|Unique:users',
        'password'              => 'Required|Confirmed',
        'password_confirmation' => 'Required'
    ];

    /**
     * Validation messagess
     *
     * @var array
     */
    protected $messages = [
    ];

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

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

}
