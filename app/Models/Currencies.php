<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currencies extends Model
{
    //
    protected $guarded = [];

    public function typeText()
    {
        $type = trans('common.default');
        if ($this->primary != '1') {
            $type = '-';
        }
        return $type;
    }
    public function apiData($lang)
    {
        return [
            'id' => $this->id,
            'name' => $this['name_'.$lang],
            'sympl' => $this['sympl_'.$lang],
            'transfer_rate' => $this['transfer_rate']
        ];
    }
}
