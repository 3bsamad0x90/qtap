<?php
function DayMonthOnly($your_date)
{
    $months = array("Jan" => "يناير",
                     "Feb" => "فبراير",
                     "Mar" => "مارس",
                     "Apr" => "أبريل",
                     "May" => "مايو",
                     "Jun" => "يونيو",
                     "Jul" => "يوليو",
                     "Aug" => "أغسطس",
                     "Sep" => "سبتمبر",
                     "Oct" => "أكتوبر",
                     "Nov" => "نوفمبر",
                     "Dec" => "ديسمبر");
    //$your_date = date('y-m-d'); // The Current Date
    $en_month = date("M", strtotime($your_date));
    foreach ($months as $en => $ar) {
        if ($en == $en_month) { $ar_month = $ar; }
    }

    $find = array ("Sat", "Sun", "Mon", "Tue", "Wed" , "Thu", "Fri");
    $replace = array ("السبت", "الأحد", "الإثنين", "الثلاثاء", "الأربعاء", "الخميس", "الجمعة");
    $ar_day_format = date("D", strtotime($your_date)); // The Current Day
    $ar_day = str_replace($find, $replace, $ar_day_format);

    header('Content-Type: text/html; charset=utf-8');
    $standard = array("0","1","2","3","4","5","6","7","8","9");
    $eastern_arabic_symbols = array("٠","١","٢","٣","٤","٥","٦","٧","٨","٩");
    $current_date = $ar_day.' '.date('d', strtotime($your_date)).' '.$ar_month.' '.date('Y', strtotime($your_date));
    $arabic_date = str_replace($standard , $eastern_arabic_symbols , $current_date);

    return $arabic_date;
}
function getTime($time)
{
    $time = '';
    $time .= date('H:m',strtotime($time));
    $time .= date('a',strtotime($time)) == 'am' ? ' ص ' : 'م';
    return $time;
}

function panelLangMenu()
{
    $list = [];
    $locales = Config::get('app.locales');

    if (Session::get('Lang') != 'ar') {
        $list[] = [
            'flag' => 'ae',
            'text' => trans('common.lang1Name'),
            'lang' => 'ar'
        ];
    } else {
        $selected = [
            'flag' => 'ae',
            'text' => trans('common.lang1Name'),
            'lang' => 'ar'
        ];
    }
    if (Session::get('Lang') != 'en') {
        $list[] = [
            'flag' => 'us',
            'text' => trans('common.lang2Name'),
            'lang' => 'en'
        ];
    } else {
        $selected = [
            'flag' => 'us',
            'text' => trans('common.lang2Name'),
            'lang' => 'en'
        ];
    }
    if (Session::get('Lang') != 'fr') {
        $list[] = [
            'flag' => 'fr',
            'text' => trans('common.lang3Name'),
            'lang' => 'fr'
        ];
    } else {
        $selected = [
            'flag' => 'fr',
            'text' => trans('common.lang3Name'),
            'lang' => 'fr'
        ];
    }

    return [
        'selected' => $selected,
        'list' => $list
    ];
}

function getCssFolder()
{
    return trans('common.cssFile');
}

function getCountriesList($lang,$value)
{
    $list = [];
    $countries = App\Models\Countries::orderBy('name_'.$lang,'asc')->get();
    foreach ($countries as $country) {
        $list[$country[$value]] = $country['name_'.$lang] != '' ? $country['name_'.$lang] : $country['name_en'];
    }
    return $list;
}

function getCountryByIso($country)
{
    $data = ['id'=>'','name'=>''];
    $country = App\Models\Countries::where('iso',$country)->first();
    if ($country != '') {
        $data = $country->apiData('ar');
    }
    return $data;
}

function getRolesList($lang,$value,$guard = null)
{
    $list = [];
    if ($guard == null) {
        $roles = App\Models\roles::orderBy('name_'.$lang,'asc')->get();
    } else {
        $roles = App\Models\roles::where('guard',$guard)->orderBy('name_'.$lang,'asc')->get();
    }
    foreach ($roles as $role) {
        $list[$role[$value]] = $role['name_'.$lang] != '' ? $role['name_'.$lang] : $role['name_ar'];
    }
    return $list;
}

