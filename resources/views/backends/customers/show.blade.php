@extends('backends.layouts.master')
@section('title', __('customer.form.title'))
@section('content')
<div id="customer-list">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between border-bottom mb-5 pb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-0">
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
        <a href="{{route('customer.index')}}" 
            class="btn btn-circle btn-primary"
            data-toggle="tooltip" 
            data-placement="left" title="" 
            data-original-title="{{__('customer.sub_title')}}"
        ><i class="fas fa-list"></i> {{__('customer.sub_title')}}</a>
    </div>
    <div class="row mb-2">
        <div class="col-12 tab-card">
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
                                        <td class="border-top-0">{{ $customer->name }}</td>
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
                                            <div class="w-50">
                                                <img class="img-thumbnail" src="{{$customer->thumbnail? getUploadUrl($customer->thumbnail, config('upload.customer')) : asset('images/no-avatar.jpg') }}" />
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                              </table>
                            <div class="form-group w-50 d-inline-flex">
                                <a href="{{route('customer.edit', $customer->id)}}" class="btn btn-circle btn-primary w-25 mr-2">{{__('button.edit')}}</a>
                                <a href="{{route('customer.index')}}" class="btn btn-circle btn-secondary w-25">{{__('button.return')}}</a>
                            </div>
                        </div><!--/tab-add-customer-->
                    </div>
                </div>
            </div>
        </div>
    </div><!--/row-->
</div>
@endsection