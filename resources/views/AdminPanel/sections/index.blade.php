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
                                <th>#</th>
                                <th>{{trans('common.name')}}</th>
                                <th class="text-center">{{trans('common.books')}}</th>
                                <th class="text-center">{{trans('common.actions')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sections as $section)
                            <tr id="row_{{$section->id}}">
                                <td>#{{$section->id}}</td>
                                <td>
                                    {{$section['name_ar']}}<br>
                                    {{$section['name_en']}}<br>
                                    {{$section['name_fr']}}
                                </td>
                                <td class="text-center">
                                    {{ $section->books()->count() }}
                                </td>
                                <td class="text-center">
                                    <a href="{{route('admin.sections.subs',['sectionId'=>$section->id])}}" class="btn btn-icon btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{trans('common.SubSections')}} ({{$section->subSections()->count()}})">
                                        <i class="ficon" data-feather="list"></i>
                                        <?php /*<span class="badge rounded-pill bg-danger badge-up">
                                            {{$section->subSections()->count()}}
                                        </span>*/ ?>
                                    </a>
                                    <a href="javascript:;" data-bs-target="#editsection{{$section->id}}" data-bs-toggle="modal" class="btn btn-icon btn-info">
                                        <i data-feather='edit' data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{trans('common.edit')}}"></i>
                                    </a>
                                    <?php $delete = route('admin.sections.delete',['id'=>$section->id]); ?>
                                    <button type="button" class="btn btn-icon btn-danger" onclick="confirmDelete('{{$delete}}','{{$section->id}}')" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{trans('common.delete')}}">
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

                @foreach($sections as $section)
                    <div class="modal fade text-md-start" id="editsection{{$section->id}}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
                            <div class="modal-content">
                                <div class="modal-header bg-transparent">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body pb-5 px-sm-5 pt-50">
                                    <div class="text-center mb-2">
                                        <h1 class="mb-1">{{trans('common.edit')}}</h1>
                                    </div>
                                    {{Form::open(['url'=>route('admin.sections.update',['id'=>$section->id]), 'id'=>'editsectionForm', 'class'=>'row gy-1 pt-75'])}}
                                        <div class="col-12 col-md-4">
                                            <label class="form-label" for="name_ar">{{trans('common.name_ar')}}</label>
                                            {{Form::text('name_ar',$section->name_ar,['id'=>'name_ar', 'class'=>'form-control'])}}
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label" for="name_en">{{trans('common.name_en')}}</label>
                                            {{Form::text('name_en',$section->name_en,['id'=>'name_en', 'class'=>'form-control'])}}
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label" for="name_fr">{{trans('common.name_fr')}}</label>
                                            {{Form::text('name_fr',$section->name_fr,['id'=>'name_fr', 'class'=>'form-control'])}}
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


                {{ $sections->links('vendor.pagination.default') }}


            </div>
        </div>
    </div>
    <!-- Bordered table end -->



@stop

@section('page_buttons')
    <a href="javascript:;" data-bs-target="#createsection" data-bs-toggle="modal" class="btn btn-primary">
        {{trans('common.CreateNew')}}
    </a>

    <div class="modal fade text-md-start" id="createsection" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">{{trans('common.CreateNew')}}</h1>
                    </div>
                    {{Form::open(['url'=>route('admin.sections.store'), 'id'=>'createsectionForm', 'class'=>'row gy-1 pt-75'])}}
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="name_ar">{{trans('common.name_ar')}}</label>
                            {{Form::text('name_ar','',['id'=>'name_ar', 'class'=>'form-control'])}}
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="name_en">{{trans('common.name_en')}}</label>
                            {{Form::text('name_en','',['id'=>'name_en', 'class'=>'form-control'])}}
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="name_fr">{{trans('common.name_fr')}}</label>
                            {{Form::text('name_fr','',['id'=>'name_fr', 'class'=>'form-control'])}}
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