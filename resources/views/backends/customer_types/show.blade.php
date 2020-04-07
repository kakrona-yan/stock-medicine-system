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
                        <span class="sub-title">ប្រភេទ{{ __('customer.sub_title') }}</span>
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
    @endif
    <div class="row mb-2">
        <div class="col-12 tab-card">
            <!-- Circle Buttons -->
            <div class="card mb-4">
                <div id="customerList" class="card-body collapse show">
                    <!-- Tab panes -->
                    <div class="tab-content w-100 w-md-50">
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
                                        <button type="submit" class="btn btn-circle btn-primary w-100 w-md-50 mw-100 mr-2">{{__('button.edit')}}</button>
                                        <a href="{{route('customer_type.index')}}" class="btn btn-circle btn-secondary w-100 w-md-50 mw-100">{{__('button.return')}}</a>
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