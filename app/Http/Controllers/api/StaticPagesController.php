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
          if(getSettingImageLink('feature' . $i . 'icon') != ''){
            $boxes[] = [
              'title' => getSettingValue('feature_'.$i.'title_'.$lang),
              'desc' => getSettingValue('feature_'.$i.'des_'.$lang),
              'icon' => getSettingImageLink('feature'.$i.'icon'),
              'dely' => $dely ,
            ];
            $dely = $dely + 200;
          }
        }
        $list = [
            'general' => [
                'title' => getSettingValue('siteTitle_'.$lang),
                'description' => getSettingValue('siteDescription'),
                'logo' => getSettingImageLink('logo'),
            ],
            'mainpage' => [
                'title' => getSettingValue('mainPageTitle_'.$lang),
                'description' => getSettingValue('mainPageDes_'.$lang),
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
                'description' => getSettingValue('aboutusDes_'.$lang),
                'image' => getSettingImageLink('aboutusImage'),
            ],
            'contactus' => [
                'email' => getSettingValue('contactusEmail'),
                'phone' => getSettingValue('contactusPhone'),
                'address' => getSettingValue('contactusAddress'),
            ],
            'followUs' => [
              'facebook' => getSettingValue('facebook'),
              'twitter' => getSettingValue('twitter'),
              'instagram' => getSettingValue('instagram'),
              'youtube' => getSettingValue('youtube'),
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
                'email' => getSettingValue('contactusEmail'),
                'phone' => getSettingValue('contactusPhone'),
                'address' => getSettingValue('contactusAddress'),
            ],
            'followUs' => [
                'facebook' => getSettingValue('facebook'),
                'twitter' => getSettingValue('twitter'),
                'instagram' => getSettingValue('instagram'),
                'youtube' => getSettingValue('youtube'),
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


}
