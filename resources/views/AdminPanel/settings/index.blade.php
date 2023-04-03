@extends('AdminPanel.layouts.master')
@section('content')


    <!-- Bordered table start -->
    <div class="row" id="table-bordered">
        <div class="col-12">
            {{Form::open(['url'=>route('admin.settings.update'), 'files'=>'true'])}}
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="general-tab" data-bs-toggle="tab" href="#general" aria-controls="home" role="tab" aria-selected="true">
                                <i data-feather="home"></i> {{trans('common.generalSettings')}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="mainPage-tab" data-bs-toggle="tab" href="#mainPage" aria-controls="mainPage" role="tab" aria-selected="false">
                                <i data-feather="book-open"></i> الصفحة الرئيسية
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="features-tab" data-bs-toggle="tab" href="#features" aria-controls="features" role="tab" aria-selected="false">
                                <i data-feather="heart"></i>مميزتنا
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="social-tab" data-bs-toggle="tab" href="#social" aria-controls="social" role="tab" aria-selected="false">
                                <i data-feather="tool"></i> {{trans('common.socialSettings')}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="images-tab" data-bs-toggle="tab" href="#images" aria-controls="images" role="tab" aria-selected="false">
                                <i data-feather="image"></i> {{trans('common.imagesSettings')}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#contact" aria-controls="contact" role="tab" aria-selected="false">
                                <i data-feather="mail"></i> {{trans('common.contactSettings')}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="aboutus-tab" data-bs-toggle="tab" href="#aboutus" aria-controls="aboutus" role="tab" aria-selected="false">
                                <i data-feather="users"></i> من نحن
                            </a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="general" aria-labelledby="general-tab" role="tabpanel">
                            @include('AdminPanel.settings.includes.general')
                        </div>
                        <div class="tab-pane" id="mainPage" aria-labelledby="mainPage-tab" role="tabpanel">
                            @include('AdminPanel.settings.includes.mainPage')
                        </div>
                        <div class="tab-pane" id="features" aria-labelledby="features-tab" role="tabpanel">
                            @include('AdminPanel.settings.includes.features')
                        </div>
                        <div class="tab-pane" id="social" aria-labelledby="social-tab" role="tabpanel">
                            @include('AdminPanel.settings.includes.social')
                        </div>
                        <div class="tab-pane" id="images" aria-labelledby="images-tab" role="tabpanel">
                            @include('AdminPanel.settings.includes.images')
                        </div>
                        <div class="tab-pane" id="contact" aria-labelledby="contact-tab" role="tabpanel">
                            @include('AdminPanel.settings.includes.contact')
                        </div>
                        <div class="tab-pane" id="aboutus" aria-labelledby="aboutus-tab" role="tabpanel">
                            @include('AdminPanel.settings.includes.aboutus')
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <input type="submit" value="{{trans('common.Save changes')}}" class="btn btn-primary">
                </div>
            </div>
            {{Form::close()}}
        </div>
    </div>
    <!-- Bordered table end -->
@stop
