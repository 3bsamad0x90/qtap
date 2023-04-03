<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Countries;
use Response;

class CountriesController extends Controller
{
    //
    public function index()
    {
        $countries = Countries::orderBy('name_'.session()->get('Lang'),'desc')->paginate(25);
        return view('AdminPanel.countries.index',[
            'active' => 'countries',
            'title' => trans('common.Countries'),
            'countries' => $countries,
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.Countries')
                ]
            ]
        ]);
    }

    public function blockAction($id,$action)
    {
        $update = Countries::find(auth()->user()->id)->update(['block'=>$action]);
        if ($update) {
            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }
    }

    public function store(Request $request)
    {
        $data = $request->except(['_token']);

        $country = Countries::create($data);
        if ($country) {
            return redirect()->route('admin.Countries')
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }
        
    }

    public function update(Request $request, $id)
    {
        $user = Countries::find($id);
        $data = $request->except(['_token']);

        $update = Countries::find($id)->update($data);
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
        $user = Countries::find($id);
        if ($user->delete()) {
            return Response::json($id);
        }
        return Response::json("false");
    }


}
