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
    <!--list product-->
    <ul class="nav nav-tabs nav-justified mb-2" role="tablist">
        <li class="nav-item">
            <a class="nav-link bg-success text-white" href="{{route("customer_owed.index")}}">សងប្រាក់តាមថ្ងៃ</a>
        </li>
        <li class="nav-item">
            <a class="nav-link bg-danger text-white" href="{{route("customer_owed.some_pay")}}">សង​ប្រាក់​ហើយ​តាម​ថ្ងៃ</a>
        </li>
        <li class="nav-item">
            <a class="nav-link bg-info text-white " href="{{route("customer_owed.all_pay")}}">សងប្រាក់ទាំងអស់</a>
        </li>
    </ul>
    @include('backends.customer_oweds.include._list_customer_all_pay')
</div>

@endsection