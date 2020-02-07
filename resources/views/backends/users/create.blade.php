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
        <a href="{{route('user.index')}}" 
            class="btn btn-circle btn-primary"
            data-toggle="tooltip" 
            data-placement="left" title="" 
            data-original-title="{{__('user.sub_title')}}"
        ><i class="fas fa-list"></i> {{__('user.sub_title')}}</a>
    </div>
    <div class="row mb-2">
        <div class="col-12 tab-card">
            <!-- Circle Buttons -->
            <div class="card mb-4">
                <div id="supplierList" class="card-body collapse show">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div id="addsupplier" class="tab-pane active">
                            <form class="form-main" action="{{route('user.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-md-6 mb-2">
                                        <div class="form-group">
                                            <label for="name">{{__('user.list.name')}}:</label>
                                            <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" 
                                                placeholder="name"
                                                name="name"
                                                value="{{ old('name', $request->name) }}"
                                            >
                                            @if ($errors->has('name'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="email">{{__('user.list.email')}}:</label>
                                            <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" 
                                                placeholder="email"
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
                                            <label for="password">{{__('user.list.password')}}:</label>
                                            <input type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" 
                                                placeholder="password"
                                                name="password"
                                            >
                                            @if ($errors->has('password'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label class="form-check form-check-inline align-top" for="role">{{__('user.list.role')}}:</label>
                                            <div class="form-check form-check-inline">
                                                <input style="margin-top:-3px" class="form-check-input" type="radio" name="role" id="admin" value="1" {{old('role', $request->role) || empty($request->role) == '1' ? 'checked' : ''}}>
                                                <label class="form-check-label" for="admin">{{__('user.admin')}}</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input style="margin-top:-3px" class="form-check-input" type="radio" name="role" id="satff" value="2" {{old('role', $request->role) == '2' ? 'checked' : ''}}>
                                                <label class="form-check-label" for="satff">Staff</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input style="margin-top:-3px" class="form-check-input" type="radio" name="role" id="view" value="3" {{old('role', $request->role) == '3' ? 'checked' : ''}}>
                                                <label class="form-check-label" for="view">View</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input style="margin-top:-3px" class="form-check-input" type="radio" name="role" id="editor" value="4" {{old('role', $request->role) == '4' ? 'checked' : ''}}>
                                                <label class="form-check-label" for="editor">Editor</label>
                                            </div>
                                            @if ($errors->has('role'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('role') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="thumbnail">{{__('button.thumbnail')}}:</label>
                                            <div class="upload-profile img-upload">
                                                <div class="img-file-tab">
                                                    <div>
                                                        <span class="btn btn-circle btn-file img-select-btn btn-block">
                                                            <i class="fa fa-fw fa-upload"></i> <span>{{__('button.add_thumbnail')}}</span>
                                                            <input type="file" name="thumbnail">
                                                        </span>
                                                    </div>
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <span class="btn btn-circle img-remove-btn"><i class="fa fa-fw fa-times"></i> {{__('button.delete')}}</span>
                                                    </div>
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
                                <div class="form-group d-inline-flex">
                                    <button type="submit" class="btn btn-circle btn-primary w-btn-125 mr-2">{{__('button.add')}}</button>
                                    <a href="{{route('user.index')}}" class="btn btn-circle btn-secondary w-btn-125">{{__('button.return')}}</a>
                                </div>
                            </form><!--/form-main-->
                        </div><!--/tab-add-user-->
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