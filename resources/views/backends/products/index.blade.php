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
                    <span class="sub-title">{{ __('product.sub_title') }}</span>
                </li>
            </ol>
        </nav>
    </div>
    <!--list category-->
    <a href="{{route('category.create')}}" 
            class="btn btn-circle btn-primary mb-2"
            data-toggle="tooltip" 
            data-placement="left" title="" 
            data-original-title="{{__('button.add_new')}}"
        ><i class="fas fa-plus-circle"></i> ប្រភេទផលិតផល{{__('button.add_new')}}</a>
    @include('backends.categories.include._list_category')
    <!--list product-->
    <a href="{{route('product.create')}}" 
            class="btn btn-circle btn-primary mb-2"
            data-toggle="tooltip" 
            data-placement="left" title="" 
            data-original-title="{{__('button.add_new')}}"
        ><i class="fas fa-plus-circle"></i> ផលិតផល{{__('button.add_new')}}</a>
    @include('backends.products.include._list_product')
</div>

@endsection