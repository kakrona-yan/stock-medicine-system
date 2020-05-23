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
                    <span class="sub-title">គ្រប់គ្រងក្រុមបុគ្គលិក</span>
                </li>
            </ol>
        </nav>
    </div>
    <!--list product-->
    <div class="row mb-2">
        <div class="col-12 col-md-6 tab-card mb-2">
            <fieldset class="edit-master-registration-fieldset">
                <legend class="edit-application-information-legend text-left">បុគ្គលិក:</legend>
                <!-- Circle Buttons -->
                <div class="card mb-4">
                    <div id="staffList" class="card-body collapse show">
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div id="addstaff" class="tab-pane active">
                                <form class="form-main" action="{{route('staff.group.update.staffIds')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        @php
                                            $i = 0;
                                        @endphp
                                        <input type="hidden" class="form-control" name="group_staff_id" value="{{$id}}">
                                        <p class="mb-2"><i class="fas fa-user"></i> {{$groupStaff->name}}</p>
                                        <label for="name">{{__('staff.list.name')}}:</label>
                                        <div class="row">
                                            @foreach ($staffs as $staff)
                                            <div class="col-6 col-md-4">
                                                <div class="custom-control custom-checkbox mr-3" >
                                                    <input type="checkbox" class="custom-control-input" id="staff_{{$staff->id}}" name="staff[{{$i}}]]" 
                                                        value="{{$staff->id}}"
                                                        {{ $staff->group_staff_id > 0 ? "checked" : ""}}>
                                                    <label class="custom-control-label justify-content-start" for="staff_{{$staff->id}}">{{$staff->getFullnameAttribute()}}</label>
                                                </div>
                                            </div>
                                            @php
                                                $i++;
                                            @endphp
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="form-group w-100 w-md-50 d-inline-flex mt-5">
                                        <button type="submit" class="btn btn-circle btn-primary w-100 w-md-50 mw-100 mr-2" name="update" value="1">{{__('button.add')}}</button>
                                        <a href="{{route('staff.index')}}" class="btn btn-circle btn-secondary w-100 w-md-50 mw-100">{{__('button.return')}}</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    </div><!--/row-->
    <div class="row">
        <div class="col-12 tab-card">
            <fieldset class="edit-master-registration-fieldset">
                <legend class="edit-application-information-legend text-left">ផលិតផល:</legend>
                <!-- Circle Buttons -->
                <div class="card mb-4">
                    <div id="staffList" class="card-body collapse show">
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div id="addProduct" class="tab-pane active">
                                <form class="form-main" action="{{route('staff.group.update.staffIds')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                            @php
                                                $index = 0;
                                            @endphp
                                            @foreach ($products as $product)
                                            <div class="row border-bottom pt-1 pb-1">
                                                <div class="col-6">
                                                    <div class="custom-control custom-checkbox mr-3">
                                                        <input type="checkbox" class="custom-control-input" id="product_{{$product->id}}" name="product[{{$index}}][id]" 
                                                            value="{{$product->id}}" 
                                                            {{ $product->group_staff_id > 0 ? "checked" : ""}}>
                                                        <label class="custom-control-label justify-content-start" for="product_{{$product->id}}">{{$product->title}}</label>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <select class="form-control select2" name="product[{{$index}}][group_staff_id]">
                                                        <option value="" {{ $product->group_staff_id == 0 ? 'selected' : ''}}>{{__('sale.select')}}</option>
                                                        @foreach($groupStaffNames as $id => $name)
                                                            <option value="{{ $id }}" {{ $id == $product->group_staff_id ? 'selected' : '' }}
                                                                >{{ $name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            @php
                                                $index++;
                                            @endphp
                                            @endforeach
                                        <div class="form-group w-100 w-md-50 d-inline-flex mt-5">
                                            <button type="submit" class="btn btn-circle btn-primary w-100 w-md-50 mw-100 mr-2" name="update" value="2">{{__('button.add')}}</button>
                                            <a href="{{route('staff.index')}}" class="btn btn-circle btn-secondary w-100 w-md-50 mw-100">{{__('button.return')}}</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
</div>
@endsection
@push('footer-script')
<script>
    $( document ).ready(function() {
        $(".select2").select2({
            allowClear: false
        });
    });
</script>
@endpush