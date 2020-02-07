@extends('backends.layouts.master')
@section('title', __('user.title'))
@section('content')
<div id="user-list">
     <!-- Page Heading -->
     <div class="d-sm-flex align-items-center justify-content-between border-bottom mb-3 pb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-0">
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
        <a href="{{route('user.create')}}" 
            class="btn btn-circle btn-primary"
            data-toggle="tooltip" 
            data-placement="left" title="" 
            data-original-title="{{__('button.add_new')}}"
        ><i class="fas fa-plus-circle"></i> {{__('button.add_new')}}</a>
    </div>
    <!--list user-->
    @include('backends.users.include._list_user')
</div>

@endsection