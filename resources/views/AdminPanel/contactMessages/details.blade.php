@extends('AdminPanel.layouts.master')
@section('content')


    <!-- Bordered table start -->
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">بيانات المستخدم</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-2">
                            <b>الإسم:</b>
                            {{$message->userData()['name']}}
                        </div>

                        <div class="col-4">
                            <b>الإيميل:</b>
                            {{$message->userData()['email']}}
                        </div>
                        <div class="col-4">
                            <b>الهاتف:</b>
                            <span dir="ltr">
                                {{$message->userData()['phone']}}
                            </span>
                        </div>
                    </div>

                </div>

            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">تفاصيل الرسالة <small>(<b class="text-danger">{{$message->subjectText()}}</b>)</small></h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            {{$message->content}}
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <!-- Bordered table end -->



@stop
