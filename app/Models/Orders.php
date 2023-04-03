<?php

namespace App\Models;

use App\Models\OrderItems;
use App\Models\Products;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    //
    protected $guarded = [];

    public function address()
    {
        return $this->belongsTo(UserAddress::class,'shipping_address_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function subOrders()
    {
        return $this->hasMany(OrderItems::class,'order_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItems::class,'order_id');
    }


    public function discount()
    {
        $discount = 0;
        if ($this->coupon != '') {
            if ($this->coupon->canUse($this->id) > 0) {
                $discount = $this->coupon->canUse($this->id)['discount'];
            }
        }
        return $discount;
    }

    public function totals()
    {
        $total = 0;
        $netTotal = 0;

        if ($this->main_order_id == 0) {
            foreach ($this->subOrders as $subOrder) {
                // $total += $subOrder->total;
                // $discount = $this->discount();
                // $netTotal = $total - $this->discount();
            }
        }
        //  else {
        //     $totals = [
        //         'total' => $this->items()->sum('total'),
        //         'discount' => $this->discount(),
        //         'netTotal' => $this->items()->sum('total') - $this->discount()
        //     ];
        // }
        return [
            'total' => $total,
            'netTotal' => $netTotal
        ];
    }

    public function getDate($lang)
    {
        $date = date('l d F Y',strtotime($this['updated_at']));
        $time = date('H:i',strtotime($this['updated_at']));
        if ($lang == 'ar') {
            $date = DayMonthOnly($this['updated_at']);
            $time = getTime($this['updated_at']);
        }
        return [
            'date' => $date,
            'time' => $time
        ];
    }
    public function apiData($lang)
    {
      $data = [
        'id' => $this->id,
        'status' => $this->status,
        'total' => $this->total,
        'quantity' => $this->quantity,
      ];
      return $data;
    }
    public function itemsCalculator()
    {
        $weight = 0;
        $count = 0;

        foreach ($this->items as $key => $value) {
            if ($value->book != '') {
                $weight += $value['unit_weight'] * $value['quantity'];
                $count += $value['quantity'];
            }
        }

        return [
            'weight' => $weight,
            'count' => $count
        ];
    }
}
