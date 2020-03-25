<div class="row mb-2">
    <div class="col-12">
        <!-- Circle Buttons -->
        <div class="card mb-4">
            <div class="card-body">
                <form id="customer_owed-search" action="{{ route('customer_owed.index') }}" method="GET" class="form form-horizontal form-search">
                    <div class="row">
                        <div class="col-12 col-md-10">
                            
                        </div>
                    </div>
                </form>
                <div class="table-responsive cus-table">
                    <table class="table table-striped table-bordered">
                        <thead class="bg-primary text-light">
                            <tr>
                                <th>{{ __('customer_owed.list.sale_id') }}</th>
                                <th>{{ __('customer_owed.list.customer_id') }}</th>
                                <th>{{ __('customer_owed.list.amount') }}</th>
                                <th>{{ __('customer_owed.list.receive_amount') }}</th>
                                <th>{{ __('customer_owed.list.owed_amount') }}</th>
                                <th class="text-center">{{ __('customer_owed.list.receive_date') }}</th>
                                <th class="text-center">{{ __('customer_owed.list.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $customerOweds as $customerOwed)
                                <tr>
                                    <td>{{ $customerOwed->sale ? $customerOwed->sale->quotaion_no　: ''}}</td>
                                    <td>{{ $customerOwed->customer ? $customerOwed->customer->customerFullName() : ''}}</td>
                                    <td>{{ $customerOwed->amount }}</td>
                                    <td>{{ $customerOwed->receive_amount }}</td>
                                    <td>{{ $customerOwed->owed_amount }}</td>
                                    <td class="text-center">{{ date('Y-m-d', strtotime($customerOwed->receive_date)) }}</td>
                                    <td class="text-center">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $customerOweds->appends(request()->query())->links() }}
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
<!--Modal delete customer_owed-->
<div class="modal fade" id="deletecustomer_owed">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">       
        <!-- Modal Header -->
        <div class="modal-header">
            <h5 class="modal-title"><i class="fa fa-trash"></i> {{__('customer_owed.confirm_delete')}}</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div> 
        <!-- Modal body -->
        <div class="modal-body text-center">
            <div class="message">{{__('customer_owed.confirm_msg') }}</div>
            <div id="modal-name"></div>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer d-flex justify-content-center">
            <form id="delete_customer_owed_form" action="{{route('customer_owed.destroy')}}" method="POST">
                @csrf
                <input type="hidden" type="form-control" name="customer_owed_id">
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
        $('input[name="customer_owed_id"]').val($(obj).attr("data-id"));
        $("#modal-name" ).html($(obj).attr("data-name"));
    }

    function clearData() {
        $('input[name="customer_owed_id"]').val('');
        $("#modal-name" ).html('');
    }
</script>
@endpush