function getWritersList($lang)
{
    $list = [];
    $writers = App\Models\Writers::orderBy('name_'.$lang,'asc')->get();
    foreach ($writers as $writer) {
        $list[$writer['id']] = $writer['name_'.$lang] != '' ? $writer['name_'.$lang] : $writer['name_ar'];
    }
    return $list;
}

function getPublishersList()
{
    $list = [];
    $publishers = App\Models\User::where('role','2')
                            ->where('block','0')
                            ->where('active','1')
                            ->orderBy('name','asc')->get();
    foreach ($publishers as $publisher) {
        $list[$publisher['id']] = $publisher['name'];
    }
    return $list;
}

function getSectionsList($lang)
{
    $list = [];
    $sections = App\Models\Sections::where('main_section','0')->orderBy('name_'.$lang,'asc')->get();
    foreach ($sections as $section) {
        $list[$section['id']] = $section['name_'.$lang];
        if ($section->subSections != '') {
            foreach ($section->subSections as $key => $value) {
                $list[$value['id']] = ' - '.$value['name_'.$lang];
            }
        }
    }
    return $list;
}

function getSettingValue($key)
{
    $value = '';
    $setting = App\Models\Settings::where('key',$key)->first();
    if ($setting != '') {
        $value = $setting['value'];
    }
    return $value;
}

function getSettingImageLink($key)
{
    $link = '';
    $setting = App\Models\Settings::where('key',$key)->first();
    if ($setting != '') {
        if ($setting['value'] != '') {
            $link = asset('uploads/settings/'.$setting['value']);
        }
    }
    return $link;
}

function getSettingImageValue($key)
{
    $value = '';
    if (getSettingImageLink($key) != '') {
        $value .= '<div class="row"><div class="col-12">';
        $value .= '<span class="avatar mb-2">';
        $value .= '<img class="round" src="'.getSettingImageLink($key).'" alt="avatar" height="90" width="90">';
        $value .= '</span>';
        $value .= '</div>';
        $value .= '<div class="col-12">';
        $value .= '<a href="'.route('admin.settings.deletePhoto',['key'=>$key]).'"';
        $value .= ' class="btn btn-danger btn-sm">'.trans("common.delete").'</a>';
        $value .= '</div></div>';
    }
    return $value;
}

function checkUserForApi($lang, $user_id)
{
    if ($lang == '') {
        $resArr = [
            'status' => false,
            'message' => trans('api.pleaseSendLangCode'),
            'data' => []
        ];
        return response()->json($resArr);
    }
    $user = App\Models\User::find($user_id);
    if ($user == '') {
        return response()->json([
            'status' => false,
            'message' => trans('api.thisUserDoesNotExist'),
            'data' => []
        ]);
    }

    return true;
}

function salesStatistics7Days()
{
    $date = \Carbon\Carbon::today()->subDays(7);
    $date7before = new \Carbon\Carbon($date);
    $date7before = $date7before->subDays(7);
    $ordersTotal = App\Models\Orders::where('created_at', '>=', $date)->sum('total');
    $ordersCount = App\Models\Orders::where('created_at', '>=', $date)->count();
    $ClientsCount = App\Models\User::where('role', '3')->where('created_at', '>=', $date)->count();
    $BooksCount = App\Models\Books::where('created_at', '>=', $date)->count();
    $orders7BeforeTotal = App\Models\Orders::where('created_at', '>=', $date7before)
                                    ->where('created_at', '<=', $date)->sum('total');
    if ($orders7BeforeTotal > 0) {
        $orders7BeforeAvg = (($ordersTotal - $orders7BeforeTotal) / $orders7BeforeTotal) * 100;
    } else {
        $orders7BeforeAvg = $ordersTotal;
    }

    return [
        'ordersCount' => number_format($ordersCount),
        'totalSales' => number_format($ordersTotal),
        'orders7BeforeTotal' => number_format($orders7BeforeTotal),
        'orders7BeforeAvg' => number_format($orders7BeforeAvg),
        'ClientsCount' => number_format($ClientsCount),
        'BooksCount' => number_format($BooksCount)
    ];
}

