<?php

namespace App\Models;

use App\Models\Orders;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function hisRole()
    {
        return $this->belongsTo(roles::class,'role');
    }
    public function photoLink()
    {
        $image = asset('AdminAssets/app-assets/images/portrait/small/avatar.png');

        if ($this->photo != '') {
            $image = asset('uploads/users/'.$this->id.'/'.$this->photo);
        }

        return $image;
    }
    public function licensePhotoLink()
    {
        $image = asset('AdminAssets/app-assets/images/portrait/small/avatar.png');

        if ($this->licensePhoto != '') {
            $image = asset('uploads/users/'.$this->id.'/'.$this->licensePhoto);
        }

        return $image;
    }
    public function countryData()
    {
        $country = 'ae';
        if ($this->country != null) {
            $country = $this->country;
        }
        if (getCountryByIso($country)['name'] != '') {
            $name = getCountryByIso($country)['name'];
        }else {
            $name = '';
        }

        return [
            'id' => $this->country ?? 'ae',
            'country_code' => $this->country ?? 'ae',
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
    public function addressList()
    {
        return $this->hasMany(UserAddress::class,'user_id');
    }
    public function paymentMethods()
    {
        return $this->hasMany(UserPaymentMethods::class,'user_id');
    }
    public function bookReviews()
    {
        return $this->hasMany(BookReviews::class,'user_id');
    }
    public function favorites()
    {
        return $this->hasMany(UserFavorites::class,'user_id');
    }
    public function apiData($lang)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'last_name' => $this->last_name,
            'job_title' => $this->job_title,
            'email' => $this->email,
            'phone' => $this->phone,
            'country' => $this->country,
            'language' => $this->language,
            'city' => $this->city,
        ];
        return $data;
    }


    public function checkActive()
    {
        $active = '1';
        if ($this->active == '0') {
            $active = trans('auth.yourAcountStillNotActive');
        }
        if ($this->block == '1') {
            $active = trans('auth.yourAcountIsBlocked');
        }
        return $active;
    }

    public function sales()
    {
        return $this->hasMany(Orders::class,'publisher_id');
    }
    function mySales()
    {
        return $this->sales->where('status','done');
    }

    public function paymentsHistory()
    {
        return $this->hasMany(PublisherPaymentsHistory::class,'user_id');
    }

    public function currentBalance()
    {
        $sales = $this->mySales()->sum('total');
        $payments = $this->paymentsHistory()->sum('amount');
        return [
            'balance' => $sales-$payments,
            'balanceRate' => (($sales-$payments)/getSettingValue('MinimumForTransfeerMoney'))*100
        ];
    }

    public function orders()
    {
        return $this->hasMany(Orders::class,'user_id');
    }
    public function cart()
    {
      return $this->orders()->where('status', 'pending');
    }
    public function myCart()
    {
      return $this->cart()->first()->items;
    }



}
