<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FAQs;
use App\Models\Settings;
use App\Models\Currencies;
use App\Models\Countries;
use App\Models\Pages;
use Response;

class StaticPagesController extends Controller
{

    public function mainpage(Request $request){
        $lang = $request->header('lang');
        if ($lang == '') {
            $resArr = [
                'status' => false,
                'message' => trans('api.pleaseSendLangCode'),
                'data' => []
            ];
            return response()->json($resArr);
        }
        $dely = 400;
        $boxes = [];
        for($i = 1; $i <=4 ; $i++ ){
          $boxes[] = [
            'title' => getSettingValue('feature_'.$i.'title_'.$lang),
            'desc' => getSettingValue('feature_'.$i.'des_'.$lang),
            'icon' => getSettingImageLink('feature'.$i.'icon'),
            'dely' => $dely ,
          ];
          $dely = $dely + 200;
        }
        $list = [
            'general' => [
                'title' => getSettingValue('siteTitle_'.$lang),
                'description' => getSettingValue('siteDescription'),
                'logo' => getSettingImageLink('logo'),
            ],
            'mainpage' => [
                'title' => getSettingValue('mainPageTitle_'.$lang),
                'description' => getSettingValue('mainPageTitle_'.$lang),
                'image1' => getSettingImageLink('mainPageImage1'),
                'image2' => getSettingImageLink('mainPageImage2'),
            ],
            'features' => [
                'title' => getSettingValue('featureTitle_'.$lang),
                'description' => getSettingValue('featureDes_'.$lang),
                'image' => getSettingImageLink('featureImage'),
                'boxes' => $boxes,
            ],
            'aboutus' => [
                'title' => getSettingValue('aboutusTitle_'.$lang),
                'description' => getSettingValue('aboutusTitle_'.$lang),
                'image' => getSettingImageLink('aboutusImage'),
            ],
            'contactus' => [
                'email' => getSettingValue('contactusEmail'),
                'phone' => getSettingValue('contactusPhone'),
                'address' => getSettingValue('contactusAddress'),
            ]
        ];
        $resArr = [
            'status' => true,
            'data' => $list
        ];
        return response()->json($resArr);
    }
    public function contactus(Request $request){
        $lang = $request->header('lang');
        if ($lang == '') {
            $resArr = [
                'status' => false,
                'message' => trans('api.pleaseSendLangCode'),
                'data' => []
            ];
            return response()->json($resArr);
        }
        $list = [
            'contactus' => [
                'name' => getSettingValue('contactusName'),
                'email' => getSettingValue('contactusEmail'),
                'phone' => getSettingValue('contactusPhone'),
                'address' => getSettingValue('contactusAddress'),
                'message' => getSettingValue('contactusMessage'),
            ],
            'followUs' => [
                'facebook' => getSettingValue('facebook'),
                'youtube' => getSettingValue('youtube'),
                'twitter' => getSettingValue('twitter'),
                'instagram' => getSettingValue('instagram'),
                'linkedin' => getSettingValue('linkedin'),
                'whatsapp' => getSettingValue('whatsapp'),
                'tiktok' => getSettingValue('tiktok'),
                'snapchat' => getSettingValue('snapchat'),
            ]
        ];
        $resArr = [
            'status' => true,
            'data' => $list
        ];
        return response()->json($resArr);
    }

    public function aboutus(Request $request){
        $lang = $request->header('lang');
        if ($lang == '') {
            $resArr = [
                'status' => false,
                'message' => trans('api.pleaseSendLangCode'),
                'data' => []
            ];
            return response()->json($resArr);
        }
        $list = [
            'aboutus' => [
                'upperPart' => [
                    'description' => getSettingValue('aboutusDes_1_'.$lang),
                    'image' => getSettingImageLink('aboutusImage1'),
                ],
                'sidePart' => [
                    'description' => getSettingValue('aboutusDes_2_'.$lang),
                    'image' => getSettingImageLink('aboutusImage2'),
                ],
                'features' => [
                    'box1' => [
                        'title' => getSettingValue('aboutus_1title_'.$lang),
                        'description' => getSettingValue('aboutus_1des_'.$lang),
                        'icon' => getSettingImageLink('aboutus1icon')
                    ],
                    'box2' => [
                        'title' => getSettingValue('aboutus_2title_'.$lang),
                        'description' => getSettingValue('aboutus_2des_'.$lang),
                        'icon' => getSettingImageLink('aboutus2icon')
                    ],
                    'box3' => [
                        'title' => getSettingValue('aboutus_3title_'.$lang),
                        'description' => getSettingValue('aboutus_3des_'.$lang),
                        'icon' => getSettingImageLink('aboutus3icon')
                    ],
                ],
            ]
        ];
        $resArr = [
            'status' => true,
            'data' => $list
        ];
        return response()->json($resArr);
    }

