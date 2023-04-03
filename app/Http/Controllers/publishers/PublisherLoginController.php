<?php

namespace App\Http\Controllers\publishers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\RegisterPublisher;
use App\User;
use Response;
use Auth;

class PublisherLoginController extends Controller
{
    //
    public function login()
    {
        return view('PublisherPanel.auth.login',[
            'active' => '',
            'title' => trans('common.Sign in')
        ]);
    }
    public function register()
    {
        return view('PublisherPanel.auth.register',[
            'active' => '',
            'title' => trans('common.register')
        ]);
    }
    
    public function submitRegister(RegisterPublisher $request)
    {
        $data = $request->except(['_token','password','licensePhoto']);
        $data['password'] = bcrypt($request['password']);
        $data['role'] = '2';
        $data['active'] = 0;
        // return $data;

        $user = User::create($data);
        if ($request->licensePhoto != '') {
            $user['licensePhoto'] = upload_image_without_resize('users/'.$user->id , $request->licensePhoto );
            $user->update();
        }

        $notificationText = notificationTextTranslate([
                                        'name' => $user['name'],
                                        'type' => 'newPublisher'
                                    ],
                                    'ar');
        $notificationData = [
            'type' => 'newPublisher',
            'linked_id' => $user->id,
            'text' => $notificationText,
            'date' => date('Y-m-d'),
            'time' => date('H:i')
        ];
        notifyManagers('newPublisher',$notificationData);
        
        if ($user) {
            return redirect()->back()
                            ->with('success',trans('common.yourDataHasBeenSentSuccessfully,WeWillContactYouToActivateYourAccountAsSoonAsPossible'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }
        
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('publisher.login');
    }
}
