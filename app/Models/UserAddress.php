<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    //
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function countryData()
    {
        if (getCountryByIso($this->country)['name'] != '') {
            $name = getCountryByIso($this->country)['name'];
        }else {
            $name = '';
        }

        return [
            'id' => $this->country,
            'country_code' => $this->country,
            'name' => $name,
        ];
        return $this->belongsTo(Countries::class,'country','iso');
    }
    public function governorateData()
    {
        return $this->belongsTo(Governorates::class,'governorate');
    }
    public function cityData()
    {
        return [
            'id' => $this->city,
            'name' => $this->city,
        ];
        return $this->belongsTo(Cities::class,'city');
    }

    public function apiData($lang)
    {
        return [
            'id' => $this->id,
            'user' => $this->user != '' ? $this->user->apiData($lang) : ['id' => ''],
            'country' => $this->countryData(),
            'city' => $this->cityData(),
            // 'country' => $this->countryData != '' ? $this->countryData->apiData($lang) : ['id'=>'','name'=>''],
            // 'governorate' => $this->governorateData != '' ? $this->governorateData->apiData($lang) : ['id'=>'','name'=>''],
            // 'city' => $this->cityData != '' ? $this->cityData->apiData($lang) : ['id'=>'','name'=>''],
            'address' => $this->address,
            'postal_code' => $this->postal_code,
            'phone' => $this->phone
        ];
    }

}
