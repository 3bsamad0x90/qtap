<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    //
    protected $guarded = [];

    public function users()
    {
        return $this->hasMany(User::class,'city');
    }
    public function apiData($lang)
    {
        return [
            'id' => $this->id,
            'name' => $this['name_'.$lang] != '' ? $this['name_'.$lang] : $this['name_en']
        ];
    }

}
