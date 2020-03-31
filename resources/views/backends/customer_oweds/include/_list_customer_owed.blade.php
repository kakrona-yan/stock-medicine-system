<div id="tab-list" class="row mb-2">
    <div class="col-12">
        <!-- Circle Buttons -->
        <div class="card mb-4">
            <div class="card-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-justified mb-2" role="tablist">
                    <li class="nav-item">
                    <a class="nav-link bg-info text-white {{$request->customers_page ? ' active' : ''}}" data-toggle="tab" href="#pay">ការសងប្រាក់នៃការលក់ទាំងអស់</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link bg-success text-white {{$request->pay_all_page ? ' active' : ''}}" data-toggle="tab" href="#pay_ready">បញ្ចីសងប្រាក់ហើយ និងបានខ្លះរបស់អតិថិជន</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link bg-danger text-white {{$request->pay_no_page ? ' active' : ''}}" data-toggle="tab" href="#pay_in_ready">បញ្ចីមិនទាន់​សងប្រាក់របស់អតិថិជន</a>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane fade {{$request->customers_page ? ' active show' : ''}}" id="pay">
                        <div class="table-responsive cus-table">
                            <table class="table table-bordered">
                                <thead class="bg-info text-light">
                                    <tr>
                                        <th class="text-center" style="width: 20px;">#</th>
                                        <th>{{__('customer.list.name')}}</th>
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
                                        </tr>
                                        @if ($customer->sales->count() > 0)
                                        <tr>
                                            <td colspan="1" id="customer_{{$customer->id}}" class="collapse p-0">
                                                <table class="table mb-0 tabel-row-1">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>{{__('sale.list.invoice_code')}}</th>
                                                            <th>{{ __('customer_owed.list.amount') }}</th>
                                                            <th>{{ __('customer_owed.list.owed_amount') }}</th>
                                                            <th class="text-center">{{ __('customer_owed.list.receive_date') }}</th>
                                                            <th class="text-center">{{ __('customer_owed.list.status_pay') }}</th>
                                                            <th class="text-center">{{ __('customer_owed.list.date_pay') }}</th>
                                                            <th>{{__('sale.list.staff_name')}}</th>
                                                            <th class="text-center">{{ __('customer_owed.list.action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($customer->sales as $sale)
                                                            @php
                                                                $customerOwed = 0;
                                                                $amount = $sale->customerOwed()->exists() ? $sale->customerOwed->amount : $sale->total_amount;
                                                                $receiveAmount = $sale->customerOwed()->exists() ? $sale->customerOwed->receive_amount : 0;
                                                                $customerOwed = ($amount - $receiveAmount);
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $sale->quotaion_no }}</td>
                                                                <td class="text-right">{{ $sale->customerOwed()->exists() ? $sale->customerOwed->amount : $sale->total_amount }}</td>
                                                                <td class="text-right">{{ currencyFormat($customerOwed) }}</td>
                                                                <td class="text-center">{{ $sale->customerOwed()->exists() ? date('Y-m-d h:i', strtotime($sale->customerOwed->receive_date)) : '-'}}</td>
                                                                <td class="text-center">
                                                                    <div class="btn-sm text-white font-size-10" style="background:{{$sale->customerOwed()->exists() ? $sale->customerOwed->statusPay()['color']:'#e74a3b'}}">
                                                                        {{ $sale->customerOwed()->exists() ? $sale->customerOwed->statusPay()['statusText'] : 'មិនទាន់សងប្រាក់' }}
                                                                    </div>
                                                                </td>
                                                                <td class="text-center">{{ $sale->customerOwed()->exists() ? date('Y-m-d h:i', strtotime($sale->customerOwed->date_pay)) : '-'}}</td>
                                                                <td>{{$sale->staff ? $sale->staff->getFullnameAttribute() : \Auth::user()->name}}</td>
                                                                <td class="text-center">
                                                                    <a class="btn btn-sm btn-warning" 
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
                    <div class="tab-pane fade {{$request->pay_all_page ? ' active show' : ''}}" id="pay_ready">
                        <div class="table-responsive cus-table">
                            <table class="table table-bordered">
                                <thead class="bg-success text-light">
                                    <tr>
                                        <th class="text-center" style="width: 20px;">#</th>
                                        <th>{{__('customer.list.name')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach( $customerPayAlls as $customer)
                                    <tr>
                                        <td class="text-center" rowspan="{{$customer->sales->count() > 0 ? 2 : 1}}">
                                            @if ($customer->sales->count() > 0)
                                            <a href="#customer_{{$customer->id}}" data-toggle="collapse" style="text-decoration: none !important;" class="collapsed"><i class="fas fa-minus-circle"></i></a>
                                            @endif
                                        </td>
                                        <td>{{ $customer->customerFullName() }}</td>
                                    </tr>
                                    @if ($customer->sales->count() > 0)
                                    <tr>
                                        <td colspan="1" id="customer_{{$customer->id}}" class="collapse p-0">
                                            <table class="table mb-0 tabel-row-1">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>{{__('sale.list.invoice_code')}}</th>
                                                        <th>{{ __('customer_owed.list.amount') }}</th>
                                                        <th>{{ __('customer_owed.list.owed_amount') }}</th>
                                                        <th class="text-center">{{ __('customer_owed.list.receive_date') }}</th>
                                                        <th class="text-center">{{ __('customer_owed.list.status_pay') }}</th>
                                                        <th class="text-center">{{ __('customer_owed.list.date_pay') }}</th>
                                                        <th>{{__('sale.list.staff_name')}}</th>
                                                        <th class="text-center">{{ __('customer_owed.list.action') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($customer->sales as $sale)
                                                        @php
                                                            $customerOwed = 0;
                                                            $amount = $sale->customerOwed()->exists() ? $sale->customerOwed->amount : $sale->total_amount;
                                                            $receiveAmount = $sale->customerOwed()->exists() ? $sale->customerOwed->receive_amount : 0;
                                                            $customerOwed = ($amount - $receiveAmount);
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $sale->quotaion_no }}</td>
                                                            <td class="text-right">{{ $sale->customerOwed()->exists() ? $sale->customerOwed->amount : $sale->total_amount }}</td>
                                                            <td class="text-right">{{ currencyFormat($customerOwed) }}</td>
                                                            <td class="text-center">{{ $sale->customerOwed()->exists() ? date('Y-m-d h:i', strtotime($sale->customerOwed->receive_date)) : '-'}}</td>
                                                            <td class="text-center">
                                                                <div class="btn-sm text-white font-size-10" style="background:{{$sale->customerOwed()->exists() ? $sale->customerOwed->statusPay()['color']:'#e74a3b'}}">
                                                                    {{ $sale->customerOwed()->exists() ? $sale->customerOwed->statusPay()['statusText'] : 'មិនទាន់សងប្រាក់' }}
                                                                </div>
                                                            </td>
                                                            <td class="text-center">{{ $sale->customerOwed()->exists() ? date('Y-m-d h:i', strtotime($sale->customerOwed->date_pay)) : '-'}}</td>
                                                            <td>{{$sale->staff ? $sale->staff->getFullnameAttribute() : \Auth::user()->name}}</td>
                                                            <td class="text-center">
                                                                <a class="btn btn-sm btn-warning" 
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
                            {{ $customerPayAlls->appends(request()->query())->links() }}
                        </div>
                        @if( Session::has('flash_danger') )
                            <p class="alert text-center {{ Session::get('alert-class', 'alert-danger') }}">
                                <span class="spinner-border spinner-border-sm text-darktext-danger align-middle"></span> {{ Session::get('flash_danger') }}
                            </p>
                        @endif
                    </div>
                    <div class="tab-pane fade {{$request->pay_no_page ? ' active show' : ''}}" id="pay_in_ready">
                        <div class="table-responsive cus-table">
                            <table class="table table-bordered">
                                <thead class="bg-danger text-light">
                                    <tr>
                                        <th class="text-center" style="width: 20px;">#</th>
                                        <th>{{__('customer.list.name')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach( $customerNotPays as $customer)
                                    <tr>
                                        <td class="text-center" rowspan="{{$customer->sales->count() > 0 ? 2 : 1}}">
                                            @if ($customer->sales->count() > 0)
                                            <a href="#customer_{{$customer->id}}" data-toggle="collapse" style="text-decoration: none !important;" class="collapsed"><i class="fas fa-minus-circle"></i></a>
                                            @endif
                                        </td>
                                        <td>{{ $customer->customerFullName() }}</td>
                                    </tr>
                                    @if ($customer->sales->count() > 0)
                                    <tr>
                                        <td colspan="1" id="customer_{{$customer->id}}" class="collapse p-0">
                                            <table class="table mb-0 tabel-row-1">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>{{__('sale.list.invoice_code')}}</th>
                                                        <th>{{ __('customer_owed.list.amount') }}</th>
                                                        <th>{{ __('customer_owed.list.owed_amount') }}</th>
                                                        <th class="text-center">{{ __('customer_owed.list.receive_date') }}</th>
                                                        <th class="text-center">{{ __('customer_owed.list.status_pay') }}</th>
                                                        <th class="text-center">{{ __('customer_owed.list.date_pay') }}</th>
                                                        <th>{{__('sale.list.staff_name')}}</th>
                                                        <th class="text-center">{{ __('customer_owed.list.action') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($customer->sales as $sale)
                                                        @php
                                                            $customerOwed = 0;
                                                            $amount = $sale->customerOwed()->exists() ? $sale->customerOwed->amount : $sale->total_amount;
                                                            $receiveAmount = $sale->customerOwed()->exists() ? $sale->customerOwed->receive_amount : 0;
                                                            $customerOwed = ($amount - $receiveAmount);
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $sale->quotaion_no }}</td>
                                                            <td class="text-right">{{ $sale->customerOwed()->exists() ? $sale->customerOwed->amount : $sale->total_amount }}</td>
                                                            <td class="text-right">{{ currencyFormat($customerOwed) }}</td>
                                                            <td class="text-center">{{ $sale->customerOwed()->exists() ? date('Y-m-d h:i', strtotime($sale->customerOwed->receive_date)) : '-'}}</td>
                                                            <td class="text-center">
                                                                <div class="btn-sm text-white font-size-10" style="background:{{$sale->customerOwed()->exists() ? $sale->customerOwed->statusPay()['color']:'#e74a3b'}}">
                                                                    {{ $sale->customerOwed()->exists() ? $sale->customerOwed->statusPay()['statusText'] : 'មិនទាន់សងប្រាក់' }}
                                                                </div>             
                                                            </td>
                                                            <td class="text-center">{{ $sale->customerOwed()->exists() ? date('Y-m-d h:i', strtotime($sale->customerOwed->date_pay)) : '-'}}</td>
                                                            <td>{{$sale->staff ? $sale->staff->getFullnameAttribute() : \Auth::user()->name}}</td>
                                                            <td class="text-center">
                                                                <a class="btn btn-sm btn-warning" 
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
                            {{ $customerNotPays->appends(request()->query())->links() }}
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