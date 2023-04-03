<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $lang = $request->header('lang');
        $allImages = [];
        if($this->images){
            $images = json_decode($this->images);
            foreach($images as $image){
                $allImages[] = url('uploads/products/'.$this->id.'/'.$image);
            }
        }
        return [
            'id' => $this->id,
            'title' => $this['title_'.$lang],
            'description' => $this['description_'.$lang],
            'price' => $this->price,
            'type' => $this['type'],
            'image' => $this->photoLink(),
            'images' =>   $allImages,
            'video' => $this->video,
          	'review' => ceil($this->reviews->count() > 0 ? $this->reviews->sum('rating') / $this->reviews->count() : 0),
        ];
    }
}
