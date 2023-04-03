<?php

namespace App\Http\Controllers\publishers;

use App\Http\Controllers\Controller;
use App\PublisherPaymentMethods;
use App\User;
use Illuminate\Http\Request;
use Response;

class Payment_methodController extends Controller{

    public function store(Request $request){

        $data = $request->except(['_token','photo']);

        $data['user_id'] = auth()->user()->id;
        if (!isset($data['primary'])) {
            $data['primary'] = '0';
        } else {
            $thisUserMethods = PublisherPaymentMethods::where('user_id',auth()->user()->id)
                                                        ->update([
                                                            'primary' => '0'
                                                        ]);
        }
        $payment = PublisherPaymentMethods::create($data);
        if ($payment) {
            return redirect()->route('publisher.payment_methods')
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }
    }

    public function index(){

        $payment_methods = PublisherPaymentMethods::where('user_id',auth()->user()->id)->get();
        return view('PublisherPanel.loggedinUser.payment_method', [
            'title' => trans('common.payment'),
            'active' => 'payment',
            'payment_methods' => $payment_methods,
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.payment')
                ]
            ]
        ]);
    }

    public function delete($id){
        $payment_methods = PublisherPaymentMethods::find($id);
        if ($payment_methods->delete()) {
            return Response::json($id);
        }
        return Response::json("false");
    }


    public function update(Request $request, $id){

        $data = $request->except(['_token']);
        if (!isset($data['primary'])) {
            $data['primary'] = '0';
        } else {
            $thisUserMethods = PublisherPaymentMethods::where('user_id',auth()->user()->id)
                                                        ->update([
                                                            'primary' => '0'
                                                        ]);
        }
        $update = PublisherPaymentMethods::find($id)->update($data);
        if ($update) {
            return redirect()->route('publisher.payment_methods')
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
    }
}
}