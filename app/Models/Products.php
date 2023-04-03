<?php

namespace App\Models;

use App\Models\Reviews;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{

    protected $fillable = [
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'price',
        'image',
        'video',
        'images',
        'review',
    ];
    public function photoLink()
    {
        $image = asset('AdminAssets/app-assets/images/portrait/small/avatar.png');

        if ($this->image != '') {
            $image = asset('uploads/products/'.$this->id.'/'.$this->image);
        }
        return $image;
    }
    public function reviews()
    {
        return $this->hasMany(Reviews::class, 'product_id', 'id');
    }
    public function apiData($lang)
    {
        $data = [
          'id' => $this->id,
          'title' => $this['title_'.$lang],
          'description' => $this['description_'.$lang],
          'price' => $this->price,
          'image' => $this->photoLink(),
          'review' => $this->reviews->count(),
        ];
        return $data;
    }
}
