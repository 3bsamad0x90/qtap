<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingLocales extends Model
{
    protected $guarded = [];
    public function fromCountry()
    {
        return $this->belongsTo(Countries::class,'from_country');
    }
    public function toCountry()
    {
        return $this->belongsTo(Countries::class,'to_country');
    }
}
