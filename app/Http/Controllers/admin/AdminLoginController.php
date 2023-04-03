<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminLoginController extends Controller
{
  // public function login(Request $request)
  // {

  //   $input = $request->all();
  //   $this->validate($request, [
  //     'email' => 'required',
  //     'password' => 'required'
  //   ]);

  //   if (auth()->attempt(['email' => $input['email'], 'password' => $input['password']])) {
  //     if (auth()->user()->checkActive() != '1') {
  //       session()->put('faild', auth()->user()->checkActive());
  //       auth()->logout();
  //       return redirect()->back()->withInput();
  //     }
  //     if (auth()->user()->role == '1') {
  //       return redirect()->route('admin.index');
  //     }
  //   } else {
  //     session()->put('faild', trans('auth.failed'));
  //     return redirect()->back()->withInput();
  //   }
  // }
  public function showLoginForm()
  {
    $title = trans('common.Sign in');
    return view('AdminPanel.auth.login', [
      'active' => '',
    ], compact('title'));
  }
  protected function loggedOut(Request $request)
  {
    return redirect()->route('login');
  }
}
