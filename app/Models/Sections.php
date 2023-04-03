<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sections extends Model
{
    //
    protected $guarded = [];

    public function subSections()
    {
        return $this->hasMany(Sections::class,'main_section');
    }
    public function mainSection()
    {
        return $this->belongsTo(Sections::class,'main_section');
    }
    public function books()
    {
        return $this->hasMany(Books::class,'section_id');
    }
    public function apiData($lang)
    {
        $subs = $this->subSections;
        $subsList = [];
        $allSubsBooksNo = 0;
        if ($subs != '') {
            foreach ($subs as $key => $value) {
                $allSubsBooksNo += $value->books()->count();
                $subsList[] = $value->apiData($lang);
            }
        }
        return [
            'id' => $this->id,
            'name' => $this['name_'.$lang] != '' ? $this['name_'.$lang] : $this['name_en'],
            'subs' => $subsList,
            'booksNo' => $this->books()->count() + $allSubsBooksNo
        ];
    }
}
