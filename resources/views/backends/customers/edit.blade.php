@extends('backends.layouts.master')
@section('title', __('customer.form.title'))
@section('content')
<div id="customer-list">
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
                    <a href="{{ route('customer.index') }}">
                        <span class="sub-title">{{ __('customer.sub_title') }}</span>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <span class="sub-title">{{  $customer->name }}</span>
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
    <div class="row mb-2">
        <div class="col-12 tab-card">
            <!-- Circle Buttons -->
            <div class="card mb-4">
                <div id="customerList" class="card-body collapse show">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div id="addcustomer" class="tab-pane active">
                            <form class="form-main" action="{{route('customer.update', $customer->id)}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-md-6 mb-2">
                                        <div class="form-group">
                                            <label for="name">{{__('customer.list.name')}}:</label>
                                            <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" 
                                                placeholder="{{__('customer.list.name')}}"
                                                name="name"
                                                value="{{ old('name', $customer->name) }}"
                                            >
                                            @if ($errors->has('name'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="address">{{__('customer.list.address')}}:</label>
                                            <textarea name="address" class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}" id="address" rows="5">{{old('address', $customer->address)}}</textarea>
                                            @if ($errors->has('address'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('address') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="phone1">{{__('customer.list.phone')}}:</label>
                                            <div class="row">
                                                <div class="col-6">
                                                    <input type="text" class="form-control {{ $errors->has('phone1') ? ' is-invalid' : '' }}" 
                                                        name="phone1"
                                                        value="{{ old('phone1', $customer->phone1) }}">
                                                    @if ($errors->has('phone1'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('phone1') }}</strong>
                                                    </span>
                                                    @endif
                                                </div>
                                                <div class="col-6">
                                                    <input type="text" class="form-control {{ $errors->has('phone2') ? ' is-invalid' : '' }}" 
                                                        name="phone2"
                                                        value="{{ old('phone2', $customer->phone2) }}"
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
                                                    <img class="thumbnail" src="{{$customer->thumbnail? getUploadUrl($customer->thumbnail, config('upload.customer')) : asset('images/no-avatar.jpg') }}"/>
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
                                <div class="form-group w-50 d-inline-flex">
                                    <button type="submit" class="btn btn-circle btn-primary w-25 mr-2">{{__('button.add')}}</button>
                                    <a href="{{route('customer.index')}}" class="btn btn-circle btn-secondary w-25">{{__('button.return')}}</a>
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
</script>
@endpush
