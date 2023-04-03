@extends('AdminPanel.layouts.master')
@section('content')


    <!-- Bordered table start -->
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{$title}}</h4>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered mb-2">
                        <thead class="text-center">
                            <tr>
                                <th scope="col">إسم المنتج</th>
                                <th scope="col">الوصف</th>
                                <th scope="col">السعر</th>
                                <th scope="col">الصورة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @forelse($products as $product)
                            <tr id="row_{{$product->id}}">
                                <td>
                                    {{$product['title_ar']}}<br>
                                    {{$product['title_en']}}
                                </td>
                                <td style="word-break: break-word;">
                                    {{$product->description_ar}}<br>
                                    {{ $product->description_en }}
                                </td>
                                <td>
                                    {{ $product->price }}
                                </td>
                                <td>
                                    <img src="{{ $product->photoLink() }}" width="80px" height="80px" class="round border">

                                </td>
                                <td class="text-center">
                                    <a href="javascript:;" data-bs-target="#editImages{{$product->id}}" data-bs-toggle="modal"
                                      class="btn btn-icon btn-primary m-1" data-bs-toggle="tooltip" data-bs-placement="top"
                                      data-bs-original-title="{{trans('common.edit')}}">
                                      <i data-feather='camera'></i>
                                    </a>
                                    <a href="javascript:;" data-bs-target="#editproduct{{$product->id}}" data-bs-toggle="modal" class="btn btn-icon btn-info m-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{trans('common.edit')}}">
                                        <i data-feather='edit'></i>
                                    </a>
                                    <?php $delete = route('products.delete',['product'=>$product->id]); ?>
                                    <button type="button" class="btn btn-icon btn-danger m-1" onclick="confirmDelete('{{$delete}}','{{$product->id}}')" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{trans('common.delete')}}">
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

                @foreach($products as $product)
                    <div class="modal fade text-md-start" id="editproduct{{$product->id}}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
                            <div class="modal-content">
                                <div class="modal-header bg-transparent">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body pb-5 px-sm-5 pt-50">
                                    <div class="text-center mb-2">
                                        <h1 class="mb-1">{{trans('common.edit')}}</h1>
                                    </div>
                                    {{Form::open(['url'=>route('products.update',['product'=>$product->id]), 'id'=>'editproductForm', 'class'=>'row gy-1 pt-75', 'files'=>true])}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label" for="title_ar">إسم المنتج بالعربية</label>
                                        {{Form::text('title_ar',$product->title_ar,['id'=>'title_ar', 'class'=>'form-control'])}}
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label" for="title_en">إسم المنتج بالإنجليزية</label>
                                        {{Form::text('title_en',$product->title_en,['id'=>'title_en', 'class'=>'form-control'])}}
                                    </div>
                                    <div class="col-12 col-md-12">
                                        <label class="form-label" for="description_ar">وصف المنتج بالعربية</label>
                                        {{Form::textarea('description_ar',$product->description_ar,['id'=>'description_ar', 'class'=>'form-control', 'rows'=>3])}}
                                    </div>
                                    <div class="col-12 col-md-12">
                                        <label class="form-label" for="description_en">وصف المنتج بالإنجليزية</label>
                                        {{Form::textarea('description_en',$product->description_en,['id'=>'description_en', 'class'=>'form-control', 'rows'=>3])}}
                                    </div>
                                    <div class="col-12 col-md-12">
                                        <label class="form-label" for="price">السعر</label>
                                        {{Form::number('price',$product->price,['id'=>'price', 'class'=>'form-control', 'min'=>0])}}
                                    </div>
                                    <div class="col-12 col-md-12">
                                      <label class="form-label" for="type">النوع</label>
                                      {{Form::select('type',
                                      ['car'=>'Car', 'busseniss'=>'Busseniss']
                                      ,$product->type,['id'=>'type', 'class'=>'form-control'])}}
                                    </div>
                                    <div class="col-12 col-md-12">
                                        <label class="form-label" for="image">صورة المنتج</label>
                                        {{Form::file('image',['id'=>'image', 'class'=>'form-control'])}}
                                    </div>
                                    <div class="col-12 col-md-12">
                                      <label class="form-label" for="video">لينك الفيديو</label>
                                      {{Form::text('video',$product->video,['id'=>'video', 'class'=>'form-control'])}}
                                    </div>
                                    <div class="col-12 text-center mt-2 pt-50">
                                        <button type="submit" class="btn btn-primary me-1">حفظ التغييرات</button>
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
                {{-- Edit Images --}}
                @foreach($products as $product)
                <div class="modal fade text-md-start" id="editImages{{$product->id}}" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
                    <div class="modal-content">
                      <div class="modal-header bg-transparent">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body pb-5 px-sm-5 pt-50">
                        <div class="text-center mb-2">
                          <h1 class="mb-1">{{trans('common.edit')}}</h1>
                        </div>
                        {{Form::open(['url'=>route('products.updateImages',['product'=>$product->id]), 'id'=>'editnewForm', 'class'=>'row gy-1
                        pt-75', 'files'=>true])}}
                        @php
                        $images = json_decode($product->images);
                        @endphp

                        @if($images != null)
                        @foreach ($images as $image)
                        <div class="user-image col-md-4 col-lg-3 mb-2 position-relative" id="{{$product->id.'_remove_'.$image}}">
                          <img src="{{ asset('uploads/products/'.$product->id. '/' . $image) }}" alt="" height="90" width="100%" />
                          <button type="button" class="btn-close bg-white text-center"
                            onclick="removeItem(this,'{{$product->id.'_remove_'.$image}}')"
                            style="position: absolute; top: -4px; left: 10px"></button>
                          <input type='hidden' value='{{$image}}' name="image_hidden[]" id="image_arr">
                        </div>

                        @endforeach
                        @endif

                        <div class="col-12 col-md-12">
                          <label class="form-label" for="images">الصور</label>
                          {{Form::file('images[]',['id'=>'images', 'class'=>'form-control', 'multiple'=>true])}}

                        </div>

                        <div class="col-12 text-center mt-2 pt-50">
                          <button type="submit" class="btn btn-primary me-1">حفظ التغييرات</button>
                          <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                            إالغاء
                          </button>
                        </div>
                        {{Form::close()}}
                      </div>
                    </div>
                  </div>
                </div>
                @endforeach
                {{ $products->links('vendor.pagination.default') }}


            </div>
        </div>
    </div>
    <!-- Bordered table end -->



@stop

@section('page_buttons')
    <a href="javascript:;" data-bs-target="#createproduct" data-bs-toggle="modal" class="btn btn-primary">
        إضافة جديد
    </a>

    <div class="modal fade text-md-start" id="createproduct" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">إضافة جديد</h1>
                    </div>
                    {{Form::open(['url'=>route('products.store'), 'id'=>'createproductForm', 'class'=>'row gy-1 pt-75', 'files'=>true])}}
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="title_ar">إسم المنتج بالعربية</label>
                            {{Form::text('title_ar','',['id'=>'title_ar', 'class'=>'form-control'])}}
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="title_en">إسم المنتج بالإنجليزية</label>
                            {{Form::text('title_en','',['id'=>'title_en', 'class'=>'form-control'])}}
                        </div>

                        <div class="col-12 col-md-12">
                            <label class="form-label" for="description_ar">وصف المنتج بالعربية</label>
                            {{Form::textarea('description_ar','',['id'=>'description_ar', 'class'=>'form-control', 'rows'=>3])}}
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="description_en">وصف المنتج بالإنجليزية</label>
                            {{Form::textarea('description_en','',['id'=>'description_en', 'class'=>'form-control', 'rows'=>3])}}
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="price">السعر</label>
                            {{Form::number('price','',['id'=>'price', 'class'=>'form-control', 'min'=>0])}}
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="type">النوع</label>
                            {{Form::select('type',
                              ['car'=>'Car', 'busseniss'=>'Busseniss']
                              ,'',['id'=>'type', 'class'=>'form-control'])}}
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="image">صورة المنتج</label>
                            {{Form::file('image',['id'=>'image', 'class'=>'form-control'])}}
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="video">لينك الفيديو</label>
                            {{Form::text('video','',['id'=>'video', 'class'=>'form-control'])}}
                        </div>
                        <div class="col-12 col-md-12">
                          <label class="form-label" for="images">صور إضافية</label>
                          {{Form::file('images[]',['id'=>'images', 'class'=>'form-control', 'multiple'=>true])}}
                        </div>
                        <div class="col-12 text-center mt-2 pt-50">
                            <button type="submit" class="btn btn-primary me-1">حفظ التغييرات</button>
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
@section('scripts')
<script>
  function removeItem(elem,id){
      const idItem = document.getElementById(id).src;
      const Item = document.getElementById(id);
      elem.remove();
      Item.remove();

}
</script>

@stop
