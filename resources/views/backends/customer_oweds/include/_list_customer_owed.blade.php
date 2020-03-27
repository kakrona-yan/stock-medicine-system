<div class="row mb-2">
    <div class="col-12">
        <!-- Circle Buttons -->
        <div class="card mb-4">
            <div class="card-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-justified mb-2" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#pay">ការបង់ប្រាក់នៃការលក់នីមួយៗ</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#payall">ការបង់ប្រាក់នៃការលក់សរុប</a>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active" id="pay">
                        <div class="table-responsive cus-table">
                            <table class="table table-bordered">
                                <thead class="bg-primary text-light">
                                    <tr>
                                        <th class="text-center" style="width: 20px;">#</th>
                                        <th>{{__('customer.list.name')}}</th>
                                        <th>{{__('customer.list.created_at')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach( $customers as $customer)
                                        <tr>
                                            <td class="text-center" rowspan="{{$customer->sales->count() > 0 ? 2 : 1}}">
                                                @if ($customer->sales->count() > 0)
                                                <a href="#customer_{{$customer->id}}" data-toggle="collapse" style="text-decoration: none !important;" class="collapsed"><i class="fas fa-minus-circle"></i></a>
                                                @endif
                                            </td>
                                            <td>{{ $customer->customerFullName() }}</td>
                                            <td>{{ date('Y-m-d', strtotime($customer->created_at)) }}</td>
                                        </tr>
                                        @if ($customer->sales->count() > 0)
                                        <tr>
                                            <td colspan="2" id="customer_{{$customer->id}}" class="collapse p-0">
                                                <table class="table mb-0 tabel-row-1">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>{{__('sale.list.invoice_code')}}</th>
                                                            <th>{{ __('customer_owed.list.amount') }}</th>
                                                            <th>{{ __('customer_owed.list.receive_amount') }}</th>
                                                            <th>{{ __('customer_owed.list.owed_amount') }}</th>
                                                            <th class="text-center">{{ __('customer_owed.list.receive_date') }}</th>
                                                            <th class="text-center">{{__('sale.list.sale_date')}}</th>
                                                            <th>{{__('sale.list.staff_name')}}</th>
                                                            <th class="text-center">{{ __('customer_owed.list.action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($customer->sales as $sale)
                                                            @php
                                                                $customerOwed = 0;
                                                                $amount = $sale->customerOwed ? $sale->customerOwed->amount : $sale->total_amount;
                                                                $receiveAmount = $sale->customerOwed ? $sale->customerOwed->receive_amount : 0;
                                                                $customerOwed = ($amount - $receiveAmount);
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $sale->quotaion_no }}</td>
                                                                <td class="text-right">{{ $sale->customerOwed ? $sale->customerOwed->amount : $sale->total_amount }}</td>
                                                                <td class="text-right">{{ $sale->customerOwed ? $sale->customerOwed->receive_amount : 0 }}</td>
                                                                <td class="text-right">{{ currencyFormat($customerOwed) }}</td>
                                                                <td class="text-center">{{ $sale->customerOwed ? date('Y-m-d h:i', strtotime($sale->customerOwed->receive_date)) : '-'}}</td>
                                                                <td class="text-center">{{date('Y-m-d h:i', strtotime($sale->sale_date))}}</td>
                                                                <td>{{$sale->staff ? $sale->staff->getFullnameAttribute() : \Auth::user()->name}}</td>
                                                                <td>
                                                                    <a class="btn btn-circle btn-circle btn-sm btn-success btn-circle mr-1" 
                                                                        data-toggle="tooltip" 
                                                                        data-placement="top"
                                                                        data-original-title="Invoice #{{$sale->quotaion_no}}"
                                                                        href="{{route('sale.downloadPDF', $sale->id)}}"
                                                                        ><i class="far fa-file-pdf"></i>
                                                                    </a>
                                                                    <a class="btn btn-circle btn-sm btn-warning btn-circle" 
                                                                        data-toggle="tooltip" 
                                                                        data-placement="top"
                                                                        data-original-title="{{__('button.pay')}}"
                                                                        href="{{route('customer_owed.edit', $sale->id)}}"
                                                                        >{{__('button.pay')}}
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        @endif
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
                    <div class="tab-pane fade" id="payall">
                        <div class="table-responsive cus-table">
                            <table class="table table-bordered">
                                <thead class="bg-primary text-light">
                                    <tr>
                                        <th class="text-center" style="width: 20px;">#</th>
                                        <th>{{__('customer.list.name')}}</th>
                                        <th>{{__('customer.list.created_at')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
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
        </div>
    </div>
</div><!--/row-->