<?php

use Estate\Estate;
use Kareem3d\Membership\UserInfo as Kareem3dUserInfo;

class UserInfo extends Kareem3dUserInfo {

    /**
     * Validations rules
     *
     * @var array
     */
    protected $rules = array(

        'ip'            => 'required|ip',
        'first_name'    => 'required',
    );

    /**
     * @var array
     */
    protected $arCustomMessages = array(
        'first_name.required'    => 'يجب إدخال الأسم بالكامل'
    );

    /**
     * @param UserInfo $userInfo
     */
    public function merge(UserInfo $userInfo)
    {
        foreach($userInfo->getAttributes() as $key => $value)
        {
            if(! $this->getAttribute($key))
            {
                $this->setAttribute($key, $value);
            }
        }

        // Update ip
        $this->ip = $userInfo->ip;

        $this->save();
    }

    /**
     * @return string
     */
    public function getContactNumberAttribute()
    {
        return $this->telephone_number ?: $this->mobile_number;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function estates()
    {
        return $this->hasMany(Estate::getClass(), 'owner_info_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contactUs()
    {
        return $this->hasMany(ContactUs::getClass(), 'owner_info_id');
    }
}