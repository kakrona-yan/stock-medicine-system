@extends('backends.layouts.master')
@section('title', __('customer_owed.title'))
@section('content')
@push("header-style")
<style>
.nav-tabs .nav-link {
    border-radius: 0px;
}
</style>
@endpush
<div id="customer_owed-list">
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
                    <span class="sub-title">{{ __('customer_owed.sub_title') }}</span>
                </li>
            </ol>
        </nav>
    </div>
    <div class="my-3">
        <a class="btn btn-circle btn-primary mb-2"
            href="{{route('customer_owed.download-pdf')}}"
            target="_blank"
            ><i class="far fa-file-pdf mr-2"></i>ReportPDF
        </a>
    </div>
    <!--list product-->
    <ul class="nav nav-tabs nav-justified mb-2" role="tablist">
        <li class="nav-item">
            <a class="nav-link bg-success text-white" href="{{route("customer_owed.index")}}">មិនទាន់សង</a>
        </li>
        <li class="nav-item">
            <a class="nav-link bg-danger text-white" href="{{route("customer_owed.some_pay")}}">សង​បានខ្លះ</a>
        </li>
        <li class="nav-item">
            <a class="nav-link bg-info text-white " href="{{route("customer_owed.all_pay")}}">សងប្រាក់ហើយ</a>
        </li>
    </ul>
    @include('backends.customer_oweds.include._list_customer_owed')
</div>

@endsection