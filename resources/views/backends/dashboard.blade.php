@extends('backends.layouts.master')
@section('title', 'RRPS-PHARMA | '.__('dashboard.title'))
@section('content')
<div id="dashboard">
    <!-- Page Heading -->
    @if(!\Auth::user()->isRoleStaff())
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h5 class="mb-0 text-gray-800">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            RRPS-PHARMA
        </h5>
    </div>
    @else
        <div class="row {{Auth::user()->isRoleStaff() ? 'sp-staff-block' : ''}}">
            <div class="col-6 p-1">
                <div class="card shadow py-2 border-success">
                    <div class="card-body text-center">
                        <a href="{{route('sale.index')}}"><i class="far fa-newspaper text-warning"></i> {{__('menu.sale')}}</a>
                    </div>
                </div>
            </div>
            <div class="col-6 p-1">
                <div class="card shadow py-2 border-success">
                    <div class="card-body text-center">
                        <a href="{{route('customer.index')}}"><i class="fas fa-users text-danger"></i> {{__('menu.customer')}}</a>
                    </div>
                </div>
            </div>
            <div class="col-6 mb-4 p-1">
                <div class="card shadow py-2 border-success">
                    <div class="card-body text-center">
                        <a href="{{route('customer_map.index')}}"><i class="fas fa-map-marked-alt"></i> {{__('menu.customer_map')}}</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- Content Row -->
    <div class="row">
        <!-- Product -->
        @if(Auth::user()->isRoleAdmin() || Auth::user()->isRoleView() || Auth::user()->isRoleEditor())
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="fa-1x font-weight-bold text-primary text-uppercase mb-1"><a href="{{route('product.index')}}">{{__('dashboard.total_products')}}</a></div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{$productCount}}</div>
                        </div>
                        <div class="col-auto">
                            <a href="{{route('product.index')}}"><i class="fab fa-product-hunt fa-3x text-primary"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(Auth::user()->isRoleAdmin())
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="fa-1x font-weight-bold text-danger text-uppercase mb-1"><a href="{{route('user.index')}}">{{__('dashboard.total_users')}}</a></div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{$userCount}}</div>
                    </div>
                    <div class="col-auto">
                        <a href="{{route('user.index')}}"><i class="fas fa-users fa-3x text-danger"></i></a>
                    </div>
                </div>
                </div>
            </div>
        </div>
        @endif
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="fa-1x font-weight-bold text-info text-uppercase mb-1"><a href="{{route('customer.index')}}">{{__('dashboard.total_customers')}}</a></div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{$customerCount}}</div>
                    </div>
                    <div class="col-auto">
                        <a href="{{route('customer.index')}}"><i class="fas fa-user fa-3x text-info"></i></a>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="fa-1x font-weight-bold text-warning text-uppercase mb-1"><a href="{{route('staff.index')}}">{{__('dashboard.total_staffs')}}</a></div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{$staffCount}}</div>
                    </div>
                    <div class="col-auto">
                        <a href="{{route('staff.index')}}"><i class="fas fa-users fa-3x text-warning"></i></a>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card">
                <div class="card-header text-white bg-danger">
                    <div class="font-weight-bold">
                        <span>{{__('dashboard.total_category')}}</span>
                    </div>
                </div>
                <div class="card-block py-0 px-4 b-t-1">
                    <div class="row">
                        <div class="col-4 b-r-1 py-3">
                            <a href="{{route('category.index')}}"><i class="fas fa-tags fa-1x text-danger"></i></a>
                        </div>
                        <div class="col-8 py-3 text-right">
                            <div class="font-weight-bold">{{$categoryCount}}</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer px-3 py-2">
                    <a class="font-weight-bold font-xs btn-block text-muted" href="{{route('category.index')}}">{{__('dashboard.view_more')}} <i class="fa fa-angle-right float-right font-lg"></i></a>
                </div>
            </div>
        </div>
        @endif
        <!-- Sale -->
        @if(Auth::user()->isRoleAdmin() || Auth::user()->isRoleView() || Auth::user()->isRoleEditor())
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card">
                <div class="card-header text-white bg-success">
                    <div class="font-weight-bold">
                         {{__('dashboard.total_sales')}}
                    </div>
                </div>
                <div class="card-block py-0 px-4 b-t-1">
                    <div class="row">
                        <div class="col-4 b-r-1 py-3">
                            <div class="font-weight-bold">{{__('dashboard.total')}}</div>
                        </div>
                        <div class="col-8 py-3 text-right">
                            <div class="font-weight-bold">{{$salesCount}}</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer px-3 py-2">
                    <a class="font-weight-bold font-xs btn-block text-muted" href="{{route('sale.index')}}">{{__('dashboard.view_more')}} <i class="fa fa-angle-right float-right font-lg"></i></a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card">
                <div class="card-header text-white bg-info">
                    <div class="font-weight-bold">
                        {{__('dashboard.total_sale_monthy')}}
                    </div>
                </div>
                <div class="card-block py-0 px-4 b-t-1">
                    <div class="row">
                        <div class="col-4 b-r-1 py-3">
                            <div class="font-weight-bold">{{__('dashboard.total')}}</div>
                        </div>
                        <div class="col-8 py-3 text-right">
                            <div class="font-weight-bold">{{ $salesCountMonthlyByUser}}</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer px-3 py-2">
                    <a class="font-weight-bold font-xs btn-block text-muted" href="{{route('sale.index')}}">{{__('dashboard.view_more')}}<i class="fa fa-angle-right float-right font-lg"></i></a>
                </div>
            </div>
        </div>
        @endif
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card">
                <div class="card-header text-white bg-success">
                    <div class="font-weight-bold">
                        <span>{{__('dashboard.product_list_for_sale')}}</span>
                    </div>
                </div>
                <div class="card-block py-0 px-4 b-t-1">
                    <div class="row">
                        <div class="col-4 b-r-1 py-3">
                            <div class="font-weight-bold">{{__('dashboard.total')}}</div>
                        </div>
                        <div class="col-8 py-3 text-right">
                            <div class="font-weight-bold">{{ $productCount }}</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer px-3 py-2">
                    <a class="font-weight-bold font-xs btn-block text-muted" href="{{route('product_rrps')}}" target="_blank">{{__('dashboard.view_more')}}<i class="fa fa-angle-right float-right font-lg"></i></a>
                </div>
            </div>
        </div>
    </div>
    @if(Auth::user()->isRoleAdmin() || Auth::user()->isRoleView() || Auth::user()->isRoleEditor())
    <div class="row">
        <div class="col-12 col-md-12 mb-2">
            <div class="card shadow mb-2">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">{{__('dashboard.product')}}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive cus-table">
                    <table class="table table-striped table-bordered">
                        <thead class="bg-primary text-light">
                            <tr>
                                <th>{{ __('product.list.thumbnail') }}</th>
                                <th>{{ __('product.list.product_title') }}</th>
                                @if(Auth::user()->isRoleAdmin() || Auth::user()->isRoleView() || Auth::user()->isRoleEditor())
                                <th>{{ __('product.list.category') }}</th>
                                <th>{{ __('product.list.product_code') }}</th>
                                <th>{{ __('product.list.product_import') }}</th>
                                @endif
                                <th>{{ __('product.list.price') }}</th>
                                <th>{{ __('product.list.price_discount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <div class="thumbnail-cicel">
                                            <img class="thumbnail" src="{{$product->thumbnail? getUploadUrl($product->thumbnail, config('upload.product')) : asset('images/no-thumbnail.jpg') }}" alt="{{$product->thumbnail}}" width="45"/>
                                        </div>
                                    </div>
                                </td>
                                <td>{{$product->title}}</td>
                                @if(Auth::user()->isRoleAdmin() || Auth::user()->isRoleView() || Auth::user()->isRoleEditor())
                                <td>{{$product->category ? $product->category->name : ""}}</td>
                                <td>{{$product->product_code}}</td>
                                <td>{{$product->product_import}}</td>
                                @endif
                                <td class="text-right">{{$product->price}}</td>
                                <td class="text-right">{{$product->price_discount}}</td>
                            </tr>
                        @endforeach 
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $products->appends(request()->query())->links() }}
                </div>
                </div>
            </div>
        </div><!--/col-12-->
        <div class="col-12 col-md-12 mb-4">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">{{__('dashboard.sale')}}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive cus-table">
                    <table class="table table-bordered">
                        <thead class="bg-primary text-light">
                            <tr>
                                <th class="text-center">#</th>
                                 <th>{{__('sale.list.invoice_code')}}</th>
                                <th>{{__('sale.list.customer_name')}}</th>
                                <th>{{__('sale.list.quantity')}}</th>
                                <th>{{__('sale.list.price')}}</th>
                                <th>{{__('sale.list.sale_date')}}</th>
                                <th>{{__('sale.list.staff_name')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $sales as $sale)
                                <tr>
                                    <td class="text-center">
                                        @if ($sale->productSales->count() > 0)
                                        <a href="#slae_{{$sale->id}}" data-toggle="collapse" style="text-decoration: none !important;"><i class="fas fa-plus-circle"></i></a>
                                        @endif
                                    </td>
                                    <td>{{$sale->quotaion_no}}</td>
                                    <td>{{$sale->customer ? $sale->customer->customerFullName() : ''}}</td>
                                    <td>{{$sale->total_quantity}}</td>
                                    <td>{{$sale->total_amount}}</td>
                                    <td>{{date('Y-m-d', strtotime($sale->sale_date))}}</td>
                                    <td>{{$sale->staff ? $sale->staff->getFullnameAttribute() : \Auth::user()->name}}</td>
                                </tr>
                                @if ($sale->productSales->count() > 0)
                                <tr>
                                    <td colspan="7" id="slae_{{$sale->id}}" class="collapse p-0">
                                        <table class="table table-borderless mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-primary">Product Name</th>
                                                    <th class="text-primary">Quantity</th>
                                                    <th class="text-primary">Rate</th>
                                                    <th class="text-primary">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                                $total = 0;
                                            @endphp
                                            @foreach ($sale->productSales as $productSale)
                                                @php
                                                    $total +=$productSale->amount;
                                                @endphp
                                                <tr class="border-sale">
                                                    <td class="w-47">{{$productSale->product ? $productSale->product->title : '' }}</td>
                                                    <td>{{$productSale->quantity}}</td>
                                                    <td>{{$productSale->rate}}</td>
                                                    <td>{{$productSale->amount}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr class="border-sale--top">
                                                    <td colspan="3" class="text-right text-primary">Total</td>
                                                    <td>{{$total}}</td>
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
                <div class="d-flex justify-content-center">
                    {{ $sales->appends(request()->query())->links() }}
                </div>
                @if( Session::has('flash_danger') )
                    <p class="alert text-center {{ Session::get('alert-class', 'alert-danger') }}">
                        <span class="spinner-border spinner-border-sm text-darktext-danger align-middle"></span> {{ Session::get('flash_danger') }}
                    </p>
                @endif
                </div>
            </div>
        </div><!--/col-12-->
    </div>
    @endif
</div>
@endsection
