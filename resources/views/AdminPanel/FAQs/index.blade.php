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
                                <th>{{trans('common.question')}}</th>
                                <th class="text-center">{{trans('common.actions')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($faqs as $faq)
                            <tr id="row_{{$faq->id}}">
                                <td>
                                    {{$faq['question_ar']}}<br>
                                    {{$faq['question_en']}}<br>
                                    {{$faq['question_fr']}}
                                </td>
                                <td class="text-center">
                                    <a href="javascript:;" data-bs-target="#editfaq{{$faq->id}}" data-bs-toggle="modal" class="btn btn-icon btn-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{trans('common.edit')}}">
                                        <i data-feather='edit'></i>
                                    </a>
                                    <?php $delete = route('admin.faqs.delete',['id'=>$faq->id]); ?>
                                    <button type="button" class="btn btn-icon btn-danger" onclick="confirmDelete('{{$delete}}','{{$faq->id}}')" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{trans('common.delete')}}">
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

                @foreach($faqs as $faq)
                    <div class="modal fade text-md-start" id="editfaq{{$faq->id}}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
                            <div class="modal-content">
                                <div class="modal-header bg-transparent">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body pb-5 px-sm-5 pt-50">
                                    <div class="text-center mb-2">
                                        <h1 class="mb-1">{{trans('common.edit')}}</h1>
                                    </div>
                                    {{Form::open(['url'=>route('admin.faqs.update',['id'=>$faq->id]), 'id'=>'editfaqForm', 'class'=>'row gy-1 pt-75'])}}
                                        <div class="col-12 col-md-2">
                                            <label class="form-label" for="ranking">{{trans('common.ranking')}}</label>
                                            {{Form::text('ranking',$faq->ranking,['id'=>'ranking', 'class'=>'form-control'])}}
                                        </div>  
                                        <div class="col-12 col-md-10">
                                            <label class="form-label" for="question_ar">{{trans('common.question_ar')}}</label>
                                            {{Form::text('question_ar',$faq->question_ar,['id'=>'question_ar', 'class'=>'form-control'])}}
                                        </div>
                                        <div class="col-12 col-md-12">
                                            <label class="form-label" for="question_en">{{trans('common.question_en')}}</label>
                                            {{Form::text('question_en',$faq->question_en,['id'=>'question_en', 'class'=>'form-control'])}}
                                        </div>
                                        <div class="col-12 col-md-12">
                                            <label class="form-label" for="question_fr">{{trans('common.question_fr')}}</label>
                                            {{Form::text('question_fr',$faq->question_fr,['id'=>'question_fr', 'class'=>'form-control'])}}
                                        </div>
                                        
                                        <div class="col-12 col-md-12">
                                            <label class="form-label" for="answer_ar">{{trans('common.answer_ar')}}</label>
                                            {!!Form::textarea('answer_ar',$faq->answer_ar,['id'=>'answer_ar', 'class'=>'form-control editor_ar'])!!}
                                        </div> 
                                        <div class="col-12 col-md-12">
                                            <label class="form-label" for="answer_en">{{trans('common.answer_en')}}</label>
                                            {!!Form::textarea('answer_en',$faq->answer_en,['id'=>'answer_en', 'class'=>'form-control editor_en'])!!}
                                        </div> 
                                        <div class="col-12 col-md-12">
                                            <label class="form-label" for="answer_fr">{{trans('common.answer_fr')}}</label>
                                            {!!Form::textarea('answer_fr',$faq->answer_fr,['id'=>'answer_fr', 'class'=>'form-control editor_en'])!!}
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


                {{ $faqs->links('vendor.pagination.default') }}


            </div>
        </div>
    </div>
    <!-- Bordered table end -->



@stop

@section('page_buttons')
    <a href="javascript:;" data-bs-target="#createfaq" data-bs-toggle="modal" class="btn btn-primary">
        {{trans('common.CreateNew')}}
    </a>

    <div class="modal fade text-md-start" id="createfaq" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">{{trans('common.CreateNew')}}</h1>
                    </div>
                    {{Form::open(['url'=>route('admin.faqs.store'), 'id'=>'createfaqForm', 'class'=>'row gy-1 pt-75'])}}
                        <div class="col-12 col-md-2">
                            <label class="form-label" for="ranking">{{trans('common.ranking')}}</label>
                            {{Form::text('ranking',App\FAQs::where('type','publisher')->count()+1,['id'=>'ranking', 'class'=>'form-control'])}}
                        </div>  
                        <div class="col-12 col-md-10">
                            <label class="form-label" for="question_ar">{{trans('common.question_ar')}}</label>
                            {{Form::text('question_ar','',['id'=>'question_ar', 'class'=>'form-control'])}}
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="question_en">{{trans('common.question_en')}}</label>
                            {{Form::text('question_en','',['id'=>'question_en', 'class'=>'form-control'])}}
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="question_fr">{{trans('common.question_fr')}}</label>
                            {{Form::text('question_fr','',['id'=>'question_fr', 'class'=>'form-control'])}}
                        </div>
                                        
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="answer_ar">{{trans('common.answer_ar')}}</label>
                            {{Form::textarea('answer_ar','',['id'=>'answer_ar', 'class'=>'form-control editor_ar'])}}
                        </div> 
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="answer_en">{{trans('common.answer_en')}}</label>
                            {{Form::textarea('answer_en','',['id'=>'answer_en', 'class'=>'form-control editor_en'])}}
                        </div> 
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="answer_fr">{{trans('common.answer_fr')}}</label>
                            {{Form::textarea('answer_fr','',['id'=>'answer_fr', 'class'=>'form-control editor_en'])}}
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