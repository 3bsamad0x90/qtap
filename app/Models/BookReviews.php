<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookReviews extends Model
{
    //
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function apiData()
    {
        return [
            'id' => $this->id,
            'user' => $this->user != '' ? $this->user->apiData('ar') : '',
            'user_id' => $this->user_id,
            'book_id' => $this->user_id,
            'rate' => $this->rate,
            'comment' => $this->comment,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
    public function book()
    {
        return $this->belongsTo(Books::class,'book_id');
    }

}
