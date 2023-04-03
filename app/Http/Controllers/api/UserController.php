<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\UserPaymentMethods;
use App\Models\UserFavorites;
use App\Models\UserAddress;
use App\Models\User;
use Auth;
use Response;

class UserController extends Controller
{
    //
    public function deleteAccount(Request $request)
    {
        $lang = $request->header('lang');
        $user_id = $request->header('user');
        if ($lang == '') {
            $resArr = [
                'status' => 'faild',
                'message' => trans('api.pleaseSendLangCode'),
                'data' => []
            ];
            return response()->json($resArr);
        }

        $user = User::find($user_id);
        if ($user == '') {
            return response()->json([
                'status' => 'faild',
                'message' => trans('api.thisUserDoesNotExist'),
                'data' => []
            ]);
        }
        $user->delete();
        $user->bookReviews()->delete();
        $resArr = [
            'status' => 'success',
            'message' => trans('api.yourAccountHasBeenDeleted'),
            'data' => []
        ];
        return response()->json($resArr);
    }
    public function myProfile(Request $request)
    {
        $lang = $request->header('lang');
        $user = auth()->user();
        if ($lang == '') {
            $resArr = [
                'status' => false,
                'message' => trans('api.pleaseSendLangCode'),
                'data' => []
            ];
            return response()->json($resArr);
        }
        $user = User::find($user->id);
        if ($user == '') {
            return response()->json([
                'status' => false,
                'message' => trans('api.thisUserDoesNotExist'),
                'data' => []
            ]);
        }
        $resArr = [
            'status' => true,
            'data' => $user->apiData($lang)
        ];
        return response()->json($resArr);
    }
    public function UpdateMyProfile(Request $request)
    {
        $lang = $request->header('lang');
        $user = auth()->user();
        if (checkUserForApi($lang, $user->id) !== true) {
            return checkUserForApi($lang, $user->id);
        }
        $rules = [
                    'name' => 'nullable|string',
                    'last_name' => 'nullable|string',
                    'email' => 'nullable|email|unique:users,email,'.$user->id,
                    'phone' => 'nullable|unique:users,phone,'.$user->id,
                    'country' => 'nullable|string',
                    'city' => 'nullable|string',
                    'job_title' => 'nullable',
                ];
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            foreach ((array)$validator->errors() as $error) {
                return response()->json([
                    'status' => false,
                    'message' => trans('api.pleaseRecheckYourDetails'),
                    'data' => $error
                ]);
            }
        }

        $data = $request->except(['_token']);
        $user = User::find($user->id);
        if ($user->update($data)) {
            $resArr = [
                'status' => true,
                'message' => trans('api.yourDataHasBeenSavedSuccessfully'),
                'data' => $user->apiData($lang)
            ];
        } else {
            $resArr = [
                'status' => false,
                'message' => trans('api.someThingWentWrong'),
                'data' => []
            ];
        }
        return response()->json($resArr);

    }

}
