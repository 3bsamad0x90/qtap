<?php

namespace App\Http\Controllers\publishers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Writers;
use App\Books;
use Response;
use App\Imports\WritersImport;
use Maatwebsite\Excel\Facades\Excel;

class WritersController extends Controller
{
    public function index()
    {
        $writers = Writers::orderBy('name_'.session()->get('Lang'),'desc')->paginate(25);
        return view('PublisherPanel.writers.index',[
            'active' => 'writers',
            'title' => trans('common.writers'),
            'writers' => $writers,
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.writers')
                ]
            ]
        ]);
    }

    public function books($id)
    {
        $books = Books::where('writer_id',$id)->where('publisher_id',auth()->user()->id)->orderBy('id','desc')->paginate(25);
        return view('PublisherPanel.books.index',[
            'title' => trans('common.books'),
            'active' => 'books',
            'books' => $books,
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.books')
                ]
            ]
        ]);
    }
    public function store(Request $request)
    {
        $rules = [
            'name_ar' => 'required',
            'name_en' => 'required'
        ];
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return redirect()->back()->withError()->withInput();
        }

        $data = $request->except(['_token','photo']);
        $writer = Writers::create($data);
        if ($request->photo != '') {
            $data['photo'] = upload_image_without_resize('writers/'.$writer->id , $request->photo );
            $writer->update($data);
        }
        $data['active'] = 0;


        $notificationText = notificationTextTranslate([
                                'name' => auth()->user()->name,
                                'type' => 'newWriter'
                            ],
                            'ar');
        $notificationData = [
            'type' => 'newWriter',
            'linked_id' => $writer->id,
            'text' => $notificationText,
            'date' => date('Y-m-d'),
            'time' => date('H:i')
        ];
        notifyManagers('newWriter',$notificationData);

        if ($writer) {
            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }
        
    }
    
    public function storeExcel(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|file'
        ]);
        Excel::import(new WritersImport(auth()->user()->id), request()->file);
        // Excel::import(new ClientsImport($branch_id,$user_id), request()->excel('File'));

        $request->session()->put('PopSuccess', trans('Site.SavedSuccessfully'));
        return back();
    }

}
