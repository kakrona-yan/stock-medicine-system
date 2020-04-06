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
                    <span class="sub-title">{{__('sale.sub_title')}}</span>
                </li>
            </ol>
        </nav>
        <a href="{{route('sale.create')}}" class="btn btn-circle btn-primary" data-toggle="tooltip" data-placement="left" title="" data-original-title="{{__('button.add_new')}}"><i class="fas fa-plus-circle"></i> {{__('button.add_new')}}</a>
    </div>
</div>
@include('backends.sales.include._list_sale')
@endsection