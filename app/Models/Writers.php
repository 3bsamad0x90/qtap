<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Writers extends Model
{
    //
    protected $guarded = [];

    public function apiData($lang,$details = null)
    {
        $data =[
            'id' => $this->id,
            'name' => $this['name_'.$lang],
            'bio' => $this['bio_'.$lang],
            'photo' => $this->photoLink(),
            'country' => $this->countryData != '' ? $this->countryData->apiData($lang) : ['id'=>'','name'=>''],
        ];
        if ($details != '') {
            if ($this->books()->count() > 0) {
                $books = $this->books;
                $data['books'] = [];
                foreach ($books as $key => $value) {
                    $data['books'][] = $value->apiData($lang);
                }
            }
        }
        return $data;
    }

    public function countryData()
    {
        return $this->belongsTo(Countries::class,'country');
    }

    public function photoLink()
    {
        $image = asset('AdminAssets/app-assets/images/portrait/small/avatar-s-11.jpg');

        if ($this->photo != '') {
            $image = asset('uploads/writers/'.$this->id.'/'.$this->photo);
        }

        return $image;
    }

    public function books()
    {
        return $this->hasMany(Books::class,'writer_id');
    }

    public function statusText()
    {
        $text = '';
        if ($this->active == '1') {
            $text .= '<span class="btn btn-sm btn-success">'.trans('common.active').'</span>';
        } else {
            $text .= '<span class="btn btn-sm btn-danger">'.trans('common.inactive').'</span>';
        }
        return $text;
    }

}
