@extends('AdminPanel.layouts.master')
@section('content')

    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $title }}</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered mb-2">
                        <thead  class="text-center">
                            <tr>
                                <th>رقم الطلب</th>
                                <th>التاريخ</th>
                                <th>العميل</th>
                                <th>المنتجات</th>
                                <th>الإجمالي</th>
                                <th>الحالة</th>
                                <th class="text-center">{{ trans('common.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @forelse($orders as $order)
                                <tr id="row_{{$order->id}}">
                                    <td  class="text-center">{{$order->id}}</td>
                                    <td  class="text-center">
                                        {{$order->date_time}}
                                    </td>
                                    <td  class="text-center">
                                        {{$order->user->name ?? '-'}}
                                    </td>
                                    <td>
                                        <ul>
                                            @foreach($order->subOrders as $subOrder)
                                                 {{$subOrder->product->title_ar }} <br/>
                                                 {{ $subOrder->product->title_en }}
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="text-center">
                                        @foreach ($order->subOrders as $subOrder)
                                            الكمية : {{ $subOrder->quantity }} <br/>
                                            السعر : {{ $subOrder->product->price }} <br/>
                                            الإجمالي : {{ $subOrder->product->price * $subOrder->quantity }}
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($order->status === "pending")
                                            <span class="badge badge-light-success">Pending</span>
                                        @elseif($order->status === "shipping")
                                            <span class="badge badge-light-info">Shipping</span>
                                        @elseif($order->status === "delivered")
                                            <span class="badge badge-light-secondary">Delivered</span>
                                        @elseif($order->status === "canceled")
                                            <span class="badge badge-light-warning">Canceled</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                       <a href="javascript:;" data-bs-target="#editstatus{{$order->id}}" data-bs-toggle="modal" class="btn btn-icon btn-primary" data-bs-toggle="tooltip"
                                          data-bs-placement="top"
                                          data-bs-original-title="{{trans('common.edit')}}">
                                            <i data-feather='edit'></i>
                                        </a>
                                        <a href="{{ route('admin.orders.details', ['id' => $order->id]) }}"
                                            class="btn btn-icon btn-info" data-bs-toggle="tooltip" data-bs-placement="top"
                                            data-bs-original-title="{{ trans('common.from_details') }}">
                                            <i data-feather='list'></i>
                                        </a>
                                        <?php /*$delete = route('admin.orders.delete', ['id' => $order->id]); ?>
                                        <button type="button" class="btn btn-icon btn-danger"
                                            onclick="confirmDelete('{{ $delete }}','{{ $order->id }}')">
                                            <i data-feather='trash-2'></i>
                                        </button>
                                        */ ?>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="22" class="p-3 text-center ">
                                        <h2>{{ trans('common.nothingToView') }}</h2>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
				  @foreach($orders as $order)
                <div class="modal fade text-md-start" id="editstatus{{$order->id}}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
                        <div class="modal-content">
                            <div class="modal-header bg-transparent">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body pb-5 px-sm-5 pt-50">
                                <div class="text-center mb-2">
                                    <h1 class="mb-1">{{trans('common.edit')}}</h1>
                                </div>
                                {{Form::open(['url'=>route('admin.orders.editStatus',['order'=>$order->id]), 'id'=>'editStatusForm', 'class'=>'row gy-1 pt-75'])}}
                                    <div class="col-12 text-center">
                                        <label class="form-label" for="status">حالة الطلب</label>
                                        {{ Form::select('status', [
                                            // 'pending' => 'Pending',
                                            'shipping' => 'Shipping',
                                            'delivered' => 'Delivered',
                                            'canceled' => 'Canceled',
                                        ],$order->status, ['id'=>'status', 'class'=>'form-control']) }}
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
                {{$orders->links('vendor.pagination.default') }}


            </div>
        </div>
    </div>
    <!-- Bordered table end -->



@stop

@section('page_buttons')
    <a href="javascript:;" data-bs-target="#searchOrders" data-bs-toggle="modal" class="btn btn-primary">
        {{trans('common.search')}}
    </a>

    <div class="modal fade text-md-start" id="searchOrders" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">{{trans('common.search')}}</h1>
                    </div>
                    {{Form::open(['url'=>route('admin.orders'), 'id'=>'searchOrdersForm', 'class'=>'row gy-1 pt-75','method'=>'GET'])}}
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="client_id">{{trans('common.client')}}</label>
                            {{Form::select('client_id',
                                                    [''=>trans('common.allClients')]
                                                    + App\Models\User::where('role','2')->pluck('name','id')->all(),
                                                    isset($_GET['client_id']) ? $_GET['client_id'] : '',['id'=>'client_id', 'class'=>'form-control selectpicker','data-live-search'=>'true'])}}
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="order_id">{{trans('common.order_id')}}</label>
                            {{Form::text('order_id',isset($_GET['order_id']) ? $_GET['order_id'] : '',['id'=>'order_id', 'class'=>'form-control'])}}
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="from_date">{{trans('common.from_date')}}</label>
                            {{Form::date('from_date',isset($_GET['from_date']) ? $_GET['from_date'] : '',['id'=>'from_date', 'class'=>'form-control'])}}
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="to_date">{{trans('common.to_date')}}</label>
                            {{Form::date('to_date',isset($_GET['to_date']) ? $_GET['to_date'] : '',['id'=>'to_date', 'class'=>'form-control'])}}
                        </div>
                        <div class="col-12 text-center mt-2 pt-50">
                            <button type="submit" class="btn btn-primary me-1">{{trans('common.search')}}</button>
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
