<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;

class Notification extends Model
{
    //
    public function imageLink()
    {
        $link = getSettingImageLink(getSettingValue('logo'));
        if ($this['data']['type'] == 'newPublisher') {
            $thePublisher = User::find($this['data']['linked_id']);
            if ($thePublisher != '') {
                $link = $thePublisher->photoLink();
            }
        }
        return $link;
    }
}
