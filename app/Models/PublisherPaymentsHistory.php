<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublisherPaymentsHistory extends Model
{
    //
    protected $guarded = [];
    public function publisher()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function attachmentLink()
    {
        $image = '';

        if ($this->attachment != '') {
            $image = asset('uploads/publishersPaymentsHistory/'.$this->id.'/'.$this->attachment);
        }

        return $image;
    }

}
