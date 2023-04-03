<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cards extends Model
{
    protected $fillable = [
      	'title',
        'full_name',
        'email',
        'slug',
        'phone',
     	'image',
        'website',
        'language',
        'type',
        'theme',
        'user_id',
    ];
}
