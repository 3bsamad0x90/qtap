<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Books extends Model
{
    //
    protected $guarded = [];

    public function writer()
    {
        return $this->belongsTo(Writers::class,'writer_id');
    }

    public function publisher()
    {
        return $this->belongsTo(User::class,'publisher_id');
    }

    public function section()
    {
        return $this->belongsTo(Sections::class,'section_id');
    }

    public function languageText()
    {
        $text = '';
        if ($this->language == 'en') {
            $text = trans('common.lang2Name');
        } elseif ($this->language == 'fr') {
            $text = trans('common.lang3Name');
        } else {
            $text = trans('common.lang1Name');
        }
        return $text;
    }
    public function countryData()
    {
        return $this->belongsTo(Countries::class,'country');
    }
    public function photoLink()
    {
        $image = asset('AdminAssets/app-assets/images/logo/logo.png');

        if ($this->photo != '') {
            $image = asset('uploads/books/'.$this->id.'/'.$this->photo);
        }

        return $image;
    }
    public function backPageLink()
    {
        $image = '';

        if ($this->backPage != '') {
            $image = asset('uploads/books/'.$this->id.'/'.$this->backPage);
        }

        return $image;
    }
    public function reviews()
    {
        return $this->hasMany(BookReviews::class,'book_id')->with('user');
    }
    public function reviewsTotal()
    {
        $result = 0;
        $thisTotalReviews = $this->reviews()->where('status','1')->where('status','1')->sum('rate');
        $Total = $this->reviews()->where('status','1')->where('status','1')->count() * 5;
        if ($Total > 0) {
            $result = ($thisTotalReviews / $Total) * 5;
        }
        return round($result);
    }
    public function related()
    {
        $books = Books::where('name_ar', 'LIKE', "%".$this->name_ar."%")->where('id','!=',$this->id)->get();
        return $books;
    }
    public function reviewsApiData()
    {
        $reviews = [];
        foreach ($this->reviews()->where('status','1')->get() as $key => $value) {
            $reviews[] = $value->apiData();
        }
        return $reviews;
    }
    public function getThePrice($currency,$type)
    {
        if ($type == 'pdfCopy') {
            if ($currency == '') {
                $list = [
                    'original' => $this->pdf_price ?? 0,
                    'offer' => $this->pdf_offer ?? 0
                ];
            } else {
                $curruncy = Currencies::find($currency);
                if ($curruncy != '') {
                    $list = [
                        'original' => $this->pdf_price != '' ? round($this->pdf_price/$curruncy->transfer_rate) : 0,
                        'offer' => $this->pdf_offer != '' ? round($this->pdf_offer/$curruncy->transfer_rate) : 0
                    ];
                } else {
                    $list = [
                        'original' => $this->pdf_price ?? 0,
                        'offer' => $this->pdf_offer ?? 0
                    ];
                }
            }
        } else {
            if ($currency == '') {
                $list = [
                    'original' => $this->hard_price ?? 0,
                    'offer' => $this->hard_offer ?? 0
                ];
            } else {
                $curruncy = Currencies::find($currency);
                if ($curruncy != '') {
                    $list = [
                        'original' => $this->hard_price != '' ? round($this->hard_price/$curruncy->transfer_rate) : 0,
                        'offer' => $this->hard_offer != '' ? round($this->hard_offer/$curruncy->transfer_rate) : 0
                    ];
                } else {
                    $list = [
                        'original' => $this->hard_price ?? 0,
                        'offer' => $this->hard_offer ?? 0
                    ];
                }
            }
        }

        return $list;
    }
    public function apiData($lang,$related = null,$currency = null)
    {
        $bookSlider = [$this->photoLink()];
        if ($this->backPageLink() != '') {
            $bookSlider[] = $this->backPageLink();
        }
        $bookDetails = [
            'id' => $this->id,
            'available' => $this->available,
            'canReview' => 0,
            'name' => $this['name_'.$lang] != '' ? $this['name_'.$lang] : $this['name_ar'],
            'language' => $this->languageText(),
            'photo' => $this->photoLink(),
            'slider' => $bookSlider,
            'des' => $this['des_'.$lang],
            'pages' => $this['pages'],
            'full_des' => $this['full_des_'.$lang],
            'index' => $this['index_'.$lang],
            'country' => $this->countryData != '' ? $this->countryData->apiData($lang) : ['id'=>'','name'=>''],
            'section' => $this->section != '' ? $this->section->apiData($lang) : ['id'=>'','name'=>''],
            'writer' => $this->writer != '' ? $this->writer->apiData($lang) : ['id'=>'','name'=>''],
            'publisher' => $this->publisher != '' ? $this->publisher->apiData($lang) : ['id'=>'','name'=>''],
            'stock' => $this->stock,
            'pdfCopy' => [
                            'status' => $this->pdfCopy,
                            'price' => [
                                'original' => $this->getThePrice($currency,'pdfCopy')['original'],
                                'offer' => $this->getThePrice($currency,'pdfCopy')['offer']
                            ],
                            'text' => trans('common.pdfCopy')
                        ],
            'reviews' => $this->reviewsApiData(),
            'reviewsCount' => $this->reviews()->where('status','1')->count(),
            'reviewsTotal' => $this->reviewsTotal(),
            'reviewsAnalytics' => [
                'count_0' => $this->reviews()->where('status','1')->where('rate','0')->count(),
                'count_1' => $this->reviews()->where('status','1')->where('rate','1')->count(),
                'count_2' => $this->reviews()->where('status','1')->where('rate','2')->count(),
                'count_3' => $this->reviews()->where('status','1')->where('rate','3')->count(),
                'count_4' => $this->reviews()->where('status','1')->where('rate','4')->count(),
                'count_5' => $this->reviews()->where('status','1')->where('rate','5')->count(),
            ]
        ];
        if ($this->weight != '') {
            $bookDetails['hardCopy'] = [
                'status' => $this->hardCopy,
                'price' => [
                    'original' => $this->getThePrice($currency,'hardCopy')['original'],
                    'offer' => $this->getThePrice($currency,'hardCopy')['offer']
                ],
                'text' => trans('common.hardCopy')
            ];
        } else {
            $bookDetails['hardCopy'] = [
                'status' => 0,
                'price' => [
                    'original' => $this->getThePrice($currency,'hardCopy')['original'],
                    'offer' => $this->getThePrice($currency,'hardCopy')['offer']
                ],
                'text' => trans('common.hardCopy')
            ];
        }

        if ($related != null) {

            $related = [];
            if (count($this->related())) {
                foreach ($this->related() as $key => $value) {
                    $related[] = $value->apiData($lang);
                }
            }
            $bookDetails['related'] = $related;
        }
        return $bookDetails;
    }

}
