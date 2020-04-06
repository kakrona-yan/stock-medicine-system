@extends('backends.layouts.master')
@section('title', __('customer.title'))
@section('content')
<div id="customer-list">
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
                    <span class="sub-title">{{ __('customer.sub_title') }}</span>
                </li>
            </ol>
        </nav>
    </div>
    <!--list product-->
    @include('backends.customer_types.include._list_customer')
    @include('backends.customers.include._list_customer')
</div>

@endsection