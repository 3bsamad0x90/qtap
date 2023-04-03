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
                                <th>{{trans('common.name')}}</th>
                                <th class="text-center">{{trans('common.type')}}</th>
                                <th class="text-center">{{trans('common.transferRate')}}</th>
                                <th class="text-center">{{trans('common.actions')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($currencies as $currency)
                            <tr id="row_{{$currency->id}}">
                                <td>
                                    {{$currency['name_ar']}}<br>
                                    {{$currency['name_en']}}<br>
                                    {{$currency['name_fr']}}
                                </td>
                                <td class="text-center">
                                    {{$currency->typeText()}}
                                </td>
                                <td class="text-center">
                                        {{$currency->transfer_rate}}
                                </td>
                                <td class="text-center">
                                    <a href="javascript:;" data-bs-target="#editcurrency{{$currency->id}}" data-bs-toggle="modal" class="btn btn-icon btn-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{trans('common.edit')}}">
                                        <i data-feather='edit'></i>
                                    </a>
                                    <?php $delete = route('admin.currencies.delete',['id'=>$currency->id]); ?>
                                    <button type="button" class="btn btn-icon btn-danger" onclick="confirmDelete('{{$delete}}','{{$currency->id}}')" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{trans('common.delete')}}">
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

                @foreach($currencies as $currency)
                    <div class="modal fade text-md-start" id="editcurrency{{$currency->id}}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
                            <div class="modal-content">
                                <div class="modal-header bg-transparent">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body pb-5 px-sm-5 pt-50">
                                    <div class="text-center mb-2">
                                        <h1 class="mb-1">{{trans('common.edit')}}</h1>
                                    </div>
                                    {{Form::open(['url'=>route('admin.currencies.update',['id'=>$currency->id]), 'id'=>'editcurrencyForm', 'class'=>'row gy-1 pt-75'])}}
                                        <div class="col-12 col-md-4">
                                            <label class="form-label" for="name_ar">{{trans('common.name_ar')}}</label>
                                            {{Form::text('name_ar',$currency->name_ar,['id'=>'name_ar', 'class'=>'form-control','required'])}}
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label" for="name_en">{{trans('common.name_en')}}</label>
                                            {{Form::text('name_en',$currency->name_en,['id'=>'name_en', 'class'=>'form-control'])}}
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label" for="name_fr">{{trans('common.name_fr')}}</label>
                                            {{Form::text('name_fr',$currency->name_fr,['id'=>'name_fr', 'class'=>'form-control'])}}
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label" for="sympl_ar">{{trans('common.sympl_ar')}}</label>
                                            {{Form::text('sympl_ar',$currency->sympl_ar,['id'=>'sympl_ar', 'class'=>'form-control','required'])}}
                                        </div>  
                                        <div class="col-12 col-md-4">
                                            <label class="form-label" for="sympl_en">{{trans('common.sympl_en')}}</label>
                                            {{Form::text('sympl_en',$currency->sympl_en,['id'=>'sympl_en', 'class'=>'form-control','required'])}}
                                        </div>  
                                            
                                        <div class="col-12 col-md-4">
                                            <label class="form-label" for="transfer_rate">{{trans('common.transferRate')}}</label>
                                            {{Form::number('transfer_rate',$currency->transfer_rate,['id'=>'transfer_rate', 'step'=>'.0001', 'class'=>'form-control','required'])}}
                                        </div>  
                                        <div class="col-12 col-md-4">
                                            <label class="form-label" for="country">{{trans('common.country')}}</label>
                                            {{Form::select('country',getCountriesList(session()->get('Lang'),'id'),$currency->country,['id'=>'country','class'=>'form-control selectpicker','data-live-search'=>'true'])}}
                                        </div>         
                                        <div class="col-12 col-md-4">
                                            <label class="form-label" for="primary">{{trans('common.default')}}</label>
                                            <div class="form-check form-check-success form-switch">
                                                {{Form::checkbox('primary','1',$currency->primary == '0' ? false : true,['id'=>'primary', 'class'=>'form-check-input'])}}
                                                <label class="form-check-label" for="primary"></label>
                                            </div>
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


                {{ $currencies->links('vendor.pagination.default') }}


            </div>
        </div>
    </div>
    <!-- Bordered table end -->



@stop

@section('page_buttons')
    <a href="javascript:;" data-bs-target="#createcurrency" data-bs-toggle="modal" class="btn btn-primary">
        {{trans('common.CreateNew')}}
    </a>

    <div class="modal fade text-md-start" id="createcurrency" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">{{trans('common.CreateNew')}}</h1>
                    </div>
                    {{Form::open(['url'=>route('admin.currencies.store'), 'id'=>'createcurrencyForm', 'class'=>'row gy-1 pt-75'])}}
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="name_ar">{{trans('common.name_ar')}}</label>
                            {{Form::text('name_ar','',['id'=>'name_ar', 'class'=>'form-control','required'])}}
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="name_en">{{trans('common.name_en')}}</label>
                            {{Form::text('name_en','',['id'=>'name_en', 'class'=>'form-control'])}}
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="name_fr">{{trans('common.name_fr')}}</label>
                            {{Form::text('name_fr','',['id'=>'name_fr', 'class'=>'form-control'])}}
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="sympl_ar">{{trans('common.sympl_ar')}}</label>
                            {{Form::text('sympl_ar','',['id'=>'sympl_ar', 'class'=>'form-control','required'])}}
                        </div>  
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="sympl_en">{{trans('common.sympl_en')}}</label>
                            {{Form::text('sympl_en','',['id'=>'sympl_en', 'class'=>'form-control','required'])}}
                        </div>  
                               
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="transfer_rate">{{trans('common.transferRate')}}</label>
                            {{Form::number('transfer_rate','',['id'=>'transfer_rate', 'step'=>'.0001', 'class'=>'form-control','required'])}}
                        </div>  
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="country">{{trans('common.country')}}</label>
                            {{Form::select('country',getCountriesList(session()->get('Lang'),'id'),'',['id'=>'country','class'=>'form-control selectpicker','data-live-search'=>'true'])}}
                        </div>         
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="primary">{{trans('common.default')}}</label>
                            <div class="form-check form-check-success form-switch">
                                {{Form::checkbox('primary','1',false,['id'=>'primary', 'class'=>'form-check-input'])}}
                                <label class="form-check-label" for="primary"></label>
                            </div>
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