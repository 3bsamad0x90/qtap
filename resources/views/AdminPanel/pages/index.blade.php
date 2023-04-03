@extends('AdminPanel.layouts.master')
@section('content')


    <!-- Bordered table start -->
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{$title}}</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered mb-2">
                        <thead>
                            <tr>
                                <th >{{trans('common.title')}}</th>
                                <th class="text-center">{{trans('common.actions')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pages as $page)
                            <tr id="row_{{$page->id}}">
                                <td>
                                    {{$page['title_ar']}}<br>
                                    {{$page['title_en']}}
                                </td>
                                <td class="text-center">
                                    <a href="javascript:;" data-bs-target="#editpage{{$page->id}}" data-bs-toggle="modal" class="btn btn-icon btn-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{trans('common.edit')}}">
                                        <i data-feather='edit'></i>
                                    </a>
                                    <?php $delete = route('admin.pages.delete',['id'=>$page->id]); ?>
                                    <button type="button" class="btn btn-icon btn-danger" onclick="confirmDelete('{{$delete}}','{{$page->id}}')" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{trans('common.delete')}}">
                                        <i data-feather='trash-2'></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-3 text-center ">
                                        <h2>{{trans('common.nothingToView')}}</h2>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @foreach($pages as $page)
                    <div class="modal fade text-md-start" id="editpage{{$page->id}}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
                            <div class="modal-content">
                                <div class="modal-header bg-transparent">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body pb-5 px-sm-5 pt-50">
                                    <div class="text-center mb-2">
                                        <h1 class="mb-1">{{trans('common.edit')}}</h1>
                                    </div>
                                    {{Form::open(['url'=>route('admin.pages.update',['id'=>$page->id]), 'id'=>'editpageForm', 'class'=>'row gy-1 pt-75'])}}
                                        <div class="col-12 col-md-6">
                                            <label class="form-label" for="title_ar">{{trans('common.title_ar')}}</label>
                                            {{Form::text('title_ar',$page->title_ar,['id'=>'title_ar', 'class'=>'form-control'])}}
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label" for="title_en">{{trans('common.title_en')}}</label>
                                            {{Form::text('title_en',$page->title_en,['id'=>'title_en', 'class'=>'form-control'])}}
                                        </div>


                                        <div class="col-12 col-md-12">
                                            <label class="form-label" for="content_ar">{{trans('common.content_ar')}}</label>
                                            {!!Form::textarea('content_ar',$page->content_ar,['id'=>'content_ar', 'class'=>'form-control editor_ar'])!!}
                                        </div>
                                        <div class="col-12 col-md-12">
                                            <label class="form-label" for="content_en">{{trans('common.content_en')}}</label>
                                            {!!Form::textarea('content_en',$page->content_en,['id'=>'content_en', 'class'=>'form-control editor_en'])!!}
                                        </div>

                                        <div class="col-12 text-center mt-2 pt-50">
                                            <button type="submit" class="btn btn-primary me-1">{{trans('common.Save changes')}}</button>
                                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                                                {{trans('common.Cancel')}}
                                            </button>
                                        </div>
                                    {{Form::close()}}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach


                {{ $pages->links('vendor.pagination.default') }}


            </div>
        </div>
    </div>
    <!-- Bordered table end -->



@stop

@section('page_buttons')
    {{-- <a href="javascript:;" data-bs-target="#createpage" data-bs-toggle="modal" class="btn btn-primary">
        {{trans('common.CreateNew')}}
    </a> --}}

    <div class="modal fade text-md-start" id="createpage" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">{{trans('common.CreateNew')}}</h1>
                    </div>
                    {{Form::open(['url'=>route('admin.pages.store'), 'id'=>'createpageForm', 'class'=>'row gy-1 pt-75'])}}
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="title_ar">{{trans('common.title_ar')}}</label>
                            {{Form::text('title_ar','',['id'=>'title_ar', 'class'=>'form-control'])}}
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="title_en">{{trans('common.title_en')}}</label>
                            {{Form::text('title_en','',['id'=>'title_en', 'class'=>'form-control'])}}
                        </div>

                        <div class="col-12 col-md-12">
                            <label class="form-label" for="content_ar">{{trans('common.content_ar')}}</label>
                            {{Form::textarea('content_ar','',['id'=>'content_ar', 'class'=>'form-control editor_ar'])}}
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="content_en">{{trans('common.content_en')}}</label>
                            {{Form::textarea('content_en','',['id'=>'content_en', 'class'=>'form-control editor_en'])}}
                        </div>

                        <div class="col-12 text-center mt-2 pt-50">
                            <button type="submit" class="btn btn-primary me-1">{{trans('common.Save changes')}}</button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                                {{trans('common.Cancel')}}
                            </button>
                        </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>
@stop
