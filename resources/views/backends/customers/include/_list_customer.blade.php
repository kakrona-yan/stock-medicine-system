<div class="row mb-2">
    <div class="col-12">
        <!-- Circle Buttons -->
        <div class="card mb-4">
            <div class="card-body">
                <form id="customer-search" action="{{ route('customer.index') }}" method="GET" class="form form-horizontal form-search">
                    <div class="row">
                        <div class="col-12 col-md-10">
                            <div class="row">
                                <div class="col-6 col-md-3 mb-1">
                                    <div class="form-group">
                                        <label class="font-weight-bold">@lang('customer.list.name')</label>
                                        <input type="text" class="form-control" name="name"  value="{{ old('name', $request->name) }}">
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 mb-1">
                                    <div class="form-group">
                                        <label class="font-weight-bold">@lang('customer.list.phone')</label>
                                        <input type="text" class="form-control" name="phone_number"  value="{{ old('phone_number', $request->phone_number) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-2 d-flex align-items-end mb-1">
                            <div class="form-group">
                                <button type="submit" class="btn btn-circle btn-circlebtn-primary"><i class="fa fa-search"></i> @lang('button.search')</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive cus-table">
                    <table class="table table-striped table-bordered">
                        <thead class="bg-primary text-light">
                            <tr>
                                <th class="text-center">{{__('customer.form.thumbnail')}}</th>
                                <th>{{ __('customer.list.name') }}</th>
                                <th>{{ __('customer.list.phone') }}</th>
                                <th>{{ __('customer.list.address') }}</th>
                                <th class="text-center">{{ __('customer.list.map_link') }}</th>
                                <th class="text-center">{{ __('customer.list.created_at') }}</th>
                                <th class="text-center">{{ __('customer.list.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $customers as $customer)
                            <tr>
                                <td>
                                    <div class="text-center d-flex justify-content-center align-items-center">
                                        <div class="crop-image d-flex justify-content-center align-items-center">
                                            <img class="thumbnail" src="{{$customer->thumbnail? getUploadUrl($customer->thumbnail, config('upload.customer')) : asset('images/no-avatar.jpg') }}" />
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $customer->customerFullName() }}</td>
                                <td>
                                    <div class="d-flex flex-row">
                                        <i class="fas fa-phone-square-alt text-success my-1 mr-1"></i>
                                    <div>
                                        {{ $customer->phone1 }}<br/>
                                        {{ $customer->phone2 ? $customer->phone2: '' }}
                                    </div>
                                    </div>
                                </td>
                                <td>{{ str_limit($customer->address, 30) }}</td>
                                <td class="text-center">
                                    @if($customer->map_address)
                                        <span class="position-relative">
                                            <a href="{{$customer->map_address}}" target="_blank"><i class="fas fa-globe-africa"></i>
                                            <span class="spinner-grow spinner-grow-sm  text-success position-absolute" role="status"></span></a>
                                        </span>
                                    @endif
                                    
                                </td>
                                <td class="text-center">{{ date('Y-m-d', strtotime($customer->created_at)) }}</td>
                                <td class="text-center">
                                    <a class="btn btn-circle btn-circle btn-sm btn-info btn-circle" 
                                        data-toggle="tooltip" 
                                        data-placement="top"
                                        data-original-title="{{__('button.show')}}"
                                        href="{{route('customer.show', $customer->id)}}"
                                    ><i class="far fa-eye"></i>
                                    </a>
                                    <a class="btn btn-circle btn-circle btn-sm btn-warning btn-circle" 
                                        data-toggle="tooltip" 
                                        data-placement="top"
                                        data-original-title="{{__('button.edit')}}"
                                        href="{{route('customer.edit', $customer->id)}}"
                                    ><i class="far fa-edit"></i>
                                    </a>
                                    <button type="button"
                                        id="btn-deleted"
                                        class="btn btn-circle btn-circle btn-sm btn-danger btn-circle"
                                        onclick="deletePopup(this)"
                                        data-id="{{ $customer->id }}"
                                        data-name="{{ $customer->name}}"
                                        data-toggle="modal" data-target="#deleteCustomer"
                                        title="{{__('button.delete')}}"
                                        ><i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $customers->appends(request()->query())->links() }}
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
<!--Modal delete customer-->
<div class="modal fade" id="deleteCustomer">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">       
        <!-- Modal Header -->
        <div class="modal-header">
            <h5 class="modal-title"><i class="fa fa-trash"></i> {{__('customer.confirm_delete')}}</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div> 
        <!-- Modal body -->
        <div class="modal-body text-center">
            <div class="message">{{__('customer.confirm_msg') }}</div>
            <div id="modal-name"></div>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer d-flex justify-content-center">
            <form id="delete_customer_form" action="{{route('customer.destroy')}}" method="POST">
                @csrf
                <input type="hidden" type="form-control" name="customer_id">
                <button type="submit" class="btn btn-circle btn-circlebtn-primary">{{__('button.ok')}}</button>
                <button type="button" class="btn btn-circle btn-circlebtn-danger" data-dismiss="modal"
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
        $('input[name="customer_id"]').val($(obj).attr("data-id"));
        $("#modal-name" ).html($(obj).attr("data-name"));
    }

    function clearData() {
        $('input[name="customer_id"]').val('');
        $("#modal-name" ).html('');
    }
</script>
@endpush