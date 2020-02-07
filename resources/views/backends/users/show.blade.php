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
                    <a href="{{ route('user.index') }}">
                        <span class="sub-title">{{ __('user.sub_title') }}</span>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <span class="sub-title">{{  $user->name }}</span>
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
            <div class="card mxy-4">
                <div class="card-body">
                    <div class="table-responsive cus-table">
                        <table class="table table-show table-hover">
                            <tbody class="border">
                                <tr>
                                    <th>{{__('user.list.thumbnail')}}</th>
                                    <td>
                                        <div class="thumbnail-cicel" style="width:100px; height:100px;">
                                            <img class="thumbnail" src="{{$user->thumbnail? getUploadUrl($user->thumbnail, config('upload.user')) : asset('images/no-avatar.jpg') }}" alt="{{$user->name}}" width="45"/>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('user.list.name') }}</th>
                                    <td>{{$user->name}}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('user.list.email') }}</th>
                                    <td>{{$user->email}}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('user.list.email') }}</th>
                                    <td>{{$user->email}}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('user.list.password') }}</th>
                                    <td>********7535</td>
                                </tr>
                                <tr>
                                    <th>{{ __('user.list.role') }}</th>
                                    <td>{{$user->roleType()}}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('user.list.active') }}</th>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" data-toggle="toggle" data-onstyle="success" name="active"
                                            {{ $user->is_active == 1 ? 'checked' : '' }}
                                            disabled
                                            > 
                                            <span class="slider"><span class="on">ON</span><span class="off">OFF</span>
                                            </span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="w-20">{{__('user.list.action')}}</th>                
                                    <td>
                                        <div class="w-100">
                                        <a class="btn btn-sm btn-info btn-circle" 
                                            data-toggle="tooltip" 
                                            data-placement="top"
                                            data-original-title="{{__('button.show')}}"
                                            href="{{route('user.show', $user->id)}}"
                                        ><i class="far fa-eye"></i>
                                        </a>
                                        <a class="btn btn-sm btn-warning btn-circle" 
                                            data-toggle="tooltip" 
                                            data-placement="top"
                                            data-original-title="{{__('button.edit')}}"
                                            href="{{route('user.edit', $user->id)}}"
                                        ><i class="far fa-edit"></i>
                                        </a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group d-inline-flex mb-3">
                        <a href="{{route('user.edit', $user->id)}}" class="btn btn-circle btn-primary w-btn-125 mr-2">{{__('button.edit')}}</a>
                        <a href="{{route('user.index')}}" class="btn btn-circle btn-secondary w-btn-125">{{__('button.return')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div><!--/row-->
</div>
@endsection