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
                        <span class="sub-title">{{ __('customer.sub_title') }} Type</span>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <span class="sub-title">{{  $customerType->name }}</span>
                </li>
            </ol>
        </nav>
        <a href="{{route('customer.index')}}" 
            class="btn btn-circle btn-primary"
            data-toggle="tooltip" 
            data-placement="left" title="" 
            data-original-title="{{__('customer.sub_title')}}"
        ><i class="fas fa-list"></i> {{__('customer.sub_title')}} Type</a>
    </div>
    <div class="row mb-2">
        <div class="col-12 tab-card">
            <!-- Circle Buttons -->
            <div class="card mb-4">
                <div id="customerList" class="card-body collapse show">
                    <!-- Tab panes -->
                    <div class="tab-content w-50">
                        <div id="addcustomer" class="tab-pane active">
                            <table class="table table-show table-hover">
                                <tbody class="border">
                                    <tr>
                                        <th class="border-top-0 w-15" style="width:30px;">{{__('customer.list.name')}}:</th>
                                        <td class="border-top-0">{{ $customerType->name }}</td>
                                    </tr>
                                </tbody>
                              </table>
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="form-group w-100 d-inline-flex">
                                        <button type="submit" class="btn btn-circle btn-primary w-50 mw-100 mr-2">{{__('button.edit')}}</button>
                                        <a href="{{route('customer_type.index')}}" class="btn btn-circle btn-secondary w-50 mw-100">{{__('button.return')}}</a>
                                    </div>
                                </div>
                            </div>
                        </div><!--/tab-add-customer-->
                    </div>
                </div>
            </div>
        </div>
    </div><!--/row-->
</div>
@endsection