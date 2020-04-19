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
    <!--list user-->
    <a href="{{route('user.create')}}" 
        class="btn btn-circle btn-primary mb-2"
        data-toggle="tooltip" 
        data-placement="left" title="" 
        data-original-title="{{__('button.add_new')}}"
    ><i class="fas fa-plus-circle"></i> អ្នកប្រើប្រាស់{{__('button.add_new')}}</a>
    @include('backends.users.include._list_user')
     <!--list staff-->
    <a href="{{route('staff.create')}}" 
     class="btn btn-circle btn-primary mb-2"
     data-toggle="tooltip" 
     data-placement="left" title="" 
     data-original-title="{{__('button.add_new')}}"
 ><i class="fas fa-plus-circle"></i> បុគ្គលិក{{__('button.add_new')}}</a>
    @include('backends.staffs.include._list_staff')
</div>

@endsection