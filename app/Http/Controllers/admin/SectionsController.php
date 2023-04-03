<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Sections;
use Response;

class SectionsController extends Controller
{
    public function index()
    {
        $sections = Sections::where('main_section','0')->orderBy('name_'.session()->get('Lang'),'desc')->paginate(25);
        return view('AdminPanel.sections.index',[
            'active' => 'sections',
            'title' => trans('common.sections'),
            'sections' => $sections,
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.sections')
                ]
            ]
        ]);
    }

    public function subindex($sectionId)
    {
        $mainSection = Sections::find($sectionId);
        $sections = Sections::where('main_section',$sectionId)->orderBy('name_'.session()->get('Lang'),'desc')->paginate(25);
        return view('AdminPanel.sections.subsindex',[
            'active' => 'sections',
            'title' => trans('common.SubSections'),
            'sections' => $sections,
            'mainSection' => $mainSection,
            'breadcrumbs' => [
                [
                    'url' => route('admin.sections'),
                    'text' => trans('common.sections')
                ],
                [
                    'url' => '',
                    'text' => $mainSection['name_'.session()->get('Lang')]
                ],
                [
                    'url' => '',
                    'text' => trans('common.SubSections')
                ]
            ]
        ]);
    }


    public function store(Request $request)
    {
        $data = $request->except(['_token']);

        $section = Sections::create($data);
        if ($section) {
            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }
        
    }

    public function substore(Request $request,$sectionId)
    {
        $data = $request->except(['_token']);
        $data['main_section'] = $sectionId;
        
        $section = Sections::create($data);
        if ($section) {
            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }
        
    }

    public function update(Request $request, $id)
    {
        $section = Sections::find($id);
        $data = $request->except(['_token']);

        $update = Sections::find($id)->update($data);
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
        $section = Sections::find($id);
        if ($section->delete()) {
            return Response::json($id);
        }
        return Response::json("false");
    }
}
