<?php

use Auction\AuctionOffer;
use Estate\Estate;
use Kareem3d\Membership\User as Kareem3dUser;

class User extends Kareem3dUser {

    /**
     * @var array
     */
    protected $arCustomMessages = array(
        'email.required' => 'يجب إدخال الإيميل',
        'email.email' => 'يجب إدخال إيميل صحيح',
        'email.unique' => 'هذا الإيميل موجود من قبل',
        'password.required' => 'يجب إدخال كلمة السر',
        'password.regex' => 'يجب ان تكون كلمة السر بالحروف الأجنبية وبها على الأقل رقم.'
    );

    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = array(
        'username' => 'min:6|unique:ka_user_accounts',
        'email'    => 'required|email|unique:ka_user_accounts',
        // Medium password strength is required
        'password' => 'required|regex:((?=.*\d)(?=.*[a-z]).{6,20})',
    );

    /**
     * @return bool
     */
    public function isAdministrator()
    {
        return $this->access > 0;
    }

    /**
     * @param $token
     * @return User
     */
    public static function getByToken($token)
    {
        foreach(static::all() as $user)
        {
            if($user->checkToken($token)) return $user;
        }
    }

    /**
     * @param $token
     * @return bool
     */
    public function checkToken($token)
    {
        return $this->getToken() === $token;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return md5($this->password);
    }

    /**
     * @param $password
     * @return bool
     */
    public function changePassword($password)
    {
        $this->password = $password;

        return $this->save();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function estates()
    {
        return $this->hasMany(Estate::getClass());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function auctionOffers()
    {
        return $this->hasMany(AuctionOffer::getClass());
    }
}