    public function settings(Request $request)
    {
        $lang = $request->header('lang');
        $country = $request->header('country');
        if ($lang == '') {
            $resArr = [
                'status' => 'faild',
                'message' => trans('api.pleaseSendLangCode'),
                'data' => []
            ];
            return response()->json($resArr);
        }

        $list = [
            'site_status' => [
                'status' => getSettingValue('closeSite'),
                'text' => getSettingValue('closeSiteText')
            ],
            'SEO' => [
                'title' => getSettingValue('siteTitle_'.$lang),
                'description' => getSettingValue('siteDescription'),
                'keywords' => getSettingValue('siteKeywords')
            ],
            'social' => [
                'facebook' => getSettingValue('facebook'),
                'twitter' => getSettingValue('twitter'),
                'instagram' => getSettingValue('instagram'),
                'youtube' => getSettingValue('youtube')
            ],
            'images' => [
                'logo' => getSettingImageLink(getSettingValue('logo')),
                'fav' => getSettingImageLink(getSettingValue('fav'))
            ],
            'contact_data' => [
                'phone' => getSettingValue('phone'),
                'mobile' => getSettingValue('mobile'),
                'email' => getSettingValue('email'),
                'map' => getSettingValue('map'),
                'address' => getSettingValue('address')
            ],
            'slider' => $this->getHomeSliderArr($lang),
            'countries' => $this->countriesList($lang),
            'currencies' => $this->getCurrenciesArr($lang,$country),
            'primaryCurrency' => $this->getCurrenciesArr($lang,$country)[0],
            'about_page' => $this->getPageContent($lang,'1'),
            'policy_page' => $this->getPageContent($lang,'2'),
            'paymentMethods' => [
                'POD' => getSettingValue('podPaymentMethod') != '' ? getSettingValue('podPaymentMethod') : 0,
                'stripe' => 1
            ],
            'shippingMethods' => [
                'free' => [
                    'id' => 'free',
                    'status' => getSettingValue('freeShipping'),
                    'name' => trans('common.freeShipping'),
                    'time' => [
                        'from' => getSettingValue('freeShippingTimeFrom'),
                        'to' => getSettingValue('freeShippingTimeTo')
                    ]
                ],
                'traditional' => [
                    'id' => 'traditional',
                    'status' => getSettingValue('otherShippingMethod'),
                    'name' => trans('common.otherShippingMethod'),
                    'time' => [
                        'from' => getSettingValue('otherShippingMethodTimeFrom'),
                        'to' => getSettingValue('otherShippingMethodTimeTo')
                    ]
                ],
                'exprese' => [
                    'id' => 'exprese',
                    'status' => getSettingValue('expreseShippingStatus'),
                    'name' => trans('common.expreseShipping'),
                    'time' => [
                        'from' => getSettingValue('expreseShippingTimeFrom'),
                        'to' => getSettingValue('expreseShippingTimeTo')
                    ]
                ],
                'fedex' => [
                    'id' => 'fedex',
                    'status' => 1,
                    'name' => trans('common.fedexShipping'),
                    'time' => [
                        'from' => getSettingValue('fedexShippingTimeFrom'),
                        'to' => getSettingValue('fedexShippingTimeTo')
                    ]
                ]
            ],
            'messageSubjects' => messageSubjects($lang),
            'user_meta' => [
                'notification_count' => 0,
                'cart_items' => 0
            ]

        ];
        $resArr = [
            'status' => 'success',
            'message' => '',
            'data' => $list
        ];
        return response()->json($resArr);

    }

    public function getHomeSliderArr($lang)
    {
        $array = [];
        for ($i=0; $i < 5; $i++) {
            if (getSettingValue('home_slide'.$i.'title_'.$lang) != '') {
                $array[] = [
                    'image' => getSettingImageLink('home_slide'.$i.'img'),
                    'title' => getSettingValue('home_slide'.$i.'title_'.$lang),
                    'des' => getSettingValue('home_slide'.$i.'des_'.$lang),
                    'button_text' => getSettingValue('home_slide'.$i.'btnTxt_'.$lang),
                    'link' => getSettingValue('home_slide'.$i.'btnLink')
                ];
            }
        }
        return $array;
    }

    public function getCurrenciesArr($lang,$country = null)
    {
        $default = Currencies::where('primary','1')->first();
        $currencies = [$default->apiData($lang)];
        if ($country != null) {
            $countryData = Countries::find($country);
            $currencies[] = $countryData->currencyDetails->apiData($lang);
        }
        return $currencies;
    }

    public function countriesList($lang)
    {
        $countries = Countries::orderBy('name_'.$lang)->get();
        $list = [];
        foreach ($countries as $key => $country) {
            $list[] = [
                'id' => $country['id'],
                'name' => $country['name_'.$lang] != '' ? $country['name_'.$lang] : $country['name_en']
            ];
        }
        return $list;
    }

    public function getPageContent($lang,$id)
    {
        $page = Pages::find($id);
        return [
            'id' => $page != '' ? $page->id : '',
            'title' => $page != '' ? $page['title_'.$lang] : '',
            'content' => $page != '' ? $page['content_'.$lang] : ''
        ];
    }

}
