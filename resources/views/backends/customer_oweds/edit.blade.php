@extends('backends.layouts.master')
@section('title', __('customer_owed.title'))
@section('content')
<div id="customer_owed-list">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between border-bottom mb-3 mb-md-5 pb-3">
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
                $amount = $sale->customerOwed()->exists() ? $sale->customerOwed->amount : $sale->total_amount;
                $receiveAmount = $sale->customerOwed()->exists() ? $sale->customerOwed->receive_amount : 0;
                $customerOwed = $sale->customerOwed()->exists() ? $sale->customerOwed->owed_amount : ($amount - $receiveAmount);
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
                                        <div class="form-group select-group">
                                            <label for="amount">{{__('customer_owed.list.discount_type')}}:</label>
                                            @php
                                                $discount = $sale->customerOwed()->exists() ? $sale->customerOwed->discount_type : 1;
                                            @endphp
                                            <select class="form-control" id="discount_type" name="discount_type">
                                                @foreach ($discountType as $key => $type)
                                                <option value="{{ $key }}" {{ $discount == $key ? 'selected' : ''}}>{{ $type}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <div class="form-group">
                                            <label for="discount_amount">{{__('customer_owed.list.discount_amount')}}:</label>
                                            @php
                                            $discountAmount = $sale->customerOwed()->exists() ? $sale->customerOwed->discount_amount : 0;
                                            @endphp
                                            <input type="text" name="discount_amount" class="form-control" id="discount_amount" 
                                                value="{{old('discount_amount',  $discountAmount)}}"
                                                oninput="calculatorDiscountMoney(this)"
                                                >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6 col-md-3">
                                        <div class="form-group">
                                            <label for="amount_pay">{{__('customer_owed.list.amount_pay')}}:</label>
                                            @php
                                                $amountPay = $sale->customerOwed()->exists() ? $sale->customerOwed->amount_pay : $amount;
                                            @endphp
                                            <input type="text" name="amount_pay" class="form-control {{ $errors->has('amount_pay') ? ' is-invalid' : '' }}" id="amount_pay" value="{{old('amount_pay', currencyFormat($amountPay))}}" readonly>
                                            @if ($errors->has('amount_pay'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('amount_pay') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="form-group">
                                            <label for="receive_amount">{{__('customer_owed.list.receive_amount')}}:</label>
                                            <input type="text" name="receive_amount" class="form-control {{ $errors->has('receive_amount') ? ' is-invalid' : '' }}" id="receive_amount" 
                                                value="{{old('receive_amount', currencyFormat($receiveAmount))}}"
                                                oninput="calculatorMoney(this)"
                                                >
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="form-group">
                                            <label for="owed_amount">{{__('customer_owed.list.owed_amount')}}:</label>
                                            <input type="text" name="owed_amount" class="form-control" id="owed_amount"
                                                value="{{currencyFormat($customerOwed)}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="fom-group w-100 w-md-50 mb-3">
                                            <label for="sale_id">{{__('customer_owed.list.date_pay')}}:</label>
                                            <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                                                @php
                                                $date_pay = $sale->customerOwed()->exists() ? $sale->customerOwed->date_pay :  date('Y-m-d');
                                                @endphp
                                                <input type="text" class="form-control" name="date_pay"
                                                    value="{{ old('date_pay', $date_pay) }}">
                                                <div class="input-group-append">
                                                    <div class="input-group-text"><span class="far fa-calendar-alt"></span></div>
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label class="form-check form-check-inline align-top" for="status_pay">{{ __('customer_owed.list.status_pay') }}:</label>
                                        @foreach ($statusPays as $key => $statusPay)
                                            <div class="form-check form-check-inline">
                                            <input style="margin-top:-3px" class="form-check-input" type="radio" name="status_pay" id="status_pay_{{$key}}" 
                                                value="{{$key}}" {{old('status_pay', $sale->customerOwed()->exists() ? $sale->customerOwed->status_pay : 2) == $key ? 'checked' : ''}}>
                                                <label class="form-check-label" for="status_pay_{{$key}}">{{$statusPay}}</label>
                                            </div>  
                                        @endforeach                        
                                    </div>
                                </div>
                                <div class="form-group w-100 w-md-50 d-inline-flex">
                                    <button type="submit" class="btn btn-circle btn-primary w-100 w-md-50 mw-100 mr-2">{{__('button.pay')}}</button>
                                    <a href="{{route('customer_owed.index')}}" class="btn btn-circle btn-secondary w-100 w-md-50 mw-100">{{__('button.return')}}</a>
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
    var discount_type = $(".discount_type").val();
    (function( $ ){
        $("[name='customer_id'], [name='sale_id']").select2({
            allowClear: false,
        });
        $("[name='discount_type']").select2({
            allowClear: false,
        }).on('select2:select', function (e) {
            discount_type = e.params.data.id;
            console.log(discount_type);
            
            switch (discount_type) {
                case 0:
                    var amount = Number($("#amount").val());
                    var discountAmount = Number($("#discount_amount").val()) / 100;
                    var totalAmount = amount - (amount * discountAmount);
                    $("#amount_pay").val(totalAmount.toFixed(2));
                    break;
                case 1:
                    var amount = Number($("#amount").val());
                    var discountAmount = Number($("#discount_amount").val());
                    var totalAmount = (amount - discountAmount);
                    $("#amount_pay").val(totalAmount.toFixed(2));
                    break;
                default:
                    var amount = Number($("#amount").val());
                    var discountAmount = Number($("#discount_amount").val());
                    var totalAmount = (amount - discountAmount);
                    $("#amount_pay").val(totalAmount.toFixed(2));
                    break;
            }
        });
        $('#discount_amount').keypress(function(event) {
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
    })( jQuery );

    function calculatorMoney(data) {
        let revicePrice = $(data)[0] ? $(data)[0].value : 0;
        let totalAmountPayment = $('input[name="amount_pay"]').val();
        let moneyOwed = Number(totalAmountPayment)- Number(revicePrice);
        $("#owed_amount").val(moneyOwed.toFixed(2));
    }

    function calculatorDiscountMoney(data) {
        switch (discount_type) {
            case 0:
                var amount = Number($("#amount").val());
                var discountAmount = Number($(data)[0] ? $(data)[0].value : 0) / 100;
                var totalAmount = amount - (amount * discountAmount);
                $("#amount_pay").val(totalAmount.toFixed(2));
                break;
            case 1:
                var amount = Number($("#amount").val());
                var discountAmount = Number($(data)[0] ? $(data)[0].value : 0);
                var totalAmount = (amount - discountAmount);
                $("#amount_pay").val(totalAmount.toFixed(2));
                break;
            default:
                var amount = Number($("#amount").val());
                var discountAmount = Number($(data)[0] ? $(data)[0].value : 0);
                var totalAmount = (amount - discountAmount);
                $("#amount_pay").val(totalAmount.toFixed(2));
                break;
        }
    }

</script>
@endpush