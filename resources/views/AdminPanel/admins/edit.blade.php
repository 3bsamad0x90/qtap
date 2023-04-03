@extends('AdminPanel.layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">

            <!-- profile -->
            <div class="card">
                <div class="card-body py-2 my-25">
                    {{Form::open(['files'=>'true','class'=>'validate-form'])}}
                        <div class="row d-flex justify-content-center">
                            <div class="col-md-3 text-center">
                                <span class="avatar mb-2">
                                    <img class="round" src="{{$user->photoLink()}}" alt="avatar" height="150" width="150">
                                </span>
                                <div class="file-loading">
                                    <input class="files" name="photo" type="file">
                                </div>
                            </div>
                        </div>

                        <!-- form -->
                        <div class="row pt-3">
                            <div class="col-12 col-md-6 mb-1">
                                <label class="form-label" for="name">{{trans('common.name')}} <span class="text-danger">*</span></label>
                                {{Form::text('name',$user->name,['id'=>'name','class'=>'form-control','required'])}}
                            </div>
                            <div class="col-12 col-md-6 mb-1">
                                <label class="form-label" for="userName">{{trans('common.username')}}</label>
                                {{Form::text('userName',$user->userName,['id'=>'userName','class'=>'form-control'])}}
                            </div>
                            <div class="col-12 col-md-6 mb-1">
                                <label class="form-label" for="email">{{trans('common.email')}} <span class="text-danger">*</span></label>
                                {{Form::text('email',$user->email,['id'=>'email','class'=>'form-control','required'])}}
                            </div>
                            <div class="col-12 col-md-6 mb-1">
                                <label class="form-label" for="email">{{trans('common.password')}}</label>
                                {{Form::password('password',['id'=>'password','class'=>'form-control','autoComplete'=>'new-password'])}}
                            </div>

                            <div class="col-12 col-md-6 mb-1">
                                <label class="form-label" for="role">{{trans('common.role')}}</label>
                                {{Form::select('role',getRolesList(session()->get('Lang'),'id','admin'),$user->role,['id'=>'role','class'=>'form-control selectpicker','data-live-search'=>'true'])}}
                            </div>

                            <div class="col-12 col-md-6 mb-1">
                                <label class="form-label" for="phone">{{trans('common.phone')}}</label>
                                {{Form::text('phone',$user->phone,['id'=>'phone','class'=>'form-control'])}}
                            </div>

                            <div class="col-12 col-md-12 mb-1">
                                <label class="form-label" for="address">{{trans('common.address')}}</label>
                                {{Form::text('address',$user->address,['id'=>'address','class'=>'form-control'])}}
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary mt-1 me-1">{{trans('common.Save changes')}}</button>
                            </div>
                        </div>
                        <!--/ form -->
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>
@stop
