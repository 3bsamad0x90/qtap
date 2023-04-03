<?php

namespace App\Models;

use App\Models\Products;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{

    protected $guarded = [];
    protected $table = 'order_items';
    public function product()
    {
        return $this->belongsTo(Products::class,'product_id');
    }

    public function order(){
        return $this->belongsTo(Orders::class,'order_id');
    }

    public function typeText()
    {
        $text = trans('common.'.$this->book_type);
        return $text;
    }
    public function apiData($lang)
    {
        $data = [
          'id' => $this->id,
          'product' => $this->product->apiData($lang),
          'price' => $this->price,
          'quantity' => $this->quantity,
          'total' => $this->total,
        ];
        return $data;
    }
    public function subOrder()
    {
        return $this->belongsTo(Orders::class,'order_id');
    }

}
