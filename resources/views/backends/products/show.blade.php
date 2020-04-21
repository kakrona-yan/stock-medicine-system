@extends('backends.layouts.master')
@section('title', __('product.title'))
@section('content')
<div id="product-list">
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
                    <a href="{{ route('product.index') }}">
                        <span class="sub-title">{{ __('product.sub_title') }}</span>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <span class="sub-title">{{  $product->name }}</span>
                </li>
            </ol>
        </nav>
        <a href="{{route('product.index')}}" 
            class="btn btn-circle btn-primary"
            data-toggle="tooltip" 
            data-placement="left" title="" 
            data-original-title="{{__('product.sub_title')}}"
        ><i class="fas fa-list"></i> {{__('product.sub_title')}}</a>
    </div>
    <div class="row mb-2">
        <div class="col-12 tab-card">
            <!-- Circle Buttons -->
            <div class="card mxy-4">
                <div class="card-body">
                    <div class="table-responsive cus-table">
                        <table class="table table-show table-hover">
                            <tbody class="border">
                                <tr>
                                    <th>{{__('product.list.thumbnail')}}</th>
                                    <td>
                                        <div class="thumbnail-cicel" style="width:100px; height:100px;">
                                            <img class="thumbnail" src="{{$product->thumbnail? getUploadUrl($product->thumbnail, config('upload.product')) : asset('images/no-avatar.jpg') }}" alt="{{$product->name}}" width="45"/>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('product.list.product_title') }}</th>
                                    <td>{{$product->title}}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('product.list.category') }}</th>
                                    <td>{{$product->category ? $product->category->name : ''}}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('product.list.product_import') }}</th>
                                    <td>{{$product->product_import}}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('product.list.price') }}</th>
                                    <td>{{$product->price}}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('product.list.in_store') }}</th>
                                    <td>{{$product->in_store}}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('product.list.product_free') }}</th>
                                    <td>{{$product->product_free}}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('product.list.terms') }}</th>
                                    <td>{{$product->terms}}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('product.list.expird_date') }}</th>
                                    <td>{{$product->expird_date ? date('Y-m', strtotime($product->expird_date)) : "-"}}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('product.list.amount_in_box') }}</th>
                                    <td>{{$product->amount_in_box}}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('product.list.active') }}</th>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" data-toggle="toggle" data-onstyle="success" name="active"
                                            {{ $product->is_active == 1 ? 'checked' : '' }}
                                            disabled
                                            > 
                                            <span class="slider"><span class="on">ON</span><span class="off">OFF</span>
                                            </span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="w-20">{{__('product.list.action')}}</th>                
                                    <td>
                                        <div class="w-100">
                                        <a class="btn btn-sm btn-info btn-circle" 
                                            data-toggle="tooltip" 
                                            data-placement="top"
                                            data-original-title="{{__('button.show')}}"
                                            href="{{route('product.show', $product->id)}}"
                                        ><i class="far fa-eye"></i>
                                        </a>
                                        <a class="btn btn-sm btn-warning btn-circle" 
                                            data-toggle="tooltip" 
                                            data-placement="top"
                                            data-original-title="{{__('button.edit')}}"
                                            href="{{route('product.edit', $product->id)}}"
                                        ><i class="far fa-edit"></i>
                                        </a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group d-inline-flex mb-3">
                        <a href="{{route('product.edit', $product->id)}}" class="btn btn-circle btn-primary w-btn-125 mr-2">{{__('button.edit')}}</a>
                        <a href="{{route('product.index')}}" class="btn btn-circle btn-secondary w-btn-125">{{__('button.return')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div><!--/row-->
</div>
@endsection