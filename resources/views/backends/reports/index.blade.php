@extends('backends.layouts.master')
@section('title', __('sale.sub_title'))
@section('content')
<div id="category-list">
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
                    <span class="sub-title">{{__('report.title_sale')}}</span>
                </li>
            </ol>
        </nav>
    </div>
</div>
@include('backends.reports.include._list')
@endsection
@push('footer-script')
<script>
    (function( $ ){
        $(".report_customer, .report_staff").select2({
            allowClear: false
        });
    })( jQuery );
</script>
@endpush