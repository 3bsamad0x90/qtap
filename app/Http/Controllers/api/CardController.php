<?php

namespace App\Http\Controllers\api;

use App\Models\Cards;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CardController extends Controller
{
    public function create(Request $request){
        $lang = $request->header('lang');
        $user = auth()->user();
        if (checkUserForApi($lang, $user->id) !== true) {
            return checkUserForApi($lang,$user->id);
        }

        $rules = [
            'title' => 'required|string',
            'full_name' => 'required|string',
            'email' => 'required|email|unique:cards,email',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
            'website' => 'nullable|string|url|unique:cards,website',
            'language' => 'required|in:ar,en',
            'type' => 'required|string',
            'theme' => 'required|string',
          	'image' => 'nullable|image|mimes:jpeg,png,jpg,gif'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        // return substr($request->email, 0, strpos($request->email, '@'));
        $data = $request->except(['_token','slug']);
        $data['slug'] = substr($request->email, 0, strpos($request->email, '@'));
        $data['user_id'] = $user->id;
        $card = Cards::create($data);
        if($card){
            return response()->json([
                'status' => true,
                'data' => $card
            ], Response::HTTP_OK);
        }
        return response()->json([
            'status' => false,
            'data' => []
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    //Cards List For User
    public function userCards(Request $request){
        $lang = $request->header('lang');
        $user = auth()->user();
        if (checkUserForApi($lang, $user->id) !== true) {
            return checkUserForApi($lang,$user->id);
        }
        $cards = Cards::where('user_id',$user->id)->get();
        if($cards){
            return response()->json([
                'status' => true,
                'data' => $cards
            ], Response::HTTP_OK);
        }
        return response()->json([
            'status' => false,
            'data' => []
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    //Card Details For User By ID
    public function show(Request $response, Cards $card){
        $lang = $response->header('lang');
        $user = auth()->user();
        if (checkUserForApi($lang, $user->id) !== true) {
            return checkUserForApi($lang,$user->id);
        }
        $card = Cards::where('user_id',$user->id)->where('id',$card->id)->first();
        if($card){
            return response()->json([
                'status' => true,
                'data' => $card
            ], Response::HTTP_OK);
        }
        return response()->json([
            'status' => false,
            'data' => []
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    //Card Update For User By ID
    public function update(Request $request, Cards $card){
        $lang = $request->header('lang');
        $user = auth()->user();
        if (checkUserForApi($lang, $user->id) !== true) {
            return checkUserForApi($lang,$user->id);
        }
        $rules = [
            'title' => 'required|string',
            'full_name' => 'required|string',
            'email' => 'required|email|unique:cards,email,'.$card->id,
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
            'website' => 'nullable|string|url|unique:cards,website,'.$card->id,
            'language' => 'required|in:ar,en',
            'type' => 'required|string',
            'theme' => 'required|string',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $data = $request->except(['_token','slug']);
        $data['slug'] = substr($request->email, 0, strpos($request->email, '@'));
        $data['user_id'] = $user->id;
        $card->update($data);
        if($card){
            return response()->json([
                'status' => true,
                'data' => $card
            ], Response::HTTP_OK);
        }
        return response()->json([
            'status' => false,
            'data' => []
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
