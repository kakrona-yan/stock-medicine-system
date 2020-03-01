@extends('backends.layouts.master')
@section('title', __('product.title'))
@section('content')
<div id="product-list">
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
                    <span class="sub-title">{{ __('product.sub_title') }}</span>
                </li>
            </ol>
        </nav>
        <a href="{{route('product.index')}}" 
            class="btn btn-circle btn-primary"
            data-toggle="tooltip" 
            data-placement="left" title="" 
            data-original-title="{{__('product.sub_title')}}"
        ><i class="fas fa-list"></i> {{__('product.sub_title')}}</a>
    </div>
    <form class="form-main" action="{{route('product.update', $product->id)}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mb-2">
            <div class="col-8 tab-card">
                <!-- Circle Buttons -->
                <div class="card mb-4">
                    <div id="product-list-left" class="card-body collapse show">
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div id="add-product-rigt" class="tab-pane active">
                                <div class="form-group">
                                    <label for="title">{{__('product.list.product_title')}}:</label>
                                    <input type="text" class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" 
                                        placeholder="title"
                                        name="title"
                                        value="{{ old('title', $product->title) }}"
                                    >
                                    @if ($errors->has('title'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('title') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="product_import">{{__('product.list.product_import')}}:</label>
                                            <input type="text" class="form-control {{ $errors->has('product_import') ? ' is-invalid' : '' }}" 
                                                placeholder="product import"
                                                name="product_import"
                                                value="{{ old('product_import', $product->product_import) }}"
                                            >
                                            @if ($errors->has('product_import'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('product_import') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="in_store">{{__('product.list.in_store')}}:</label>
                                            <input type="text" class="form-control {{ $errors->has('in_store') ? ' is-invalid' : '' }}" 
                                                placeholder="in-store"
                                                name="in_store"
                                                value="{{ old('in_store', $product->in_store) }}"
                                            >
                                            @if ($errors->has('in_store'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('in_store') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="price">{{__('product.list.price')}}($):</label>
                                            <input type="text" class="form-control {{ $errors->has('price') ? ' is-invalid' : '' }}" 
                                                placeholder="price"
                                                name="price"
                                                value="{{ old('price', $product->price) }}"
                                            >
                                            @if ($errors->has('price'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('price') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="product_free">{{__('product.list.product_free')}}:</label>
                                            <input type="text" class="form-control" 
                                                placeholder="product free"
                                                name="product_free"
                                                value="{{ old('product_free', $product->product_free) }}"
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="invoiceCode">Expird date:</label>
                                            <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                                                <input type="text" class="form-control" name="expird_date"
                                                    value="{{ old('expird_date', $product->expird_date)}}">
                                                <div class="input-group-append">
                                                    <div class="input-group-text"><span class="far fa-calendar-alt"></span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="product_free">Terms(B/3x10T):</label>
                                            <input type="text" class="form-control" 
                                                placeholder="terms"
                                                name="terms"
                                                value="{{ old('terms', $product->terms) }}"
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group description">
                                    <textarea class="form-control textarea" id="description" rows="3" name="description">{{$product->description}}</textarea>
                                </div>
                                <div class="form-group">
                                    <fieldset class="edit-master-registration-fieldset">
                                        <legend class="edit-application-information-legend">Gallery Product:</legend>
                                        @if (session('maxSizeImage'))
                                        <div class="text-danger">{{ session('maxSizeImage') }}</div>
                                        @endif
                                        <div id="upload">
                                            <label for="productImages" class="mb-1 py-2 btn btn-circle btn-primary"><i class="fas fa-plus-circle mr-1"></i> Upload Gallery</label>
                                            <input type="file" id="productImages" accept="image/*" class="upload-label d-none" multiple>
                                        </div>
                                        <div class="multi-image row">
                                            <input id="product_gallery_id" type="hidden" name="product_gallery_id" value="">
                                            @foreach ($product->productImages as $productImage)
                                            @isset($productImage->thumbnail)
                                                <div class="col-6 col-md-3 align-content-end text-center">
                                                <div class="product-thumb align-middle">
                                                    <img class="mw-100 mh-100" src='{{ getUploadUrl($productImage->thumbnail, config('upload.product_gallery')) }}'/>
                                                </div>
                                                <p><button type="button" class="mt-1 btn btn-circle btn_remove" data-id="{{$productImage->id}}">Remove</button></p>
                                            </div>
                                            @endisset
                                            @endforeach
                                        </div>
                                    </fieldset>
                                </div>
                            </div><!--/tab-add-product-->
                        </div>
                    </div>
                    <div class="card-footer">
                        <span><i class="fas fa-user-md"></i> Creator: {{ Auth::user() ? Auth::user()->name : '' }}</span>
                    </div>
                </div>
            </div>
            <div class="col-4 tab-card">
                <!-- Circle Buttons -->
                <div class="card mb-4">
                    <div id="product-list-right" class="card-body collapse show">
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div id="add-product-right" class="tab-pane active">
                                <div class="form-group">
                                    <div class="date-public"><i class="far fa-calendar-alt"></i> Date:{{ date('Y-M-d') }}</div>
                                    <div class="form-group modal-footer mt-3 justify-content-center">
                                        <button type="submit" class="btn btn-circle btn-light w-btn-125 mr-2" name="is_active" value="0">{{__('button.save_draft')}}</button>
                                        <button type="submit" class="btn btn-circle btn-primary w-btn-125 mr-2" name="is_active" value="1">{{__('button.save')}}</button>
                                    </div>
                                </div>
                                <div class="form-group select-group">
                                    <label for="category text-center"><i class="fas fa-tags"></i> {{__('product.list.category')}}</label>
                                    <select class="form-control {{ $errors->has('category_id') ? ' is-invalid' : '' }}" name="category_id" id="category_id">
                                        @foreach($categories as $id => $name)
                                            <option value="{{ $id }}" {{ $id == $product->category_id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('category_id'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('category_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="thumbnail text-center"><i class="fas fa-image"></i> {{__('button.thumbnail')}}</label>
                                    <div class="upload-profile img-upload">
                                        <div class="img-file-tab">
                                            <div>
                                                <span class="btn btn-circle btn-file img-select-btn btn-block">
                                                    <i class="fa fa-fw fa-upload"></i> <span>{{__('button.add_thumbnail')}}</span>
                                                    <input type="file" name="thumbnail">
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-center align-items-center">
                                                <img class="thumbnail" src="{{$product->thumbnail? getUploadUrl($product->thumbnail, config('upload.product')) : asset('images/no-thumbnail.jpg') }}"/>
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
                            </div><!--/tab-add-product-->
                        </div>
                    </div>
                </div>
            </div>
        </div><!--/row-->
    </form><!--/form-main-->
</div>
@endsection
@push('footer-script')
<script type="text/javascript" src="{{asset('/js/imageupload.js')}}"></script>
<script>
    // script for upload image
    $('.img-upload').imgUpload();
    $(function(){
        $("#category_id").select2({
            allowClear: false
        });
    });
</script>
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>
    $(function(){
       // TinyMCE4
        var editor_config = {
        path_absolute : "/",
        selector: "textarea#description",
        plugins: [
        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media nonbreaking save table contextmenu directionality",
        "emoticons template paste textcolor colorpicker textpattern"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | forecolor backcolor",
        relative_urls: false,
        file_browser_callback : function(field_name, url, type, win) {
        var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
        var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

        var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
        if (type == 'image') {
            cmsURL = cmsURL + "&type=Images";
        } else {
            cmsURL = cmsURL + "&type=Files";
        }

        tinyMCE.activeEditor.windowManager.open({
            file : cmsURL,
            title : 'Filemanager',
            width : x * 0.8,
            height : y * 0.8,
            resizable : "yes",
            close_previous : "no"
        });
            }
        };

        tinymce.init(editor_config);
    });
    /* multi image perview */
    $(function() {
        var imagesPreview = function(input, placeToInsertImagePreview) {
		if (input.files) {
			var filesAmount = input.files.length;
			for (let i = 0; i < filesAmount; i++) {
				var reader = new FileReader();
				reader.onload = function(event) {
					const div = `<div class="col-6 col-md-3 align-content-end text-center">
									<div class="product-thumb align-middle">
										<input type="hidden" name="product_gallery[]" value="${event.target.result}" class="d-none">
										<img class="mw-100 mh-100" src='${event.target.result}'/>
									</div>
									<p><button type="button" class="mt-1 btn btn-circle btn_remove">Remove</button></p>
								</div>`
					$(placeToInsertImagePreview).append(div);
				}
				reader.readAsDataURL(input.files[i]);
			}
		}
	};

    $('#productImages').on('change', function() {
        imagesPreview(this, 'div.multi-image');
	});
	var element = [];
	$(document).on('click', '.btn_remove', function(e){
		e.preventDefault();
		var id = $(this).attr('data-id');
		element.push(id);
		$('#product_gallery_id').val(element);
		$(this).parent().parent().remove();
		$(this).remove();
	});	
    });
</script>
@endpush