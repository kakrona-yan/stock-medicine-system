@extends('backends.layouts.master')
@section('title', __('category.title'))
@section('content')
<div id="category-list">
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
                    <a href="{{ route('category.index') }}">
                        <span class="sub-title">{{ __('category.sub_title') }}</span>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <span class="sub-title">{{  $category->name }}</span>
                </li>
            </ol>
        </nav>
        <a href="{{route('category.index')}}" 
            class="btn btn-circle btn-primary"
            data-toggle="tooltip" 
            data-placement="left" title="" 
            data-original-title="{{__('category.sub_title')}}"
        ><i class="fas fa-list"></i> {{__('category.sub_title')}}</a>
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
                                    <th>{{ __('category.list.name') }}</th>
                                    <td>{{$category->name}}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('category.list.categories') }}</th>
                                    <td>
                                        @foreach($category->childs as $cat)
                                        <span class="label font-xs-14 text-info">
                                            <i class="fa fa-btn fa-tags"></i> {{$cat->name}}
                                        </span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('category.list.category_type') }}</th>
                                    <td><span class="text-warning font-xs-14"><i class="fas fa-dot-circle"></i> {{ $category->CategoryType() }}</span></td>
                                </tr>
                                <tr>
                                    <th>{{ __('category.list.active') }}</th>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" data-toggle="toggle" data-onstyle="success" name="active"
                                            {{ $category->is_active == 1 ? 'checked' : '' }}
                                            disabled
                                            > 
                                            <span class="slider"><span class="on">ON</span><span class="off">OFF</span>
                                            </span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="w-20">{{__('category.list.action')}}</th>                
                                    <td>
                                        <div class="w-100">
                                        <a class="btn btn-sm btn-info btn-circle" 
                                            data-toggle="tooltip" 
                                            data-placement="top"
                                            data-original-title="{{__('button.show')}}"
                                            href="{{route('category.show', $category->id)}}"
                                        ><i class="far fa-eye"></i>
                                        </a>
                                        <a class="btn btn-sm btn-warning btn-circle" 
                                            data-toggle="tooltip" 
                                            data-placement="top"
                                            data-original-title="{{__('button.edit')}}"
                                            href="{{route('category.edit', $category->id)}}"
                                        ><i class="far fa-edit"></i>
                                        </a>
                                        <button type="button" disabled
                                            id="btn-deleted"
                                            class="btn btn-sm btn-danger btn-circle"
                                            onclick="deletePopup(this)"
                                            data-id="{{ $category->id }}"
                                            data-name="{{ $category->name}}"
                                            data-toggle="modal" data-target="#deletecategory"
                                            title="{{__('button.delete')}}"
                                            ><i class="fa fa-trash"></i>
                                        </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group d-inline-flex mb-3">
                        <a href="{{route('category.edit', $category->id)}}" class="btn btn-circle btn-primary w-btn-125 mr-2">{{__('button.edit')}}</a>
                        <a href="{{route('category.index')}}" class="btn btn-circle btn-secondary w-btn-125">{{__('button.return')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div><!--/row-->
</div>
@endsection