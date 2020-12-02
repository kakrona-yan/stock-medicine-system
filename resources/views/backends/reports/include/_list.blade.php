@push('header-style')
    <style>
        .form-control{
            height: 40px;
        }
    </style>
@endpush
<div class="row mb-2">
    <div class="col-12">
        <!-- Circle Buttons -->
        <div class="card mb-4">
            <div class="card-body">
                <form id="sale-search" action="{{ route('report.index') }}" method="GET" class="form form-horizontal form-search mb-2">
                    <div class="row">
                        <div class="col-12  d-inline-flex">
                            <div class="select-group form-group mb-2 mr-2">
                                <label for="title" class="mr-sm-2">{{__('sale.list.customer_name')}}:</label>
                                <select class="form-control report_customer" id="customer" name="customer_name" placeholder="{{__('sale.list.customer_name')}}">
                                    <option value="all" selected>ALL</option>
                                    @foreach ($customers as $key => $customer)
                                        <option value="{{$key}}" {{ $key == $request->customer_name ? 'selected' : ''}}>{{ $customer }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="select-group form-group mb-2 mr-2">
                                <label for="title" class="mr-sm-2">{{__('sale.list.staff_name')}}:</label>
                                <select class="form-control report_staff" id="staff" name="staff_name" placeholder="{{__('sale.list.staff_name')}}">
                                    <option value="all" selected>ALL</option>
                                    @foreach ($staffs as $key => $staff)
                                        <option value="{{$key}}" {{ $key == $request->staff_name ? 'selected' : ''}}>{{ $staff }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-2 mr-2">
                                <label for='date' class="mr-sm-2">{{__('report.date')}}</label>
                                <div class="d-flex">
                                    <div class="input-group date mr-1" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                                        <input type="text" class="form-control" name="start_date"
                                            value="{{ $request->start_date ? $request->start_date : date('Y-m-d')  }}">
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="far fa-calendar-alt"></span></div>
                                        </div>
                                    </div>
                                    <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                                        <input type="text" class="form-control" name="end_date"
                                            value="{{ $request->end_date ? $request->end_date : date('Y-m-d')  }}">
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="far fa-calendar-alt"></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-circle btn-primary"><i class="fa fa-search"></i> @lang('button.search')</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mt-3">
                                <input id="download_sale" type="hidden" name="download_sale" value="1">
                                <button id="downloadReportSale" type="button" class="btn btn-circle btn-primary"
                                    {{$saleCount == 0 ? 'disabled' : ''}}
                                ><i class="fas fa-file-excel mr-1"></i>DownloadExcel</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="w-100 mb-1">
                    <div class="d-flex justify-content-end">
                        <div class="p-2 border-right bg-success">{{ __('report.total_medicine') }}: {{$sumTotalQuantity}} </div>
                        <div class="p-2 bg-primary">{{ __('report.total_currency') }}: {{$sumTotalamount}} </div>
                      </div>
                </div>
               <div class="table-responsive cus-table">
                    <table class="table table-bordered">
                        <thead class="bg-primary text-light">
                            <tr>
                                <th class="text-center">#</th>
                                <th>{{__('sale.list.invoice_code')}}</th>
                                <th>{{__('sale.list.customer_name')}}</th>
                                <th>{{__('sale.list.staff_name')}}</th>
                                <th>{{__('sale.list.product_name')}}</th>
                                <th>{{__('sale.list.quantity')}}</th>
                                <th>{{__('sale.list.amount')}}</th>
                                <th class="text-center">{{ __('customer_owed.list.status_pay') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sales as $sale)
                                <tr>
                                    <td>{{ $sale->id }}</td>
                                    <td>{{ $sale->quotaion_no }} {{date('h:i', strtotime($sale->sale_date))}}</td>
                                    <td>{{$sale->customer ? $sale->customer->customerFullName() : ''}}</td>
                                    <td>{{$sale->staff ? $sale->staff->getFullnameAttribute() : \Auth::user()->name}}</td>
                                    <td>
                                        @foreach ($sale->productSales as $key => $productSale)
                                            <div><span>{{ $key+1 }}. </span><span>{{$productSale->product ? $productSale->product->title : '' }}</span></div>
                                        @endforeach
                                    </td>
                                    <td> 
                                        @foreach ($sale->productSales as $key => $productSale)
                                            <div><span>{{ $productSale ? $productSale->quantity : 0 }}</span></div>
                                        @endforeach
                                    </td>
                                    <td> 
                                        @foreach ($sale->productSales as $key => $productSale)
                                            <div><span>{{ $productSale ? $productSale->amount : 0 }}</span></div>
                                        @endforeach
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-sm text-white font-size-10 d-inline-block" style="background:{{$sale->customerOwed()->exists() ? $sale->customerOwed->statusPay()['color']:'#e74a3b'}}">
                                            {{ $sale->customerOwed()->exists() ? $sale->customerOwed->statusPay()['statusText'] : 'មិនទាន់សង' }}
                                        </div>             
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $sales->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div><!--/row-->
@push('footer-script')
    <script>
        $(function () {
            $('#downloadReportSale').click(function(){
                $('#download_sale').val(2);
                $('#sale-search').submit();
            });
        });
    </script>
@endpush