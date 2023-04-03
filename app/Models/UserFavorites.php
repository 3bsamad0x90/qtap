<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFavorites extends Model
{
    //
    protected $guarded = [];
    public function book()
    {
        return $this->belongsTo(Books::class,'book_id');
    }
    public function apiData($lang)
    {
        $list = [
            'fav_id' => $this->id,
            'book' => $this->book != '' ? $this->book->apiData($lang) : ''
        ];
        return $list;
    }
}
