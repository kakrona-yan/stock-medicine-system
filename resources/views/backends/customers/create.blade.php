@extends('backends.layouts.master')
@section('title', __('customer.title'))
@section('content')
<div id="customer-list">
    @if(\Auth::user()->isRoleStaff())
    <div class="row {{Auth::user()->isRoleStaff() ? 'sp-staff-block' : ''}}">
        <div class="col-6 mb-4 p-1">
            <div class="card shadow py-2 border-success">
                <div class="card-body text-center">
                    <a href="{{route('sale.index')}}"><i class="far fa-newspaper text-warning"></i> {{__('menu.sale')}}</a>
                </div>
            </div>
        </div>
        <div class="col-6  mb-4 p-1">
            <div class="card shadow py-2 border-success">
                <div class="card-body text-center">
                    <a href="{{route('customer.index')}}"><i class="fas fa-users text-danger"></i> {{__('menu.customer')}}</a>
                </div>
            </div>
        </div>
    </div>
    @else
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
                    <span class="sub-title">{{ __('customer.sub_title') }}</span>
                </li>
            </ol>
        </nav>
        <a href="{{route('customer.index')}}" 
            class="btn btn-circle btn-primary"
            data-toggle="tooltip" 
            data-placement="left" title="" 
            data-original-title="{{__('customer.sub_title')}}"
        ><i class="fas fa-list"></i> {{__('customer.sub_title')}}</a>
    </div>
    @endif
    <div class="row mb-2">
        <div class="col-12 tab-card">
            <!-- Circle Buttons -->
            <div class="card mb-4">
                <div id="customerList" class="card-body collapse show">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div id="addcustomer" class="tab-pane active">
                            <form class="form-main" action="{{route('customer.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-md-6 mb-2">
                                        <div class="row">
                                            <div class="form-group col-12 col-md-6">
                                                <label for="name">{{__('customer.list.name')}}:</label>
                                                <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" 
                                                    placeholder="{{__('customer.list.name')}}"
                                                    name="name"
                                                    value="{{ old('name', $request->name) }}"
                                                >
                                                @if ($errors->has('name'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="select-group form-group col-12 col-md-6">
                                                <label for="name">{{__('customer.customer_type')}}:</label>
                                                <select class="form-control" id="customer_type_id" name="customer_type_id">
                                                    @foreach($customerTypes as $id => $name)
                                                        <option value="{{ $id }}" {{ $id == $request->customer_type_id ? 'selected' : '' }}>{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger">
                                                    <strong id="customer_id_error">{{ $errors->first('customer_type_id') }}</strong>
                                                </span>
                                            </div>
                                        </div><!--/row-->
                                        <div class="form-group">
                                            <label for="address">{{__('customer.list.address')}}:</label>
                                            <textarea name="address" class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}" id="address" rows="5">{{old('address', $request->address)}}</textarea>
                                            @if ($errors->has('address'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('address') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="address">{{__('customer.list.map_link')}}:</label>
                                            <textarea name="map_address" class="form-control" id="map_address" rows="5">{{old('map_address', $request->map_address)}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="phone1">{{__('customer.list.phone')}}:</label>
                                            <div class="row">
                                                <div class="col-6">
                                                    <input type="text" class="form-control {{ $errors->has('phone1') ? ' is-invalid' : '' }}" 
                                                        name="phone1"
                                                        value="{{ old('phone1', $request->phone1) }}">
                                                    @if ($errors->has('phone1'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('phone1') }}</strong>
                                                    </span>
                                                    @endif
                                                </div>
                                                <div class="col-6">
                                                    <input type="text" class="form-control {{ $errors->has('phone2') ? ' is-invalid' : '' }}" 
                                                        name="phone2"
                                                        value="{{ old('phone2', $request->phone2) }}"
                                                    >
                                                    @if ($errors->has('phone2'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('phone2') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="thumbnail">{{__('customer.form.thumbnail')}}:</label>
                                            <div class="upload-profile img-upload">
                                                <div class="img-file-tab">
                                                    <div>
                                                        <span class="btn btn-circle btn-file img-select-btn btn-circle btn-block">
                                                            <i class="fa fa-fw fa-upload"></i> <span>{{__('customer.form.add_thumbnail')}}</span>
                                                            <input type="file" name="thumbnail">
                                                        </span>
                                                    </div>
                                                    <span class="btn btn-circle img-remove-btn"><i class="fa fa-fw fa-times"></i>{{__('button.delete')}}</span>
                                                </div>
                                            </div>
                                            @if ($errors->has('thumbnail'))
                                                <div class="text-danger">
                                                    <strong>{{ $errors->first('thumbnail') }}</strong>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div><!--/row-->
                                <div class="form-group w-100 w-md-50 d-inline-flex">
                                    <button type="submit" class="btn btn-circle btn-primary w-100 w-md-50 mw-100 mr-2">{{__('button.add')}}</button>
                                    <a href="{{route('customer.index')}}" class="btn btn-circle btn-secondary w-100 w-md-50 mw-100">{{__('button.return')}}</a>
                                </div>
                            </form><!--/form-main-->
                        </div><!--/tab-add-customer-->
                    </div>
                </div>
            </div>
        </div>
    </div><!--/row-->
</div>
@endsection
@push('footer-script')
<script type="text/javascript" src="{{asset('/js/imageupload.js')}}"></script>
<script>
    // script for upload image
    $('.img-upload').imgUpload();
    $('#customer_type_id').select2({
        allowClear: false
    });
</script>
@endpush