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
                    <span class="sub-title">{{ __('category.sub_title') }}</span>
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
            <div class="card mb-4">
                <div id="supplierList" class="card-body collapse show">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div id="addsupplier" class="tab-pane active">
                            <form class="form-main" action="{{route('category.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-md-6 mb-2">
                                        <div class="form-group">
                                            <label for="name">{{__('category.list.name')}}:</label>
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
                                        <div class="form-group select-group">
                                            <label for="parent_id">{{__('category.list.categories')}}:</label>
                                            <select class="form-control" name="parent_id" id="parent_id">
                                                <option value="">{{ __('category.select') }}</option>
                                                @foreach($categoryNames as $id => $name)
                                                    <option value="{{ $id }}" {{ $id = $request->parent_id ? 'selected' : '' }}>{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group select-group">
                                            <label for="category_type">{{__('category.list.category_type')}}:</label>
                                            <select class="form-control {{ $errors->has('category_type') ? ' is-invalid' : '' }}" 
                                                name="category_type" id="category_type">
                                                @foreach($categoryType as $key => $name)
                                                    <option value="{{ $key }}" {{ $key = $request->category_type ? 'selected' : '' }}>{{ $name }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('category_type'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('category_type') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div><!--/row-->
                                <div class="form-group d-inline-flex">
                                    <button type="submit" class="btn btn-circle btn-primary w-btn-125 mr-2">{{__('button.add')}}</button>
                                    <a href="{{route('category.index')}}" class="btn btn-circle btn-secondary w-btn-125">{{__('button.return')}}</a>
                                </div>
                            </form><!--/form-main-->
                        </div><!--/tab-add-category-->
                    </div>
                </div>
            </div>
        </div>
    </div><!--/row-->
</div>
@endsection
@push('footer-script')
<script>
    $(function(){
        $("[name='parent_id'], [name='category_type']").select2({
            allowClear: false
        });
    });
    
</script>
@endpush