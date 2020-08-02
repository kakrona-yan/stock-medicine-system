@extends('backends.layouts.master')
@section('title', __('staff.title'))
@section('content')
<div id="staff-list">
     <!-- Psage Heading -->
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
                    <span class="sub-title">{{ __('staff.sub_title') }}CHECKINរបស់បុគ្គលិកប្រចាំថ្ងៃ</span>
                </li>
            </ol>
        </nav>
    </div>
    <div class="table-responsive cus-table">
        <table class="table table-striped table-bordered">
            <thead class="bg-primary text-light">
                <tr>
                    <th>{{ __('staff.list.name') }}</th>
                    <th>{{ __('staff.group_staff') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach( $staffs as $staff)
                <tr>
                    <td>{{ $staff->getFullnameAttribute() }}</td>
                    <td>{{ $staff->groupStaff ? $staff->groupStaff->name : "-" }}</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div id="sale-customer" class="table-responsive collapse show">
                            <table class="table table-borderless mb-0">
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
                                    @foreach( $staff->staffGPSMaps as $gpsStaff)          
                                        <tr>
                                            <td>{{$gpsStaff->customer ? $gpsStaff->customer->customerFullName() : 'ទីតាំងរបស់បុគ្គលិក'}}</td>
                                            <td>{{date('Y-m-d h:i', strtotime($gpsStaff->start_date_place))}}</td>
                                            <td class="text-center">
                                                <span class="position-relative">
                                                    <a href="{{route('map.gps.staff')}}?staff_id={{$gpsStaff->staff_id}}&latitude={{$gpsStaff->staff_latitude}}&longitude={{$gpsStaff->staff_longitude}}"><i class="fas fa-globe-africa"></i>
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
                                <tfoot>
                                        <tr>
                                            <td colspan="4" class="text-right">អតិថិជនសរុប</td>
                                            <td>{{$staff->staffGPSMaps->count()}}នាក់</td>
                                        </tr>
                                </tfoot>
                            </table>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!--list product-->
</div>

@endsection