<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Pages;
use Response;

class PagesController extends Controller
{
    public function index()
    {
        $pages = Pages::orderBy('id','desc')->paginate(25);
        return view('AdminPanel.pages.index',[
            'active' => 'pages',
            'title' => trans('common.pages'),
            'pages' => $pages,
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.pages')
                ]
            ]
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'title_ar' => 'required',
            'content_ar' => 'required'
        ];
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)
                            ->with('faild',trans('api.pleaseRecheckYourDetails'));
        }

        $data = $request->except(['_token']);

        $page = Pages::create($data);
        if ($page) {
            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }

    }

    public function update(Request $request, $id)
    {
        $page = Pages::find($id);
        $data = $request->except(['_token']);

        $update = Pages::find($id)->update($data);
        if ($update) {
            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }

    }

    public function delete($id)
    {
        $page = Pages::find($id);
        if ($page->delete()) {
            return Response::json($id);
        }
        return Response::json("false");
    }
}
