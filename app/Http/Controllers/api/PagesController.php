<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PagesResource;
use App\Models\Pages;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function termsConditions(Request $request){
        $lang = $request->header('lang');
        if ($lang == '') {
            $resArr = [
                'status' => false,
                'message' => trans('api.pleaseSendLangCode'),
                'data' => []
            ];
            return response()->json($resArr);
        }
        $termsConditions = Pages::all();
        if($termsConditions){
            $resArr = [
                'status' => true,
                'data' => PagesResource::collection($termsConditions)
            ];
            return response()->json($resArr);
        }else{
            $resArr = [
                'status' => false,
                'data' => []
            ];
            return response()->json($resArr);
        }
    }
}
