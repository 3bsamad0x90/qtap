<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\Products;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller
{
  public function addToCart(Request $request)
  {
    $lang = $request->header('lang');
    $user = auth()->user();

    if (checkUserForApi($lang, $user->id) !== true) {
      return checkUserForApi($lang, $user->id);
    }
    $rules = [
      'product_id' => 'required',
      'price' => 'required',
      'quantity' => 'required',
    ];
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      foreach ((array)$validator->errors() as $error) {
        return response()->json([
          'status' => 'faild',
          'message' => 'Please Recheck Your Details',
          'data' => $error
        ]);
      }
    }
    $userOrder = Orders::where('user_id', $user->id)->first();
    if($userOrder){
      $cartItem = OrderItems::where('order_id', $userOrder->id)->where('product_id', $request->product_id)->first();
      if($cartItem){
        $cartItem->quantity += $request->quantity;
        $cartItem->total = $cartItem->quantity * $cartItem->price;
        $cartItem->save();
      }else{
          $dateTime = date('Y-m-d H:i:s');
          $order = Orders::create([
            'user_id' => $user->id,
            'date_time' => $dateTime,
            'date_time_str' => strtotime($dateTime),
            'total' => $request->price,
          ]);
        $orderItem = OrderItems::create([
          'order_id' => $order->id,
          'product_id' => $request->product_id,
          'quantity' => $request->quantity,
          'price' => $request->price,
          'total' => $request->price * $request->quantity,
        ]);
      }
    }else{
      $dateTime = date('Y-m-d H:i:s');
      $order = Orders::create([
        'user_id' => $user->id,
        'date_time' => $dateTime,
        'date_time_str' => strtotime($dateTime),
        'total' => $request->price,
      ]);
      $orderItem = OrderItems::create([
        'order_id' => $order->id,
        'product_id' => $request->product_id,
        'quantity' => $request->quantity,
        'price' => $request->price,
        'total' => $request->price * $request->quantity,
      ]);
    }

    return response()->json([
      'status' => 'success',
      'message' => 'Added To Cart Successfully',
    ]);
  }
  //Get Cart
  public function getCart(Request $request)
  {
    $lang = $request->header('lang');
    $user_id = $request->header('user');
    if (checkUserForApi($lang, $user_id) !== true) {
      return checkUserForApi($lang, $user_id);
    }
    $user = User::find($user_id);
    $list = Orders::where('user_id', $user_id)->orderBy('id', 'desc')->get();
    // return $list;
    $orders = [];
    foreach ($list as $key => $value) {
      $orders[] = $value->subOrders->map(function ($item) use ($lang) {
        return [
          'id' => $item->id,
          'product' => $item->product['title_' . $lang],
          'image' => asset('uploads/products/' . $item->product->id . '/' . $item->product->image),
          'price' => $item->price,
          'quantity' => $item->quantity,
          'total' => $item->total,
        ];
      });
    }

    $resArr = [
      'status' => true,
      'data' =>  $orders
    ];
    return response()->json($resArr);
  }

  public function updateCart(Request $request, $id)
  {
    $lang = $request->header('lang');
    $user = auth()->user();
    $cart = OrderItems::where('product_id', $id)->first();
    if (checkUserForApi($lang, $user->id) !== true) {
      return checkUserForApi($lang, $user->id);
    }

    if ($cart != '') {
      $quantity = $cart->quantity;
      if ($request->action == 'increase') {
        $quantity += 1;
      }else {
        if ($quantity > 1) {
          $quantity -= 1;
        }
      }
      $cart->update(['quantity' => $quantity, 'total' => $quantity * $cart->price]);
    }
    $resArr = [
      'status' => true,
      'message' => trans('api.CartUpdatedSuccessfully'),
      'data' => $cart->apiData($lang)
    ];
    return response()->json($resArr);
  }
  public function removeItem(Request $request, $id)
  {
      $lang = $request->header('lang');
      $user = auth()->user();
      $cart = OrderItems::where('product_id', $id)->first();
      if (checkUserForApi($lang, $user->id) !== true) {
        return checkUserForApi($lang, $user->id);
      }
      if ($cart != '') {
        $order = $cart->order;
        $cart->delete();
        if($order != ''){
          if($order->subOrders->count() == 0){
            $order->delete();
          }
        }
      }
      $resArr = [
        'status' => true,
        'message' => trans('api.CartUpdatedSuccessfully'),
        'data' => $user->myCart($lang)
      ];
      return response()->json($resArr);
  }

  public function clearCart(Request $request){
      $lang = $request->header('lang');
      $user = auth()->user();
      if (checkUserForApi($lang, $user->id) !== true) {
        return checkUserForApi($lang, $user->id);
      }
      $orders = Orders::where('user_id', $user->id)->get();
      if($orders != ''){
        foreach($orders as $order){
          $order->subOrders()->delete();
          $order->delete();
          return response()->json([
            'status' => true,
            'message' => trans('api.CartClearedSuccessfully'),
          ]);
        }
      }
        return response()->json([
          'status' => false,
          'message' => trans('api.CartIsEmpty'),
        ]);
  }
  public function myCart(Request $request){
    $lang = $request->header('lang');
    $user = auth()->user();
    if (checkUserForApi($lang, $user->id) !== true) {
      return checkUserForApi($lang, $user->id);
    }
    $userCart = Orders::where('user_id', $user->id)->get();
    $cart = [];
    foreach($userCart as $order){
      $cart[] = $order->items->map(function ($item) use ($lang) {
        return [
          'id' => $item->id,
          'product' => $item->product['title_' . $lang],
          'image' => asset('uploads/products/' . $item->product->id . '/' . $item->product->image),
          'price' => $item->price,
          'quantity' => $item->quantity,
          'total' => $item->total,
        ];
      });
    }
    return response()->json([
      'status' => true,
      'data' => $cart,
    ]);
  }

}