function getPermissions($role = null)
{
    $roleData = '';
    if ($role != null) {
        $roleData = App\Models\roles::find($role);
    }
    $permissions = [];
    $permissions['settings'] = [
        'name' => trans('common.setting'),
        'permissions' => []
    ];
    $settingPermissions = App\Models\permissions::where('group','settings')->get();
    foreach ($settingPermissions as $key => $value) {
        $hasIt = 0;
        if ($roleData != '') {
            if ($roleData->hasPermission($value['id']) == 1) {
                $hasIt = 1;
            }
        }
        $permissions['settings']['permissions'][] = [
            'id' => $value['id'],
            'name' => $value['name_'.session()->get('Lang')],
            'hasIt' => $hasIt
        ];
    }

    $permissions['admins'] = [
        'name' => trans('common.AdminUsers'),
        'permissions' => []
    ];
    $adminPermissions = App\Models\permissions::where('group','admins')->get();
    foreach ($adminPermissions as $key => $value) {
        $hasIt = 0;
        if ($roleData != '') {
            if ($roleData->hasPermission($value['id']) == 1) {
                $hasIt = 1;
            }
        }
        $permissions['admins']['permissions'][] = [
            'id' => $value['id'],
            'name' => $value['name_'.session()->get('Lang')],
            'hasIt' => $hasIt
        ];
    }

    $permissions['publishers'] = [
        'name' => trans('common.Publishers'),
        'permissions' => []
    ];
    $publisherPermissions = App\Models\permissions::where('group','publishers')->get();
    foreach ($publisherPermissions as $key => $value) {
        $hasIt = 0;
        if ($roleData != '') {
            if ($roleData->hasPermission($value['id']) == 1) {
                $hasIt = 1;
            }
        }
        $permissions['publishers']['permissions'][] = [
            'id' => $value['id'],
            'name' => $value['name_'.session()->get('Lang')],
            'hasIt' => $hasIt
        ];
    }

    $permissions['clients'] = [
        'name' => trans('common.Clients'),
        'permissions' => []
    ];
    $clientPermissions = App\Models\permissions::where('group','clients')->get();
    foreach ($clientPermissions as $key => $value) {
        $hasIt = 0;
        if ($roleData != '') {
            if ($roleData->hasPermission($value['id']) == 1) {
                $hasIt = 1;
            }
        }
        $permissions['clients']['permissions'][] = [
            'id' => $value['id'],
            'name' => $value['name_'.session()->get('Lang')],
            'hasIt' => $hasIt
        ];
    }

    $permissions['role'] = [
        'name' => trans('common.Roles'),
        'permissions' => []
    ];
    $rolePermissions = App\Models\permissions::where('group','roles')->get();
    foreach ($rolePermissions as $key => $value) {
        $hasIt = 0;
        if ($roleData != '') {
            if ($roleData->hasPermission($value['id']) == 1) {
                $hasIt = 1;
            }
        }
        $permissions['role']['permissions'][] = [
            'id' => $value['id'],
            'name' => $value['name_'.session()->get('Lang')],
            'hasIt' => $hasIt
        ];
    }

    $permissions['countries'] = [
        'name' => trans('common.Countries'),
        'permissions' => []
    ];
    $countryPermissions = App\Models\permissions::where('group','countries')->get();
    foreach ($countryPermissions as $key => $value) {
        $hasIt = 0;
        if ($roleData != '') {
            if ($roleData->hasPermission($value['id']) == 1) {
                $hasIt = 1;
            }
        }
        $permissions['countries']['permissions'][] = [
            'id' => $value['id'],
            'name' => $value['name_'.session()->get('Lang')],
            'hasIt' => $hasIt
        ];
    }

    $permissions['shippingLocales'] = [
        'name' => trans('common.shippingLocales'),
        'permissions' => []
    ];
    $shippingLocalePermissions = App\Models\permissions::where('group','shippingLocales')->get();
    foreach ($shippingLocalePermissions as $key => $value) {
        $hasIt = 0;
        if ($roleData != '') {
            if ($roleData->hasPermission($value['id']) == 1) {
                $hasIt = 1;
            }
        }
        $permissions['shippingLocales']['permissions'][] = [
            'id' => $value['id'],
            'name' => $value['name_'.session()->get('Lang')],
            'hasIt' => $hasIt
        ];
    }

    $permissions['currencies'] = [
        'name' => trans('common.currencies'),
        'permissions' => []
    ];
    $currencyPermissions = App\Models\permissions::where('group','currencies')->get();
    foreach ($currencyPermissions as $key => $value) {
        $hasIt = 0;
        if ($roleData != '') {
            if ($roleData->hasPermission($value['id']) == 1) {
                $hasIt = 1;
            }
        }
        $permissions['currencies']['permissions'][] = [
            'id' => $value['id'],
            'name' => $value['name_'.session()->get('Lang')],
            'hasIt' => $hasIt
        ];
    }

    $permissions['books'] = [
        'name' => trans('common.books'),
        'permissions' => []
    ];
    $bookPermissions = App\Models\permissions::where('group','books')->get();
    foreach ($bookPermissions as $key => $value) {
        $hasIt = 0;
        if ($roleData != '') {
            if ($roleData->hasPermission($value['id']) == 1) {
                $hasIt = 1;
            }
        }
        $permissions['books']['permissions'][] = [
            'id' => $value['id'],
            'name' => $value['name_'.session()->get('Lang')],
            'hasIt' => $hasIt
        ];
    }

    $permissions['sections'] = [
        'name' => trans('common.sections'),
        'permissions' => []
    ];
    $sectionPermissions = App\Models\permissions::where('group','sections')->get();
    foreach ($sectionPermissions as $key => $value) {
        $hasIt = 0;
        if ($roleData != '') {
            if ($roleData->hasPermission($value['id']) == 1) {
                $hasIt = 1;
            }
        }
        $permissions['sections']['permissions'][] = [
            'id' => $value['id'],
            'name' => $value['name_'.session()->get('Lang')],
            'hasIt' => $hasIt
        ];
    }

    $permissions['writers'] = [
        'name' => trans('common.writers'),
        'permissions' => []
    ];
    $writerPermissions = App\Models\permissions::where('group','writers')->get();
    foreach ($writerPermissions as $key => $value) {
        $hasIt = 0;
        if ($roleData != '') {
            if ($roleData->hasPermission($value['id']) == 1) {
                $hasIt = 1;
            }
        }
        $permissions['writers']['permissions'][] = [
            'id' => $value['id'],
            'name' => $value['name_'.session()->get('Lang')],
            'hasIt' => $hasIt
        ];
    }

    $permissions['orders'] = [
        'name' => trans('common.orders'),
        'permissions' => []
    ];
    $orderPermissions = App\Models\permissions::where('group','orders')->get();
    foreach ($orderPermissions as $key => $value) {
        $hasIt = 0;
        if ($roleData != '') {
            if ($roleData->hasPermission($value['id']) == 1) {
                $hasIt = 1;
            }
        }
        $permissions['orders']['permissions'][] = [
            'id' => $value['id'],
            'name' => $value['name_'.session()->get('Lang')],
            'hasIt' => $hasIt
        ];
    }

    $permissions['faqs'] = [
        'name' => trans('common.FAQs'),
        'permissions' => []
    ];
    $faqPermissions = App\Models\permissions::where('group','faqs')->get();
    foreach ($faqPermissions as $key => $value) {
        $hasIt = 0;
        if ($roleData != '') {
            if ($roleData->hasPermission($value['id']) == 1) {
                $hasIt = 1;
            }
        }
        $permissions['faqs']['permissions'][] = [
            'id' => $value['id'],
            'name' => $value['name_'.session()->get('Lang')],
            'hasIt' => $hasIt
        ];
    }

    $permissions['pages'] = [
        'name' => trans('common.pages'),
        'permissions' => []
    ];
    $pagePermissions = App\Models\permissions::where('group','pages')->get();
    foreach ($pagePermissions as $key => $value) {
        $hasIt = 0;
        if ($roleData != '') {
            if ($roleData->hasPermission($value['id']) == 1) {
                $hasIt = 1;
            }
        }
        $permissions['pages']['permissions'][] = [
            'id' => $value['id'],
            'name' => $value['name_'.session()->get('Lang')],
            'hasIt' => $hasIt
        ];
    }

    $permissions['contactMessages'] = [
        'name' => trans('common.contactMessages'),
        'permissions' => []
    ];
    $contactmessagePermissions = App\Models\permissions::where('group','contactMessages')->get();
    foreach ($contactmessagePermissions as $key => $value) {
        $hasIt = 0;
        if ($roleData != '') {
            if ($roleData->hasPermission($value['id']) == 1) {
                $hasIt = 1;
            }
        }
        $permissions['contactMessages']['permissions'][] = [
            'id' => $value['id'],
            'name' => $value['name_'.session()->get('Lang')],
            'hasIt' => $hasIt
        ];
    }
    return $permissions;
}

