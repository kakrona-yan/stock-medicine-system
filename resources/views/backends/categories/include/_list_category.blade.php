<div class="row mb-2">
    <div class="col-12">
        <!-- Circle Buttons -->
        <div class="card mb-4">
            <div class="card-body">
                <form id="category-search" action="{{ route('product.index') }}" method="GET" class="form form-horizontal form-search form-inline mb-2">
                    <div class="form-group mb-2 mr-2">
                        <label for="name" class="mr-sm-2">{{ __('category.list.filter') }}:</label>
                        <input type="text" class="form-control mr-1" id="name" 
                            name="name" value="{{ old('name', $request->name)}}"
                            placeholder="{{ __('category.list.name') }}"
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
                            <select class="form-control" name="limit" id="limit_category">
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
                            <th>{{ __('category.list.name') }}</th>
                            {{-- <th>{{ __('category.list.categories') }}</th> --}}
                            {{-- <th>{{ __('category.list.category_type') }}</th> --}}
                            <th class="text-center">{{ __('category.list.active') }}</th>
                            @if(Auth::user()->isRoleAdmin() || Auth::user()->isRoleEditor())
                            <th class="w-action text-center">{{__('category.list.action')}}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($listCategories as $category)
                            <tr>
                                <td>{{$category->name}}</td>
                                {{-- <td>
                                    @foreach($category->childs as $cat)
                                    <span class="label font-xs-14 text-info">
                                        <i class="fa fa-btn fa-tags"></i> {{$cat->name}}
                                    </span>
                                    @endforeach
                                </td> --}}
                                {{-- <td>
                                    <span class="text-warning font-xs-14"><i class="fas fa-dot-circle"></i> {{ $category->CategoryType() }}</span>
                                </td> --}}
                                <td class="text-center">
                                    <label class="switch">
                                        <input type="checkbox" data-toggle="toggle" data-onstyle="success" name="active"
                                            {{ $category->is_active == 1 ? 'checked' : '' }}
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
                                    <button type="button"
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
                                @endif
                            </tr>
                        @endforeach 
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $listCategories->appends(request()->query())->links() }}
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
<!--Modal delete category-->
<div class="modal fade" id="deletecategory">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">       
        <!-- Modal Header -->
        <div class="modal-header">
            <h5 class="modal-title"><i class="fa fa-trash"></i> {{__('category.confirm_delete')}}</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div> 
        <!-- Modal body -->
        <div class="modal-body text-center">
            <div class="message">{{__('category.confirm_msg') }}</div>
            <div id="modal-name"></div>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer d-flex justify-content-center">
            <form id="delete_category_form" action="{{route('category.destroy')}}" method="POST">
                @csrf
                <input type="hidden" type="form-control" name="category_id">
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
        $('input[name="category_id"]').val($(obj).attr("data-id"));
        $("#modal-name" ).html($(obj).attr("data-name"));
    }

    function clearData() {
        $('input[name="category_id"]').val('');
        $("#modal-name" ).html('');
    }
    
    $( document ).ready(function() {
        $("#limit_category").select2({
            allowClear: false
        }).on('select2:select', function (e) {
            $('#category-search').submit();
        });
    });
</script>
@endpush