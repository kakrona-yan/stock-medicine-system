<div class="row mb-2">
    <div class="col-12">
        <!-- Circle Buttons -->
        <div class="card mb-4">
            <div class="card-body">
                <form id="product-search" action="{{ route('product.index') }}" method="GET" class="form form-horizontal form-search form-inline mb-2">
                    <div class="form-group mb-2 mr-2">
                        <label for="title" class="mr-sm-2">{{ __('product.list.filter') }}:</label>
                        <input type="text" class="form-control mr-1" id="title" 
                            name="title" value="{{ old('title', $request->title)}}"
                            placeholder="title"
                        >
                    </div>
                    <div class="form-group mb-2">
                        <button type="submit" class="btn btn-circle btn-primary"><i class="fa fa-search"></i> @lang('button.search')</button>
                    </div>
                    <div class="form-group d-block w-100">
                        <div class="input-group mw-btn-125" style="width: 120px;">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-hand-pointer"></i></span>
                            </div>
                            <select class="form-control" name="limit" id="limit">
                                @for( $i=10; $i<=50; $i +=10 )
                                    <option value="{{$i}}" 
                                    {{ $request->limit == $i || config('pagination.limit') == $i ? 'selected' : ''}}
                                    >{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </form>
                <div class="table-responsive cus-table">
                    <table class="table table-striped table-bordered">
                        <thead class="bg-primary text-light">
                            <tr>
                                <th>#</th>
                                <th>{{ __('product.list.thumbnail') }}</th>
                                <th>{{ __('product.list.product_title') }}</th>
                                <th>{{ __('product.list.category') }}</th>
                                <th>{{ __('product.list.product_code') }}</th>
                                <th>{{ __('product.list.product_import') }}</th>
                                <th>{{ __('product.list.price') }}</th>
                                <th>{{ __('product.list.price_discount') }}</th>
                                <th class="text-center">{{ __('product.list.active') }}</th>
                                @if(Auth::user()->isRoleAdmin() || Auth::user()->isRoleEditor())
                                <th class="w-action text-center">{{__('product.list.action')}}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td><i class="fas fa-capsules text-success"></i> {{$product->id}}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <div class="thumbnail-cicel">
                                            <img class="thumbnail" src="{{$product->thumbnail? getUploadUrl($product->thumbnail, config('upload.product')) : asset('images/no-thumbnail.jpg') }}" alt="{{$product->thumbnail}}" width="45"/>
                                        </div>
                                    </div>
                                </td>
                                <td>{{$product->title}}</td>
                                <td>{{$product->category ? $product->category->name : ""}}</td>
                                <td>{{$product->product_code}}</td>
                                <td>{{$product->product_import}}</td>
                                <td class="text-right">{{$product->price}}</td>
                                <td class="text-right">{{$product->price_discount}}</td>
                                <td class="text-center">
                                    <label class="switch">
                                        <input type="checkbox" data-toggle="toggle" data-onstyle="success" name="active"
                                            {{ $product->is_active == 1 ? 'checked' : '' }}
                                        > 
                                        <span class="slider"><span class="on">ON</span><span class="off">OFF</span>
                                        </span>
                                    </label>
                                </td>
                                @if(Auth::user()->isRoleAdmin() || Auth::user()->isRoleEditor())           
                                <td>
                                    <div class="w-action">
                                    <a class="btn btn-sm btn-info btn-circle" 
                                        data-toggle="tooltip" 
                                        data-placement="top"
                                        data-original-title="{{__('button.show')}}"
                                        href="{{route('product.show', $product->id)}}"
                                    ><i class="far fa-eye"></i>
                                    </a>
                                    <a class="btn btn-sm btn-warning btn-circle" 
                                        data-toggle="tooltip" 
                                        data-placement="top"
                                        data-original-title="{{__('button.edit')}}"
                                        href="{{route('product.edit', $product->id)}}"
                                    ><i class="far fa-edit"></i>
                                    </a>
                                    <button type="button"
                                        id="btn-deleted"
                                        class="btn btn-sm btn-danger btn-circle"
                                        onclick="deletePopup(this)"
                                        data-id="{{ $product->id }}"
                                        data-name="{{ $product->title}}"
                                        data-toggle="modal" data-target="#deleteproduct"
                                        title="{{__('button.delete')}}"
                                        ><i class="fa fa-trash"></i>
                                    </button>
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @endforeach 
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $products->appends(request()->query())->links() }}
                </div>
                @if( Session::has('flash_danger') )
                    <p class="alert text-center {{ Session::get('alert-class', 'alert-danger') }}">
                        <span class="spinner-border spinner-border-sm text-darktext-danger align-middle"></span> {{ Session::get('flash_danger') }}
                    </p>
                @endif
            </div>
        </div>
    </div>
</div><!--/row-->
<!--Modal delete product-->
<div class="modal fade" id="deleteproduct">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">       
        <!-- Modal Header -->
        <div class="modal-header">
            <h5 class="modal-title"><i class="fa fa-trash"></i> {{__('product.confirm_delete')}}</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div> 
        <!-- Modal body -->
        <div class="modal-body text-center">
            <div class="message">{{__('product.confirm_msg') }}</div>
            <div id="modal-name"></div>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer d-flex justify-content-center">
            <form id="delete_product_form" action="{{route('product.destroy')}}" method="POST">
                @csrf
                <input type="hidden" type="form-control" name="product_id">
                <button type="submit" class="btn btn-circle btn-primary">{{__('button.ok')}}</button>
                <button type="button" class="btn btn-circle btn-danger" data-dismiss="modal"
                    onclick="clearData()"
                >{{__('button.close')}}</button>
            </form>
        </div>
    </div>
    </div>
</div>
@push('footer-script')
<script>
    function deletePopup(obj) {
        $('input[name="product_id"]').val($(obj).attr("data-id"));
        $("#modal-name" ).html($(obj).attr("data-name"));
    }

    function clearData() {
        $('input[name="product_id"]').val('');
        $("#modal-name" ).html('');
    }
    
    (function( $ ){
        $("[name='limit']").select2({
            allowClear: false
        }).on('select2:select', function (e) {
            $('#product-search').submit();
        });
    })( jQuery );
    
</script>
@endpush