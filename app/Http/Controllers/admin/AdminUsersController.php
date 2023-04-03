<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\users\CreateUser;
use App\Models\User;
use Response;

class AdminUsersController extends Controller
{
    //
    public function index()
    {
        $users = User::where('role','1')->orderBy('id','desc')->paginate(25);
        return view('AdminPanel.admins.index',[
            'active' => 'adminUsers',
            'title' => trans('common.AdminUsers'),
            'users' => $users,
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.AdminUsers')
                ]
            ]
        ]);
    }

    public function blockAction($id,$action)
    {
        $update = User::find($id)->update(['block'=>$action]);
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
        return view('AdminPanel.admins.create',[
            'active' => 'adminUsers',
            'title' => trans('common.AdminUsers'),
            'breadcrumbs' => [
                                [
                                    'url' => route('admin.adminUsers'),
                                    'text' => trans('common.AdminUsers')
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
        $data['password'] = bcrypt($request['password']);

        $user = User::create($data);
        if ($request->photo != '') {
            $user['photo'] = upload_image_without_resize('users/'.$user->id , $request->photo );
            $user->update();
        }
        if ($user) {
            return redirect()->route('admin.adminUsers')
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }

    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('AdminPanel.admins.edit',[
            'active' => 'adminUsers',
            'title' => trans('common.AdminUsers'),
            'user' => $user,
            'breadcrumbs' => [
                [
                    'url' => route('admin.adminUsers'),
                    'text' => trans('common.AdminUsers')
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
            return redirect()->route('admin.adminUsers')
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
            return Response::json($id);
        }
        return Response::json("false");
    }


}
