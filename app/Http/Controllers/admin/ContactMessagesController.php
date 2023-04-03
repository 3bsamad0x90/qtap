<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ContactMessages;
use Response;

class ContactMessagesController extends Controller
{
    public function index()
    {
        $messages = ContactMessages::orderBy('status','asc')->orderByDesc('created_at')->paginate(20);
        return view('AdminPanel.contactMessages.index',[
            'active' => 'contactMessages',
            'title' => trans('common.contactMessages'),
            'messages' => $messages,
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.contactMessages')
                ]
            ]
        ]);
    }

    public function details($id)
    {
        $message = ContactMessages::find($id);
        $message->update(['status'=>'1']);
        if ($message == '') {
            return redirect()->route('admin.contactmessages');
        }
        $route = route('admin.contactmessages');
        $title = trans('common.contactMessages');
        $active = 'contactMessages';
        if ($message->user_type == 'publisher') {
            $route = route('admin.publishersContactMessages');
            $title = trans('common.publishersContactMessages');
            $active = 'publishersContactMessages';
        }
        return view('AdminPanel.contactMessages.details',[
            'active' => $active,
            'title' => $title,
            'message' => $message,
            'breadcrumbs' => [
                [
                    'url' => $route,
                    'text' => $title
                ],
                [
                    'url' => '',
                    'text' => trans('common.messageDetails')
                ]
            ]
        ]);
    }

    public function delete($id)
    {
        $message = ContactMessages::find($id);
        if ($message->delete()) {
            return Response::json($id);
        }
        return Response::json("false");
    }
}
