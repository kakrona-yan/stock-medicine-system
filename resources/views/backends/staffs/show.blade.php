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
        <div class="col-12 col-md-6 tab-card">
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
                                    {{-- <tr>
                                        <th>{{__('staff.list.email')}}:</th>
                                        <td>{{ $staff->email }}</td>
                                    </tr> --}}
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
                                <a href="{{route('staff.checkin')}}" class="btn btn-circle btn-secondary w-100 w-md-50 mw-100 mr-2">{{__('menu.staff_check_in')}}</a>
                                <a href="{{route('staff.index')}}" class="btn btn-circle btn-secondary w-100 w-md-50 mw-100">{{__('menu.staff')}}</a>
                            </div>
                        </div><!--/tab-add-staff-->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 tab-card mb-2">
            <!-- Circle Buttons -->
            <div class="card mb-4">
                <div class="card-body">
                    <h3>CHECKINរបស់បុគ្គលិកប្រចាំថ្ងៃ</h3>
                    <div id="sale-customer" class="table-responsive collapse show">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{__('customer.list.name')}}</th>
                                        <th>{{__('customer.list.created_at')}}</th>
                                        <th>ទីតាំងបុគ្គលិក</th>
                                        <th>ទីតាំងអតិថិជន</th>
                                        <th>មើលលំអិត</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    @foreach( $gpsStaffs as $gpsStaff)          
                                        <tr>
                                            <td>{{$gpsStaff->customer ? $gpsStaff->customer->customerFullName() : 'ទីតាំងរបស់បុគ្គលិក'}}</td>
                                            <td>{{date('Y-m-d h:i', strtotime($gpsStaff->start_date_place))}}</td>
                                            <td class="text-center">
                                                <span class="position-relative">
                                                    <a href="{{route('map.gps.staff')}}?staff_id={{$gpsStaff->staff_id}}&latitude={{$gpsStaff->latitude}}&longitude={{$gpsStaff->longitude}}"><i class="fas fa-globe-africa"></i>
                                                    <span class="spinner-grow spinner-grow-sm  text-success position-absolute" role="status"></span></a>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if($gpsStaff->customer)
                                                <span class="position-relative">
                                                    <a href="{{route('customer_map.index')}}?customer_id={{$gpsStaff->customer_id}}&latitude={{$gpsStaff->latitude}}&longitude={{$gpsStaff->longitude}}"><i class="fas fa-globe-africa"></i>
                                                    <span class="spinner-grow spinner-grow-sm  text-success position-absolute" role="status"></span></a>
                                                </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($gpsStaff->customer)
                                                <a class="btn btn-circle btn-circle btn-sm btn-info btn-circle" 
                                                    data-toggle="tooltip" 
                                                    data-placement="top"
                                                    data-original-title="{{__('button.show')}}"
                                                    href="{{route('customer.show', $gpsStaff->customer->id)}}"
                                                ><i class="far fa-eye"></i>
                                                </a>
                                                @endif
                                            </td>
                                        </tr>
                                       
                                    @endforeach
                                </tbody>
                                <tfooter>
                                        <tr>
                                            <td colspan="4" class="text-right">អតិថិជនសរុប</td>
                                            <td>{{$gpsStaffs->count()}}នាក់</td>
                                        </tr>
                                </tfooter>
                            </table>
                        </div>
                </div>
            </div>
        </div>
    </div><!--/row-->
</div>
@endsection