@extends('backends.layouts.master')
@section('title', 'RRPS-PHARMA | '.__('dashboard.title'))
@section('content')
<div id="setting">
    <!-- Page Heading -->
    @if(!\Auth::user()->isRoleStaff())
    <div class="border-bottom mb-3 pb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-3">
                <li class="breadcrumb-item">
                    <a href="{{route('dashboard')}}">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        ផ្ទាំងគ្រប់គ្រង
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <span class="sub-title">Setting</span>
                </li>
            </ol>
        </nav>
    </div>
    @else
        <div class="row {{Auth::user()->isRoleStaff() ? 'sp-staff-block' : ''}}">
            <div class="col-6 mb-4 p-1">
                <div class="card shadow py-2 border-success">
                    <div class="card-body text-center">
                        <a href="{{route('sale.index')}}"><i class="far fa-newspaper text-warning"></i> {{__('menu.sale')}}</a>
                    </div>
                </div>
            </div>
            <div class="col-6 mb-4 p-1">
                <div class="card shadow py-2 border-success">
                    <div class="card-body text-center">
                        <a href="{{route('customer.index')}}"><i class="fas fa-users text-danger"></i> {{__('menu.customer')}}</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- Content Row -->
    <div class="row">
        <div class="col-12 col-md-3 mb-4">
            <div class="card">
                <div class="card-header text-white bg-info">
                    <div class="font-weight-bold">
                        <span>{{__('customer.sub_title')}}គ្រប់គ្រងដោយបុគ្គលិក</span>
                    </div>
                </div>
                <div class="card-block py-3 px-4 b-t-1">
                    <div class="d-flex align-items-center" style="height:130px;">
                    <a class="font-weight-bold font-xs btn-block text-muted" href="{{route('setting.staff_to_customer')}}">
                        មើលលំអិត <i class="fa fa-angle-right float-right font-lg"></i>
                    </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 mb-4">
            <div class="card">
                <div class="card-header text-white bg-success">
                    <div class="font-weight-bold">
                        <span>QR-CODE LINE(RRPS-PhARMA-BOT)</span>
                    </div>
                </div>
                <div class="card-block py-3 px-4 b-t-1">
                    <div class="row">
                        <div class="col-5">
                            <div class="d-flex align-items-center" style="width: 130px;margin: 0px auto;height:130px;">
                                <img src="{{asset('images/QR-code-line-bot.png')}}" style="max-width:100%">
                            </div>
                        </div>
                        <div class="col-7">
                            <p class="mb-0">ពាក្យសម្ងាត់</p>
                            <p class="mb-0">① username</p>
                            <p class="mb-0">② phone number</p>
                            <p class="mb-0">③ checkin</p>
                            <p class="mb-0">④ checkout</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
