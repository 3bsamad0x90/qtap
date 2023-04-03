<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
    //
    protected $guarded = [];

    public function apiData($lang)
    {
        return [
            'id' => $this->id,
            'name' => $this['name_'.$lang] != '' ? $this['name_'.$lang] : $this['name_en'],
            'country_code' => $this['iso']
        ];
    }
    public function users()
    {
        return $this->hasMany(User::class,'country');
    }
    public function governorates()
    {
        return $this->hasMany(Governorates::class,'country');
    }
    public function currencyDetails()
    {
        return $this->hasOne(Currencies::class,'country');
    }
}
