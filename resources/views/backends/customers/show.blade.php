@extends('backends.layouts.master')
@section('title', __('customer.form.title'))
@section('content')
<div id="customer-list">
    @if(\Auth::user()->isRoleStaff())
    <div class="row {{Auth::user()->isRoleStaff() ? 'sp-staff-block' : ''}}">
        <div class="col-6 mb-4 p-1">
            <div class="card shadow py-2 border-success">
                <div class="card-body text-center">
                    <a href="{{route('sale.index')}}"><i class="far fa-newspaper text-warning"></i> {{__('menu.sale')}}</a>
                </div>
            </div>
        </div>
        <div class="col-6  mb-4 p-1">
            <div class="card shadow py-2 border-success">
                <div class="card-body text-center">
                    <a href="{{route('customer.index')}}"><i class="fas fa-users text-danger"></i> {{__('menu.customer')}}</a>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Page Heading -->
    <div class="border-bottom mb-3 pb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-3">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        {{ __('dashboard.title') }}
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('customer.index') }}">
                        <span class="sub-title">{{ __('customer.sub_title') }}</span>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <span class="sub-title">{{  $customer->name }}</span>
                </li>
            </ol>
        </nav>
        <div class="d-flex">
            <a href="{{route('customer.index')}}" 
                class="btn btn-circle btn-primary"
                data-toggle="tooltip" 
                data-placement="left" title="" 
                data-original-title="{{__('customer.sub_title')}}"
            ><i class="fas fa-list"></i> {{__('customer.sub_title')}}</a>
            <div class="d-inline-flex justify-content-end" style="width: 49%;">
                <button  class="btn btn-circle btn-primary" data-toggle="collapse" data-target="#sale-customer">CheckIn</button>
            </div>
        </div>
    </div>
    @endif
    <div class="row mb-2">
        <div class="col-12 col-md-6 tab-card mb-2">
            <!-- Circle Buttons -->
            <div class="card mb-4">
                <div id="customerList" class="card-body collapse show">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div id="addcustomer" class="tab-pane active">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th class="border-top-0 w-15">{{__('customer.list.name')}}:</th>
                                        <td class="border-top-0">{{ $customer->customerFullName() }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{__('customer.list.phone')}}:</th>
                                        <td>
                                            <div class="d-flex flex-row">
                                                <i class="fas fa-phone-square-alt text-success my-1 mr-1"></i>
                                                <div>
                                                    {{ $customer->phone1 }}<br/>
                                                    {{ $customer->phone2 ? $customer->phone2: '' }}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{__('customer.list.address')}}:</th>
                                        <td>{{ $customer->address }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{__('customer.form.thumbnail')}}:</th>
                                        <td>
                                            <div class="w-100 w-md-50">
                                                <img class="img-thumbnail" src="{{$customer->thumbnail? getUploadUrl($customer->thumbnail, config('upload.customer')) : asset('images/no-avatar.jpg') }}" width="200px" />
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                              </table>
                            <div class="form-group w-100 w-md-50 d-inline-flex">
                                <a href="{{route('customer.edit', $customer->id)}}" class="btn btn-circle btn-primary w-100 w-md-50 mw-100 mr-2">{{__('button.edit')}}</a>
                                <a href="{{route('customer.index')}}" class="btn btn-circle btn-secondary w-100 w-md-50 mw-100">{{__('button.return')}}</a>
                            </div>
                        </div><!--/tab-add-customer-->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 tab-card mb-2">
            <!-- Circle Buttons -->
            <div class="card mb-4">
                <div class="card-body">
                    <h3>ប្រវត្តិការទិញ</h3>
                    <div id="sale-customer" class="table-responsive collapse">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>{{__('sale.list.invoice_code')}}</th>
                                    <th>{{__('sale.list.sale_date')}}</th>
                                    <th>{{__('sale.list.staff_name')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach( $saleCustomers as $sale)
                                    <tr>
                                        <td class="text-center" rowspan="2">
                                            @if ($sale->productSales->count() > 0)
                                            <a href="#slae_{{$sale->id}}" data-toggle="collapse" style="text-decoration: none !important;" class="collapsed"><i class="fas fa-minus-circle"></i></a>
                                            @endif
                                        </td>
                                        <td style="width: 270px;">{{$sale->quotaion_no}}</td>
                                        <td>{{date('Y-m-d h:i', strtotime($sale->sale_date))}}</td>
                                        <td>{{$sale->staff ? $sale->staff->getFullnameAttribute() : \Auth::user()->name}}</td>
                                    </tr>
                                    @if ($sale->productSales->count() > 0)
                                    <tr>
                                        <td colspan="9" id="slae_{{$sale->id}}" class="collapse p-0">
                                            <table class="table table-borderless mb-0">
                                                <tbody>
                                                @php
                                                    $total = 0;
                                                @endphp
                                                @foreach ($sale->productSales as $productSale)
                                                    @php
                                                        $total +=$productSale->amount;
                                                    @endphp
                                                    <tr class="border-sale">
                                                        <td style="width: 269px;">{{$productSale->product ? $productSale->product->title : '' }}</td>
                                                        <td>{{$productSale->quantity}}</td>
                                                        <td>{{$productSale->product_free}}</td>
                                                        <td>{{$productSale->rate}}</td>
                                                        <td>{{$productSale->amount}}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr class="border-sale--top">
                                                        <td colspan="4" class="text-right text-primary">{{__('sale.list.total')}}</td>
                                                        <td>{{currencyFormat($sale->total_amount)}}</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div><!--/row-->
</div>
@endsection