<?php

namespace App\Http\Controllers\publishers;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\StoreBook;
use Illuminate\Http\Request;
use App\Books;
use App\Sections;
use App\User;
use App\Writers;
use Response;
use Illuminate\Support\Facades\Validator;
use App\Imports\BooksImport;
use Maatwebsite\Excel\Facades\Excel;

class BooksController extends Controller
{
    
    public function index(Type $var = null)
    {

        $books = Books::where('publisher_id',auth()->user()->id)->paginate(20);
        return view('PublisherPanel.books.index', [
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

    public function create()
    {

        $writers = Writers::all()->pluck('name_'.session()->get('Lang'),'id')->all();
        $publishers = User::where('role','2')->pluck('name','id')->all();

        return view('PublisherPanel.books.create', [
            'title' => trans('common.books'),
            'active' => 'books',
            'writers' => $writers,
            'publishers' => $publishers,
            'breadcrumbs' => [
                [
                    'url' => route('publisher.books'),
                    'text' => trans('common.books')
                ],
                [
                    'url' => '',
                    'text' => trans('common.CreateNew')
                ]
            ]
        ]);
    }
    public function store(StoreBook $request)
    {
        $data = $request->except(['_token','backPage','photo']);
        $data['publisher_id'] = auth()->user()->id;

        if (getSettingValue('autoBooksPublishing') == 1) {
            $data['available'] = 1;
        }
        
        $book = Books::create($data);
        if ($request->photo != '') {
            $book['photo'] = upload_image_without_resize('books/'.$book->id , $request->photo );
            $book->update();
        }
        if ($request->backPage != '') {
            $book['backPage'] = upload_image_without_resize('books/'.$book->id , $request->backPage );
            $book->update();
        }
        if ($book) {
            return redirect()->route('publisher.books')
                ->with('success', trans('common.successMessageText'));
        } else {
            return redirect()->back()
                ->with('faild', trans('common.faildMessageText'));
        }
    }


    public function edit($id)
    {
        $book = Books::find($id);
        $writers = Writers::all();
        $publishers = User::where('role','2')->pluck('name','id')->all();
        return view('PublisherPanel.books.edit', [
            'active' => 'books',
            'title' => trans('common.books'),
            'book' => $book,
            'writers'=>$writers,
            'publishers'=>$publishers,
            'breadcrumbs' => [
                [
                    'url' => route('publisher.books'),
                    'text' => trans('common.books')
                ],
                [
                    'url' => '',
                    'text' => trans('common.edit') . ': ' . $book->name_ar
                ]
            ]
        ]);
    }


    public function update(Request $request, $id)
    {
        $update = Books::find($id);
        $data = $request->except(['_token','backPage','photo']);

        if ($request->photo != '') {
            if ($update->photo != '') {
                delete_image('books/'.$id , $update->photo);
            }
            $data['photo'] = upload_image_without_resize('books/'.$id , $request->photo );
        }
        if ($request->backPage != '') {
            if ($update->backPage != '') {
                delete_image('books/'.$id , $update->backPage);
            }
            $data['backPage'] = upload_image_without_resize('books/'.$id , $request->backPage );
        }
        if (!isset($data['hardCopy'])) {
            $data['hardCopy'] = '0';
        }
        if (!isset($data['pdfCopy'])) {
            $data['pdfCopy'] = '0';
        }
        if (!isset($data['available'])) {
            $data['available'] = '0';
        }

        if ($update->update($data)) {
            return redirect()->route('publisher.books')
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
        Excel::import(new BooksImport(auth()->user()->id), request()->file);
        // Excel::import(new ClientsImport($branch_id,$user_id), request()->excel('File'));

        $request->session()->put('PopSuccess', trans('Site.SavedSuccessfully'));
        return back();
    }


    public function delete($id)
    {

        $book = Books::find($id);
        delete_folder('books/'.$id);
        if ($book->delete()) {
            return Response::json($id);
        }
        return Response::json("false");
    }


    public function booksBulkDelete(Request $request)
    {
        if ($request['books'] == '') {
            return redirect()->route('publisher.books')
                            ->with('faild',trans('common.selectBooksFirst'));
        }
        foreach ($request['books'] as $key => $value) {
            $book = Books::find($value);
            if ($book != '') {
                delete_folder('books/'.$value);
                $book->delete();
            }
        }
        return redirect()->route('publisher.books')
                        ->with('success',trans('common.successMessageText'));
    }
}
