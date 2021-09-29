<div id="tab-list" class="row mb-2">
    <div class="col-12">
        <!-- Circle Buttons -->
        <div class="card mb-4">
            <div class="card-body">
                <form id="sale-search" action="{{ route('customer_owed.index') }}" method="GET" class="form form-horizontal form-search form-inline mb-2 d-inline-flex">
                    <div class="form-group mb-2 mr-2">
                        <input type="text" class="form-control mr-1" id="quotaion_no"
                            name="quotaion_no" value="{{ old('quotaion_no', $request->quotaion_no)}}"
                            placeholder="{{__('sale.list.invoice_code')}}"
                        >
                    </div>
                    <div class="form-group mb-2 mr-2">
                        <input type="text" class="form-control mr-1" id="customer"
                            name="customer_name" value="{{ old('customer_name', $request->customer_name)}}"
                            placeholder="{{__('sale.list.customer_name')}}"
                        >
                    </div>
                    <div class="form-group mb-2 mr-2">
                        <input type="text" class="form-control mr-1" id="staff_name"
                            name="staff_name" value="{{ old('staff_name', $request->staff_name)}}"
                            placeholder="{{__('sale.list.staff_name')}}"
                        >
                    </div>
                    <div class="form-group mb-2">
                        <button type="submit" class="btn btn-circle btn-primary"><i class="fa fa-search"></i> @lang('button.search')</button>
                    </div>
                </form>
                <div class="tab-content">
                    <!--/list pay by day-->
                    <div class="tab-pane fade  active show" id="pay_day">
                        <div class="table-responsive cus-table">
                            <table class="table table-bordered">
                                <thead class="bg-success text-light">
                                    <tr>
                                        <th class="pr-4">
                                            {!! \App\Helper\SortableHelper::order(__('sale.list.invoice_code'), 'quotaion_no', 'customer_owed.index') !!}
                                        </th>
                                        <th>{{__('customer.list.name')}}</th>
                                        <th>{{ __('customer_owed.list.amount') }}</th>
                                        <th>{{ __('customer_owed.list.owed_amount') }}</th>
                                        <th class="text-center">{{ __('customer_owed.list.receive_date') }}</th>
                                        <th class="text-center">{{ __('customer_owed.list.status_pay') }}</th>
                                        <th class="text-center">{{ __('customer_owed.list.date_pay') }}</th>
                                        <th>{{__('sale.list.staff_name')}}</th>
                                        @if(Auth::user()->isRoleAdmin() || Auth::user()->isRoleEditor())
                                        <th class="text-center">{{ __('customer_owed.list.action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sales as $sale)
                                    @if(!$sale->customerOwed()->exists() || $sale->customerOwed()->exists() && $sale->customerOwed->status_pay == 0)
                                        @php
                                            $customerOwed = 0;
                                            $amount = $sale->customerOwed()->exists() && $sale->customerOwed->amount ? $sale->customerOwed->amount : $sale->total_amount;
                                            $receiveAmount = $sale->customerOwed()->exists() && $sale->customerOwed->receive_amount  ? $sale->customerOwed->receive_amount : 0;
                                            $customerOwed = $sale->customerOwed()->exists() && $sale->customerOwed->owed_amount ? $sale->customerOwed->owed_amount : ($amount - $receiveAmount);
                                        @endphp
                                        <tr>
                                            <td>{{ $sale->quotaion_no }} {{date('h:i', strtotime($sale->sale_date))}}</td>
                                            <td>{{ $sale->customer->customerFullName() }}</td>
                                            <td class="text-right">{{ $sale->customerOwed()->exists() && $sale->customerOwed->amount_pay ? $sale->customerOwed->amount_pay : $sale->total_amount }}</td>
                                            <td class="text-right">{{ currencyFormat($customerOwed) }}</td>
                                            <td class="text-center">{{ $sale->customerOwed()->exists() && $sale->customerOwed->receive_date ? date('Y-m-d h:i', strtotime($sale->customerOwed->receive_date)) : '-'}}</td>
                                            <td class="text-center">
                                                <div class="btn-sm text-white font-size-10 d-inline-block" style="background:{{$sale->customerOwed()->exists() ? $sale->customerOwed->statusPay()['color']:'#e74a3b'}}">
                                                    {{ $sale->customerOwed()->exists() ? $sale->customerOwed->statusPay()['statusText'] : 'មិនទាន់សង' }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div>{{ $sale->customerOwed()->exists() && $sale->customerOwed->date_pay ? date('Y-m-d h:i', strtotime($sale->customerOwed->date_pay)) : '-'}}</div>
                                                <button type="button" class="btn btn-sm btn-warning" style="font-size: 10px;padding: 1px 5px;"
                                                    onclick="setDatePayPopup(this)"
                                                    data-sale-id="{{ $sale->id }}"
                                                    data-customer-id="{{ $sale->customer->id }}"
                                                    data-toggle="modal" data-target="#set_date_pay_confirm"
                                                    ><i class="far fa-calendar-alt"></i>
                                                </button>
                                            </td>
                                            <td>{{$sale->staff ? $sale->staff->getFullnameAttribute() : \Auth::user()->name}}</td>
                                            @if(Auth::user()->isRoleAdmin() || Auth::user()->isRoleEditor())
                                            <td class="text-center" style="width: 130px;">
                                                <a class="btn btn-sm btn-warning d-inline-flex"
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    data-original-title="{{__('button.pay')}}"
                                                    href="{{route('customer_owed.edit', $sale->id)}}"
                                                    >{{__('button.pay')}}
                                                </a>
                                                <button type="button" class="btn btn-sm btn-info d-inline-flex"
                                                    onclick="updatePopup(this)"
                                                    data-sale-id="{{ $sale->id }}"
                                                    data-customer-id="{{ $sale->customer->id }}"
                                                    data-toggle="modal" data-target="#update_sale_confirm"
                                                    >{{__('button.pay_all')}}
                                                </button>
                                            </td>
                                            @endif
                                        </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
                            {{ $sales->appends(request()->query())->links() }}
                        </div>
                        @if( Session::has('flash_danger') )
                            <p class="alert text-center {{ Session::get('alert-class', 'alert-danger') }}">
                                <span class="spinner-border spinner-border-sm text-darktext-danger align-middle"></span> {{ Session::get('flash_danger') }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!--/row-->
<!--pay fast-->
<div class="modal fade" id="update_sale_confirm">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h5 class="modal-title"><i class="fa fa-trash"></i> {{__('customer_owed.confirm_pay')}}</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body text-center">
            <div class="message">{{__('customer_owed.confirm_msg_pay') }}</div>
            <div id="modal-name"></div>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer d-flex justify-content-center">
            <form id="update_sale_confirm_form" action="{{route('customer_owed.update.pay_day')}}" method="POST">
                @csrf
                <input type="hidden" type="form-control" name="customer_id">
                <input type="hidden" type="form-control" name="sale_id">
                <button type="submit" class="btn btn-circle btn-primary">{{__('button.ok')}}</button>
                <button type="button" class="btn btn-circle btn-danger pl-4 pr-4" data-dismiss="modal"
                    onclick="clearData()"
                >{{__('button.close')}}</button>
            </form>
        </div>
    </div>
    </div>
</div>
<!--set date-->
<div class="modal fade" id="set_date_pay_confirm">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-trash"></i> {{__('customer_owed.confirm_set_date')}}</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="set_date_pay_confirm_form" action="{{route('customer_owed.update.set_date')}}" method="POST">
                @csrf
                <!-- Modal body -->
                <div class="modal-body text-center">
                    <div class="fom-group　mb-3">
                        <input type="hidden" type="form-control" name="customer_id">
                        <input type="hidden" type="form-control" name="sale_id">
                        <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                            <input type="text" class="form-control" name="date_pay"
                                value="{{ old('date_pay', date('Y-m-d')) }}">
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="far fa-calendar-alt"></span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="btn btn-circle btn-primary">{{__('button.ok')}}</button>
                        <button type="button" class="btn btn-circle btn-danger pl-4 pr-4" data-dismiss="modal"
                            onclick="clearData()"
                        >{{__('button.close')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('footer-script')
<script>
    (function( $ ){
        $("[name='status_pay']").select2({
            allowClear: false
        });
    })( jQuery );

    function updatePopup(obj) {
        $('input[name="customer_id"]').val($(obj).attr("data-customer-id"));
        $('input[name="sale_id"]').val($(obj).attr("data-sale-id"));
    }

    function setDatePayPopup(obj){
        $('input[name="customer_id"]').val($(obj).attr("data-customer-id"));
        $('input[name="sale_id"]').val($(obj).attr("data-sale-id"));
    }

    function clearData() {
        $('input[name="customer_id"]').val('');
        $('input[name="sale_id"]').val('');
    }
    $(document).keypress(function(e) {
        if ($("#update_sale_confirm").hasClass('show') && (e.keycode == 13 || e.which == 13)) {
            $("#update_sale_confirm_form").submit()
        }
        if ($("#set_date_pay_confirm").hasClass('show') && (e.keycode == 13 || e.which == 13)) {
            $("#set_date_pay_confirm_form").submit()
        }
    });
</script>
@endpush