function messageSubjects($lang)
{
    $list = [
        'ar' => [
            [
                'id' => 'question',
                'name' => 'استفسار'
            ],
            [
                'id' => 'suggest',
                'name' => 'اقتراح'
            ],
            [
                'id' => 'request',
                'name' => 'طلب'
            ],
            [
                'id' => 'complaint',
                'name' => 'شكوى'
            ]
        ],
        'en' => [
            [
                'id' => 'question',
                'name' => 'question'
            ],
            [
                'id' => 'suggest',
                'name' => 'suggest'
            ],
            [
                'id' => 'request',
                'name' => 'request'
            ],
            [
                'id' => 'complaint',
                'name' => 'complaint'
            ]
        ],
        'fr' => [
            [
                'id' => 'question',
                'name' => 'question'
            ],
            [
                'id' => 'suggest',
                'name' => 'suggest'
            ],
            [
                'id' => 'request',
                'name' => 'request'
            ],
            [
                'id' => 'complaint',
                'name' => 'complaint'
            ]
        ]
    ];
    return $list[$lang];
}

function messageSubjectsList($lang)
{
    $list = [];
    foreach (messageSubjects($lang) as $key => $value) {
        $list[$value['id']] = $value['name'];
    }
    return $list;
}

function getUserCountryData()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
        $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
        $ip=$_SERVER['REMOTE_ADDR'];
    }


    $xml = simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=".$ip);
    $country = App\Models\Countries::where('iso',$xml->geoplugin_countryCode)->first();
    //return $xml;
    $resArr = [
        'countryCode' => $country != '' ? $country['iso'] : 'UA',
        'countryId' => $country != '' ? $country['id'] : 224
    ];
    return $resArr;

}

