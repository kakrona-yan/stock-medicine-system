<div class="row mb-2">
    <div class="col-12">
        <!-- Circle Buttons -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex w-100 mb-2 justify-content-end">
                    <a href="{{route('customer_type.create')}}" 
                        class="btn btn-circle btn-primary"
                        data-toggle="tooltip" 
                        data-placement="left" title="" 
                        data-original-title="{{__('button.add_new')}}"
                    ><i class="fas fa-plus-circle"></i> {{__('button.add_new')}}ប្រភេទអតិថិជន</a>
                </div>
                <form id="customer-search" action="{{ route('customer.index') }}" method="GET" class="form form-horizontal form-search">
                    <div class="row">
                        <div class="col-6 col-md-3 mb-1">
                            <div class="form-group">
                                <input type="text" class="form-control" name="name"  value="{{ old('name', $request->name) }}" placeholder="@lang('customer.list.name')">
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-1">
                            <div class="form-group">
                                <button type="submit" class="btn btn-circle btn-circlebtn-primary"><i class="fa fa-search"></i> @lang('button.search')</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive cus-table w-50">
                    <table class="table table-striped table-bordered">
                        <thead class="bg-primary text-light">
                            <tr>
                                <th>{{ __('customer.list.name') }}</th>
                                <th class="text-center">{{ __('customer.list.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $customerTypes as $customerType)
                            <tr>
                                <td>{{ $customerType->name }}</td>
                                <td class="text-center">
                                    <a class="btn btn-circle btn-circle btn-sm btn-info btn-circle" 
                                        data-toggle="tooltip" 
                                        data-placement="top"
                                        data-original-title="{{__('button.show')}}"
                                        href="{{route('customer_type.show', $customerType->id)}}"
                                    ><i class="far fa-eye"></i>
                                    </a>
                                    <a class="btn btn-circle btn-circle btn-sm btn-warning btn-circle" 
                                        data-toggle="tooltip" 
                                        data-placement="top"
                                        data-original-title="{{__('button.edit')}}"
                                        href="{{route('customer_type.edit', $customerType->id)}}"
                                    ><i class="far fa-edit"></i>
                                    </a>
                                    <button type="button"
                                        id="btn-deleted"
                                        class="btn btn-circle btn-circle btn-sm btn-danger btn-circle"
                                        onclick="deletePopup(this)"
                                        data-id="{{ $customerType->id }}"
                                        data-name="{{ $customerType->name}}"
                                        data-toggle="modal" data-target="#deleteCustomerType"
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
                    {{ $customerTypes->appends(request()->query())->links() }}
                </div>
                @if( Session::has('flash_danger') )
                    <p class="alert text-center w-50 {{ Session::get('alert-class', 'alert-danger') }}">
                        <span class="spinner-border spinner-border-sm text-darktext-danger align-middle"></span> {{ Session::get('flash_danger') }}
                    </p>
                @endif
            </div>
        </div>
    </div>
</div><!--/row-->
<!--Modal delete customer-->
<div class="modal fade" id="deleteCustomerType">
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
            <form id="delete_customer_form" action="{{route('customer_type.destroy')}}" method="POST">
                @csrf
                <input type="hidden" type="form-control" name="customer_type_id">
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
        $('input[name="customer_type_id"]').val($(obj).attr("data-id"));
        $("#modal-name" ).html($(obj).attr("data-name"));
    }

    function clearData() {
        $('input[name="customer_type_id"]').val('');
        $("#modal-name" ).html('');
    }
</script>
@endpush