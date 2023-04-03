<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\users\CreateUser;
use App\Models\User;
use Response;

class ClientUsersController extends Controller
{
    //
    public function index()
    {
        $users = User::where('role','!=','1')->orderBy('id','desc')->paginate(25);
        return view('AdminPanel.clients.index',[
            'active' => 'clientUsers',
            'title' => trans('common.Clients'),
            'users' => $users,
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.Clients')
                ]
            ]
        ]);
    }

    public function blockAction($id,$action)
    {
        $update = User::find(auth()->user()->id)->update(['block'=>$action]);
        if ($update) {
            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }
    }

    public function create()
    {
        return view('AdminPanel.clients.create',[
            'active' => 'clientUsers',
            'title' => trans('common.Clients'),
            'breadcrumbs' => [
                                [
                                    'url' => route('admin.clientUsers'),
                                    'text' => trans('common.Clients')
                                ],
                                [
                                    'url' => '',
                                    'text' => trans('common.CreateNew')
                                ]
                            ]
        ]);
    }

    public function store(CreateUser $request)
    {
        $data = $request->except(['_token','photo','password','hidden']);
        if ($request->photo != '') {
            $data['photo'] = upload_image_without_resize('users/'.auth()->user()->id , $request->photo );
        }
        $data['password'] = bcrypt($request['password']);
        $data['role'] = '3';

        $user = User::create($data);
        if ($user) {
            return redirect()->route('admin.clientUsers')
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }

    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('AdminPanel.clients.edit',[
            'active' => 'clientUsers',
            'title' => trans('common.Clients'),
            'user' => $user,
            'breadcrumbs' => [
                                [
                                    'url' => route('admin.clientUsers'),
                                    'text' => trans('common.Clients')
                                ],
                                [
                                    'url' => '',
                                    'text' => trans('common.Edit').': '.$user->name
                                ]
                            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $data = $request->except(['_token','photo','password','hidden']);
        // return $data;
        if ($request->photo != '') {
            if ($user->photo != '') {
                delete_image('users/'.$id , $user->photo);
            }
            $data['photo'] = upload_image_without_resize('users/'.$id , $request->photo );
        }
        if ($request['password'] != '') {
            $data['password'] = bcrypt($request['password']);
        }

        $update = User::find($id)->update($data);
        if ($update) {
            return redirect()->route('admin.clientUsers')
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }

    }

    public function delete($id)
    {
        $user = User::find($id);
        if ($user->delete()) {
            $user->bookReviews()->delete();
            return Response::json($id);
        }
        return Response::json("false");
    }

}
