<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductsResource;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductsController extends Controller
{
    public function products(Request $request){
        $lang = $request->header('lang');
        if ($lang == '') {
            $resArr = [
                'status' => false,
                'message' => trans('api.pleaseSendLangCode'),
                'data' => []
            ];
            return response()->json($resArr);
        }
        $products = Products::orderBy('id', 'asc');
        // if (isset($_GET['price'])) {
        //   $price = [];
        //   foreach ($_GET['price'] as $key => $value) {
        //     $price[] = $value;
        //   }
        //   $products = $products->whereIn('price', $price);
        // }
        if (isset($_GET['price'])) {
          if ($_GET['price'] != '') {
            $products = $products->where('price', $_GET['price']);
          }
        }
        if(isset($_GET['type'])){
          if($_GET['type'] != ''){
              $products = $products->where('type', $_GET['type']);
          }
        }
        $products = $products->get();
        if($products){
            $resArr = [
                'status' => true,
                'data' => ProductsResource::collection($products),
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
    public function productDetails(Request $request, Products $product){
        $lang = $request->header('lang');
        if ($lang == '') {
            $resArr = [
                'status' => false,
                'message' => trans('api.pleaseSendLangCode'),
                'data' => []
            ];
            return response()->json($resArr);
        }
        if($product){
            $resArr = [
                'status' => true,
                'data' => new ProductsResource($product),
            ];
            return response()->json($resArr, Response::HTTP_OK);
        }else{
            $resArr = [
                'status' => false,
                'data' => []
            ];
            return response()->json($resArr, Response::HTTP_NOT_FOUND);
        }
    }
    public function review(Request $request, Products $product){
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
        'review' => 'required',
      ]);
      $data = $request->except('_token');
      $product['review'] = $data['review'];
      $review = $product->update();
      if ($review) {
        $resArr = [
          'status' => true,
          'data' => new ProductsResource($product),
        ];
        return response()->json($resArr, Response::HTTP_OK);
      } else {
        $resArr = [
          'status' => false,
          'data' => []
        ];
        return response()->json($resArr, Response::HTTP_NOT_FOUND);
      }
    }
}
