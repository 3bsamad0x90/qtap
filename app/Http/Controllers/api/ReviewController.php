<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\Reviews;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReviewController extends Controller
{
    public function store(Request $request){
      $lang = $request->header('lang');
      if ($lang == '') {
        $resArr = [
          'status' => false,
          'message' => trans('api.pleaseSendLangCode'),
          'data' => []
        ];
        return response()->json($resArr);
      }
      $request->validate([
        'product_id' => 'required',
        'review' => 'required',
        'rating' => 'required',
      ]);
      $product = Products::find($request->product_id);
      if(!$product){
        return response()->json([
        'status' => false,
        'message' => trans('api.productNotFound'),

        ]);
      }
      if(!auth()->check()){
        return response()->json([
          'status' => false,
          'message' => trans('api.loginFirst'),

        ]);
      }
      $review = Reviews::create([
        'user_id' => auth()->user()->id,
        'product_id' => $request->product_id,
        'review' => $request->review,
        'rating' => $request->rating,
      ]);
      if($review){
        return response()->json([
          'status' => true,
          'message' => trans('api.reviewAddedSuccessfully'),
        ]);
      }else{
        return response()->json([
          'status' => false,
          'message' => trans('api.pleaseRecheckYourDetails'),
        ]);
      }
    }
}