function addToCart($user_id,$items)
{
    $user = App\Models\User::find($user_id);
    if ($user == '') {
        return [];
    }

    $dateTime = date('Y-m-d H:i:s');

    if ($user->cart() == '') {
        $order = App\Models\Orders::create([
            'user_id' => $user_id,
            'date_time' => $dateTime,
            'date_time_str' => strtotime($dateTime),
            'status' => 0
        ]);
        $orders = [];
    } else {
        $order = $user->cart();
        $orders = $user->cart()->subOrders;
    }


    foreach ($items as $key => $value) {
        $bookDetails = App\Models\Books::find($value['book_id']);
        if ($bookDetails != '') {
            $oldSubOrder = $order->subOrders()->where('publisher_id',$bookDetails['publisher_id'])->first();
            // array_search($bookDetails['publisher_id'], array_column($orders, 'publisher_id'));
            if ($oldSubOrder == '') {
                $subOrder = App\Models\Orders::create([
                    'publisher_id' => $bookDetails['publisher_id'],
                    'main_order_id' => $order->id,
                    'user_id' => $user_id,
                    'date_time' => $dateTime,
                    'date_time_str' => strtotime($dateTime),
                    'total' => $value['quntity'] * $value['price'],
                    'net_total' => ($value['quntity'] * $value['price']),
                    'status' => 'draft'
                ]);
            } else {
                $subOrder = App\Models\Orders::find($oldSubOrder['id']);
                $update = $subOrder->update([
                    'total' => $value['quntity'] * $value['price'] + $oldSubOrder['net_total'],
                    'net_total' => ($value['quntity'] * $value['price']) + $oldSubOrder['net_total']
                ]);
            }
            $oldItem = App\Models\OrderItems::where('user_id',$user_id)
                                        ->where('order_id',$subOrder->id)
                                        ->where('book_type',$value['book_type'])
                                        ->where('book_id',$value['book_id'])
                                        ->first();
            if ($oldItem == '') {
                $item = App\Models\OrderItems::create([
                    'publisher_id' => $bookDetails['publisher_id'],
                    'user_id' => $user_id,
                    'order_id' => $subOrder->id,
                    'book_id' => $value['book_id'],
                    'book_type' => $value['book_type'],
                    'price' => $value['price'],
                    'quantity' => $value['quntity'],
                    'total' => $value['quntity'] * $value['price']
                ]);
                if ($item['book_type'] == 'hardcopy') {
                    if ($item->book->weight != '') {
                        $item->update(['unit_weight'=>$item->book->weight]);
                    }
                }
            }
        }
    }

    $order->update([
        'total' => $user->cart()->subOrders()->sum('total')
    ]);

}

