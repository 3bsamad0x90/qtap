<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\users\CreateUser;
use App\Http\Resources\UserResource;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthinticationController extends Controller
{

    public function register(Request $request)
    {
        $lang = $request->header('lang');
        if ($lang == '') {
            $resArr = [
                'status' => 'failed',
                'message' => trans('api.pleaseSendLangCode'),
                'data' => []
            ];
            return response()->json($resArr);
        }
        try {
            $validateUser = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
                'password' => 'required',
                'confirm_password' => 'required|same:password',
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => trans('api.validationError'),
                    'errors' => $validateUser->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'language' => in_array($lang,['ar','en']) ? $lang : 'en',
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'user' => new UserResource($user),
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }



    public function login(Request $request)
    {
        $lang = $request->header('lang');
        if ($lang == '') {
            $resArr = [
                'status' => 'faild',
                'message' => trans('api.pleaseSendLangCode'),
                'data' => []
            ];
            return response()->json($resArr);
        }

        try {
            $validateUser = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $user = User::where('email', $request->email)->first();
            $user->update([
                'language' => in_array($lang,['ar','en']) ? $lang : 'ar',
            ]);


            return response()->json([
                'status' => true,
                'message' => trans('api.UserLoggedInSuccessfully'),
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'expires_at' => now()->addDays(7),
                // 'user_id' => $user->id,
                'user' => new UserResource($user),
            ], Response::HTTP_OK);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => trans('api.UserLoggedOutSuccessfully')
        ], Response::HTTP_OK);
    }

    //change password
    public function changePassword(Request $request){
        $lang = $request->header('lang');
        if ($lang == '') {
            $resArr = [
                'status' => 'faild',
                'message' => trans('api.pleaseSendLangCode'),
                'data' => []
            ];
            return response()->json($resArr);
        }
        $request->validate([
            'password' => ['required'],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);
        if (!Hash::check($request->password, auth()->user()->password)) {
            return response()->json([
                'status' => false,
                'message' => trans('api.PasswordDoesNotMatchYourCurrentPassword')
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        // return $request->all();

        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
        return response()->json([
            'status' => true,
            'message' => trans('api.PasswordChangedSuccessfully')
        ], Response::HTTP_OK);
    }

}
