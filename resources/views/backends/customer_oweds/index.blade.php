@extends('backends.layouts.master')
@section('title', __('customer_owed.title'))
@section('content')
<div id="customer_owed-list">
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
                    <span class="sub-title">{{ __('customer_owed.sub_title') }}</span>
                </li>
            </ol>
        </nav>
    </div>
    <!--list product-->
    @include('backends.customer_oweds.include._list_customer_owed')
</div>

@endsection