function checkForCoupon($order_id,$coupon)
{
    $data = '';
    $CouponDetails = App\Models\Coupons::where('coupon',$coupon)->first();
    if ($CouponDetails != '') {
        if ($CouponDetails->canUse($order_id) != '0') {
            $order = App\Models\Orders::find($order_id)->update([
                'coupun_id' => $CouponDetails['id'],
                'coupun_code' => $coupon
            ]);
            $data = '1';
        }
    }
    return $data;
}

function payForOrder($paymentMethod,$user,$lang = 'ar')
{
    // return $user->cart()->apiData($lang)['netTotal'];
    if ($user->cart() != '') {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        try {
            $token = $stripe->tokens->create([
                'card' => [
                    'number' => $paymentMethod['card_number'],
                    'exp_month' => $paymentMethod['card_month'],
                    'exp_year' => $paymentMethod['card_year'],
                    'cvc' => $paymentMethod['card_cvv'],
                ]
            ]);

            if (!isset($token['id'])) {
                return [
                    'status' => 'faild',
                    'data'=>trans('api.yourPaymentProccessFaild')
                ];
            }
            $charge = Stripe\Charge::create ([
                "amount" => $user->cart()->apiData($lang)['netTotal'],
                "currency" => "aed",
                "source" => $token['id'],
                "description" => "payment for order #".$user->cart()['id']
            ]);

            if($charge['status'] == 'succeeded') {
                return $charge;
            } else {
                return [
                    'status' => 'faild',
                    'data'=>$charge
                ];
            }
        } catch (Exception $e) {
            return [
                'status' => 'faild',
                'data'=>$e->getMessage()
            ];
        } catch(\Cartalyst\Stripe\Exception\CardErrorException $e) {
            return [
                'status' => 'faild',
                'data'=>$e->getMessage()
            ];
        } catch(\Cartalyst\Stripe\Exception\MissingParameterException $e) {
            return [
                'status' => 'faild',
                'data'=>$e->getMessage()
            ];
        }
    }
    return '';
}
function createNewWriter($writer_name)
{
    $writer_id = 0;
    $writer = App\Models\Writers::where('name_ar',$writer_name)->first();
    if ($writer_name != '') {
        if ($writer == '') {
            $writer = App\Models\Writers::create([
                'name_ar' => $writer_name
            ]);
        }
        $writer_id = $writer->id;
    }
    return $writer_id;
}
