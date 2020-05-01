@extends('backends.layouts.master')
@section('title', __('user.title'))
@section('content')
<div id="user-list">
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
                    <span class="sub-title">{{ __('user.sub_title') }}</span>
                </li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-6 col-md-3 mb-4">
            <div class="card">
                <div class="card-header text-white bg-info">
                    <div class="font-weight-bold">
                        <span>{{__('customer.sub_title')}}</span>
                    </div>
                </div>
                <div class="card-block py-3 px-4 b-t-1">
                    <a class="font-weight-bold font-xs btn-block text-muted" href="{{route('staff.index')}}">
                        {{__('dashboard.view_more')}} <i class="fa fa-angle-right float-right font-lg"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-4">
            <div class="card">
                <div class="card-header text-white bg-success">
                    <div class="font-weight-bold">
                        <span>ចំនួនអ្នកមកធ្វើការថ្ងៃនេះ</span>
                    </div>
                </div>
                <div class="card-block py-3 px-4 b-t-1">
                    10 
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-4">
            <div class="card">
                <div class="card-header text-white bg-danger">
                    <div class="font-weight-bold">
                        <span>ចំនួនអ្នកអត់មកធ្វើការថ្ងៃនេះ</span>
                    </div>
                </div>
                <div class="card-block py-3 px-4 b-t-1">
                    2  
                </div>
            </div>
        </div>
    </div>
    <a href="{{route('staff.create')}}" 
     class="btn btn-circle btn-primary mb-2"
     data-toggle="tooltip" 
     data-placement="left" title="" 
     data-original-title="{{__('button.add_new')}}"
    ><i class="fas fa-plus-circle"></i> បុគ្គលិក{{__('button.add_new')}}</a>
    <!--list user-->
    <fieldset class="edit-master-registration-fieldset mt-2">
        <legend class="edit-application-information-legend text-left">{{__('user.sub_title')}}</legend>
        @include('backends.users.include._list_user')
    </fieldset>
</div>

@endsection