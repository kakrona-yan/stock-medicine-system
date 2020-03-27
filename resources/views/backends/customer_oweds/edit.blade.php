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
        <a href="{{route('customer_owed.index')}}" 
            class="btn btn-circle btn-primary"
            data-toggle="tooltip" 
            data-placement="left" title="" 
            data-original-title="{{__('customer_owed.sub_title')}}"
        ><i class="fas fa-list"></i> {{__('customer_owed.sub_title')}}</a>
    </div>
    <div class="row mb-2">
        <div class="col-12 tab-card">
            @php
                $customerOwed = 0;
                $amount = $sale->customerOwed ? $sale->customerOwed->amount : $sale->total_amount;
                $receiveAmount = $sale->customerOwed ? $sale->customerOwed->receive_amount : 0;
                $customerOwed = $sale->customerOwed ? $sale->customerOwed->owed_amount : ($amount - $receiveAmount);
            @endphp
            <!-- Circle Buttons -->
            <div class="card mb-4">
                <div id="customer_owedList" class="card-body collapse show">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div id="addcustomer_owed" class="tab-pane active">
                            <form class="form-main" action="{{route('customer_owed.update', $sale->id)}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-2">
                                    <div class="col-12 col-md-4">
                                        <div class="select-group form-group">
                                            <label for="customer_id">{{__('customer_owed.list.customer_id')}}:</label>
                                            <select class="form-control" id="customer_id" name="customer_id" readonly>
                                                <option value="{{ $sale->customer_id }}" selected>{{ $sale->customer->customerFullName() }}</option>
                                            </select>
                                            @if ($errors->has('customer_id'))
                                                <span class="text-danger">
                                                    <strong id="customer_owed_id_error">{{ $errors->first('customer_id') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="select-group form-group">
                                            <label for="sale_id">{{__('customer_owed.list.sale_id')}}:</label>
                                            <select class="form-control" id="sale_id" name="sale_id" readonly>
                                                <option value="{{ $sale->id }}" selected>{{ $sale->quotaion_no }}</option>
                                            </select>
                                            @if ($errors->has('sale_id'))
                                                <span class="text-danger">
                                                    <strong id="customer_owed_id_error">{{ $errors->first('sale_id') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label for="sale_id">{{__('customer_owed.list.receive_date')}}:</label>
                                        <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                                            <input type="text" class="form-control" name="receive_date"
                                                value="{{ old('receive_date', date('Y-m-d')) }}">
                                            <div class="input-group-append">
                                                <div class="input-group-text"><span class="far fa-calendar-alt"></span></div>
                                            </div>
                                        </div>
                                        @if ($errors->has('receive_date'))
                                            <span class="text-danger">
                                                <strong>{{ $errors->first('receive_date') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div><!--/row-->
                                <div class="row mb-2">
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label for="amount">{{__('customer_owed.list.amount')}}:</label>
                                            <input type="text" name="amount" class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}" id="amount" value="{{old('amount', currencyFormat($amount))}}" readonly>
                                            @if ($errors->has('amount'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('amount') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label for="receive_amount">{{__('customer_owed.list.receive_amount')}}:</label>
                                            <input type="text" name="receive_amount" class="form-control {{ $errors->has('receive_amount') ? ' is-invalid' : '' }}" id="receive_amount" 
                                                value="{{old('receive_amount', currencyFormat($receiveAmount))}}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label for="owed_amount">{{__('customer_owed.list.owed_amount')}}:</label>
                                            <input type="text" name="owed_amount" class="form-control" id="owed_amount"
                                                value="{{currencyFormat($customerOwed)}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group w-100 w-md-50 d-inline-flex">
                                    <button type="submit" class="btn btn-circle btn-primary w-50 mw-100 mr-2">{{__('button.pay')}}</button>
                                    <a href="{{route('customer_owed.index')}}" class="btn btn-circle btn-secondary w-50 mw-100">{{__('button.return')}}</a>
                                </div>
                            </form><!--/form-main-->
                        </div><!--/tab-add-customer_owed-->
                    </div>
                </div>
            </div>
        </div>
    </div><!--/row-->
</div>
@endsection
@push('footer-script')
<script>
    (function( $ ){
        $("[name='customer_id'], [name='sale_id']").select2({
            allowClear: false
        });
    })( jQuery );
</script>
@endpush