@extends('backends.layouts.master')
@section('title', __('staff.title'))
@section('content')
<div id="staff-list">
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
                    <span class="sub-title">{{ __('staff.sub_title') }}</span>
                </li>
            </ol>
        </nav>
        <a href="{{route('staff.index')}}" 
            class="btn btn-circle btn-primary"
            data-toggle="tooltip" 
            data-placement="left" title="" 
            data-original-title="{{__('staff.sub_title')}}"
        ><i class="fas fa-list"></i> {{__('staff.sub_title')}}</a>
    </div>
    <div class="row mb-2">
        <div class="col-12 tab-card">
            <!-- Circle Buttons -->
            <div class="card mb-4">
                <div id="staffList" class="card-body collapse show">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div id="addstaff" class="tab-pane active">
                            <form class="form-main" action="{{route('staff.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-md-6 mb-2">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-6">
                                                    <label for="lastname">{{__('staff.list.lastname')}}:</label>
                                                    <input type="text" class="form-control {{ $errors->has('lastname') ? ' is-invalid' : '' }}" 
                                                        placeholder="{{__('staff.list.lastname')}}"
                                                        name="lastname"
                                                        value="{{ old('lastname', $request->lastname) }}">
                                                    @if ($errors->has('lastname'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('lastname') }}</strong>
                                                    </span>
                                                    @endif
                                                </div>
                                                <div class="col-6">
                                                    <label for="lastname">{{__('staff.list.firstname')}}:</label>
                                                    <input type="text" class="form-control {{ $errors->has('firstname') ? ' is-invalid' : '' }}" 
                                                        placeholder="{{__('staff.list.firstname')}}"
                                                        name="firstname"
                                                        value="{{ old('firstname', $request->firstname) }}">
                                                    @if ($errors->has('firstname'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('firstname') }}</strong>
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                         <div class="form-group">
                                            <label for="password">{{__('user.list.password')}}:</label>
                                            <input type="text" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" 
                                                placeholder="password"
                                                name="password" value="{{old('password', $request->password)}}"
                                            >
                                            @if ($errors->has('password'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="email">{{__('staff.list.email')}}:</label>
                                            <input type="emal" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" 
                                                placeholder="{{__('staff.list.email')}}"
                                                name="email"
                                                value="{{ old('email', $request->email) }}"
                                            >
                                            @if ($errors->has('email'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                
                                        </div>
                                        <div class="form-group">
                                            <label for="address">{{__('staff.list.address')}}:</label>
                                            <textarea name="address" class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}" id="address" rows="5">{{old('address', $request->address)}}</textarea>
                                            @if ($errors->has('address'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('address') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="phone1">{{__('staff.list.phone')}}:</label>
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
                                            <label for="thumbnail">{{__('staff.form.thumbnail')}}:</label>
                                            <div class="upload-profile img-upload">
                                                <div class="img-file-tab">
                                                    <div>
                                                        <span class="btn btn-circle btn-file img-select-btn btn-circle btn-block">
                                                            <i class="fa fa-fw fa-upload"></i> <span>{{__('staff.form.add_thumbnail')}}</span>
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
                                <div class="form-group w-50 d-inline-flex">
                                    <button type="submit" class="btn btn-circle btn-primary w-25 mr-2">{{__('button.add')}}</button>
                                    <a href="{{route('staff.index')}}" class="btn btn-circle btn-secondary w-25">{{__('button.return')}}</a>
                                </div>
                            </form><!--/form-main-->
                        </div><!--/tab-add-staff-->
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