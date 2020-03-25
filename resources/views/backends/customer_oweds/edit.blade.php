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
            <!-- Circle Buttons -->
            <div class="card mb-4">
                <div id="customer_owedList" class="card-body collapse show">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div id="addcustomer_owed" class="tab-pane active">
                            <form class="form-main" action="{{route('customer_owed.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-2">
                                    <div class="col-6 col-md-4">
                                        <div class="select-group form-group">
                                            <label for="customer_id">{{__('customer_owed.list.customer_id')}}:</label>
                                            <select class="form-control" id="customer_id" name="customer_id">
                                                @foreach($customers as  $customer)
                                                    <option value="{{ $customer->id }}" {{ $customer->id == $request->customer_id ? 'selected' : '' }}>{{ $customer->customerFullName() }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger">
                                                <strong id="customer_owed_id_error">{{ $errors->first('customer_id') }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <div class="select-group form-group">
                                            <label for="sale_id">{{__('customer_owed.list.sale_id')}}:</label>
                                            <select class="form-control" id="sale_id" name="sale_id">
                                               
                                            </select>
                                            <span class="text-danger">
                                                <strong id="customer_owed_id_error">{{ $errors->first('customer_id') }}</strong>
                                            </span>
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
                                    <div class="col-6 col-md-4">
                                        <div class="form-group">
                                            <label for="amount">{{__('customer_owed.list.amount')}}:</label>
                                            <input type="text" name="amount" class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}" id="amount" value="{{old('amount', $request->amount)}}">
                                            @if ($errors->has('amount'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('amount') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <div class="form-group">
                                            <label for="receive_amount">{{__('customer_owed.list.receive_amount')}}:</label>
                                            <input type="text" name="amount" class="form-control {{ $errors->has('receive_amount') ? ' is-invalid' : '' }}" id="receive_amount" value="{{old('receive_amount', $request->receive_amount)}}">
                                            @if ($errors->has('receive_amount'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('receive_amount') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label for="owed_amount">{{__('customer_owed.list.owed_amount')}}:</label>
                                            <input type="text" name="amount" class="form-control {{ $errors->has('owed_amount') ? ' is-invalid' : '' }}" id="owed_amount" value="{{old('owed_amount', $request->owed_amount)}}">
                                            @if ($errors->has('owed_amount'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('owed_amount') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group w-50 d-inline-flex">
                                    <button type="submit" class="btn btn-circle btn-primary w-50 mw-100 mr-2">{{__('button.add')}}</button>
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
    // script for upload image
    $('#customer_id').select2({
        allowClear: false
    });
    $('#sale_id').select2({
        allowClear: false
    });
</script>
@endpush