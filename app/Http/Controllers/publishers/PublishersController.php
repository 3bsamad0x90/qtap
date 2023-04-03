<?php

namespace App\Http\Controllers\publishers;

use App\Books;
use App\Http\Controllers\Controller;
use App\PublisherPaymentsHistory;
use App\User;
use App\FAQs;
use App\ContactMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Notifications\DatabaseNotification;


class PublishersController extends Controller
{
    
    public function index()
    {
        $numberofbook = Books::where('writer_id',auth()->user()->id)->count();

        return view('PublisherPanel.index',[
            'active' => 'panelHome',
            'title' => trans('common.Admin Panel'),
            'numberofbook'=>$numberofbook
        ]);
    }

    public function payment_method()
    {
        return view('PublisherPanel.loggedinUser.payment_method',[
            'active' => 'payment_method',
            'title' => ''
        ]);
    }

    public function payment_history()
    {

        $payment_histories = PublisherPaymentsHistory::all();
        return view('PublisherPanel.loggedinUser.payment_history',[
            'active' => 'payment_history',
            'title' => '',
            'payment_histories'=>$payment_histories
        ]);
    }

    public function EditProfile()
    {
        return view('PublisherPanel.loggedinUser.my-profile',[
            'active' => 'my-profile',
            'title' => trans('common.Profile'),
            'breadcrumbs' => [
                                [
                                    'url' => '',
                                    'text' => trans('common.Account')
                                ]
                            ]
        ]);
    }

    public function UpdateProfile(Request $request)
    {
        $data = $request->except(['_token','photo']);
      
        if ($request->photo != '') {
            if (auth()->user()->photo != '') {
                delete_image('users/'.auth()->user()->id , auth()->user()->photo);
            }
            $data['photo'] = upload_image_without_resize('users/'.auth()->user()->id , $request->photo );
        }

        $update = User::find(auth()->user()->id)->update($data);
        if ($update) {
            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }
        
    }

    public function EditPassword()
    {
        return view('PublisherPanel.loggedinUser.my-password',[
            'active' => 'my-password',
            'title' => trans('common.password'),
            'breadcrumbs' => [
                                [
                                    'url' => '',
                                    'text' => trans('common.Security')
                                ]
                            ]
        ]);
    }
    
    public function updatePassword(Request $request)
    {
        $data = $request->except(['_token','photo','password_confirmation']);
      
        $rules = [
                    'password' => 'required|confirmed',
                ];
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return redirect()->back()
                            ->withErrors($validator)
                            ->with('faild',trans('common.faildMessageText'));
        }
        $data['password'] = bcrypt($request['password']);

        $update = User::find(auth()->user()->id)->update($data);

        if ($update) {
            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }
        
    }

    public function notificationDetails($id)
    {
        $Notification = DatabaseNotification::find($id);
        $Notification->markAsRead();

        if (in_array($Notification['data']['type'], ['activatePublisherAccount'])) {
            return redirect()->route('publisher.index')
                                    ->with('success','نهنئك بتفعيل حسابك لدينا ابدأ الاستخدام بحرية');
        }

        return redirect()->back();
    }

    public function readAllNotifications()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }

    public function FAQs()
    {
        $faqs = FAQs::where('type','publisher')->orderBy('ranking','asc')->orderBy('id','desc')->paginate(25);
        return view('PublisherPanel.FAQs.index',[
            'active' => 'FAQs',
            'title' => trans('common.FAQs'),
            'faqs' => $faqs,
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.FAQs')
                ]
            ]
        ]);
    }

    public function contactUs()
    {
        return view('PublisherPanel.contactUs.index',[
            'active' => 'contactUs',
            'title' => trans('common.contactUs'),
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.contactUs')
                ]
            ]
        ]);
    }
    
    public function submitContactUs(Request $request)
    {      
        $data = $request->except(['_token']);
        $rules = [
                    'subject' => 'required',
                    'title' => 'required',
                    'content' => 'required',
                ];
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return redirect()->back()
                            ->withErrors($validator)
                            ->with('faild',trans('common.faildMessageText'));
        }
        $data['user_id'] = auth()->user()->id;
        $data['user_type'] = 'publisher';        

        $message = ContactMessages::create($data);
        $notificationText = notificationTextTranslate([
                                        'name' => auth()->user()->name,
                                        'type' => 'newPublisherMessage'
                                    ],
                                    'ar');
        $notificationData = [
            'type' => 'newPublisherMessage',
            'linked_id' => $message->id,
            'text' => $notificationText,
            'date' => date('Y-m-d'),
            'time' => date('H:i')
        ];
        notifyManagers('newPublisherMessage',$notificationData);

        if ($message) {
            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }
        
    }

}
