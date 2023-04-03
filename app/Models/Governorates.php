<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Governorates extends Model
{
    //
    protected $guarded = [];
    public function users()
    {
        return $this->hasMany(User::class,'governorate');
    }

    public function cities()
    {
        return $this->hasMany(Cities::class,'governorate');
    }
    public function apiData($lang)
    {
        return [
            'id' => $this->id,
            'name' => $this['name_'.$lang] != '' ? $this['name_'.$lang] : $this['name_en'],
            'name_en' => $this['name_en']
        ];
    }

}
