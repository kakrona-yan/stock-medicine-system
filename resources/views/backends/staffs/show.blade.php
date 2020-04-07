@extends('backends.layouts.master')
@section('title', __('staff.form.title'))
@section('content')
<div id="staff-list">
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
                    <a href="{{ route('staff.index') }}">
                        <span class="sub-title">{{ __('staff.sub_title') }}</span>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <span class="sub-title">{{  $staff->name }}</span>
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
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th class="border-top-0 w-15">{{__('staff.list.name')}}:</th>
                                        <td class="border-top-0">{{ $staff->getFullnameAttribute() }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{__('staff.list.email')}}:</th>
                                        <td>{{ $staff->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{__('staff.list.phone')}}: </th>
                                        <td> 
                                            <div class="d-flex flex-row">
                                            <i class="fas fa-phone-square-alt text-success my-1 mr-1"></i>
                                            <div>
                                                {{ $staff->phone1 }}<br/>
                                                {{ $staff->phone2 ? $staff->phone2: '' }}
                                            </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{__('staff.list.address')}}:</th>
                                        <td>{{ $staff->address }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{__('staff.form.thumbnail')}}:</th>
                                        <td>
                                            <div class="w-100 w-md-50">
                                                <img class="img-thumbnail" src="{{$staff->thumbnail? getUploadUrl($staff->thumbnail, config('upload.staff')) : asset('images/no-avatar.jpg') }}" />
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                              </table>
                            <div class="form-group w-100 w-md-50 d-inline-flex">
                                <a href="{{route('staff.edit', $staff->id)}}" class="btn btn-circle btn-primary w-100 w-md-50 mw-100 mr-2">{{__('button.edit')}}</a>
                                <a href="{{route('staff.index')}}" class="btn btn-circle btn-secondary w-100 w-md-50 mw-100">{{__('button.return')}}</a>
                            </div>
                        </div><!--/tab-add-staff-->
                    </div>
                </div>
            </div>
        </div>
    </div><!--/row-->
</div>
@endsection