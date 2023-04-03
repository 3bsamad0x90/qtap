<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Countries;
use App\Governorates;
use Response;

class GovernoratesController extends Controller
{
    public function index($countryId)
    {
        $country = Countries::find($countryId);
        $governorates = Governorates::where('country',$countryId)->orderBy('name_'.session()->get('Lang'),'desc')->paginate(25);
        return view('AdminPanel.governorates.index',[
            'active' => 'governorates',
            'title' => trans('common.governorates'),
            'governorates' => $governorates,
            'country' => $country,
            'breadcrumbs' => [
                [
                    'url' => route('admin.countries'),
                    'text' => trans('common.Countries')
                ],
                [
                    'url' => '',
                    'text' => $country['name_'.session()->get('Lang')] != '' ? $country['name_'.session()->get('Lang')] : $country['name_en']
                ],
                [
                    'url' => '',
                    'text' => trans('common.governorates')
                ]
            ]
        ]);
    }

    public function store(Request $request,$countryId)
    {
        $data = $request->except(['_token']);
        $data['country'] = $countryId;
        $governorate = Governorates::create($data);
        if ($governorate) {
            return redirect()->route('admin.governorates',['countryId'=>$countryId])
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }
        
    }

    public function update(Request $request, $countryId, $governorateId)
    {
        $data = $request->except(['_token']);

        $update = Governorates::find($governorateId)->update($data);
        if ($update) {
            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }
        
    }

    public function delete($countryId, $governorateId)
    {
        $governorate = Governorates::find($governorateId);
        if ($governorate->delete()) {
            return Response::json($governorateId);
        }
        return Response::json("false");
    }
}
