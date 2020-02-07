<div class="row mb-2">
    <div class="col-12">
        <!-- Circle Buttons -->
        <div class="card mb-4">
            <div class="card-body">
                <form id="product-search" action="{{ route('sale.index') }}" method="GET" class="form form-horizontal form-search form-inline mb-2">
                    
                </form>
               <div class="table-responsive cus-table">
                    <table class="table table-bordered">
                        <thead class="bg-primary text-light">
                            <tr>
                                <th class="text-center">#</th>
                                <th>Invoice Code</th>
                                <th>Customer Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Received Price</th>
                                <th>Owed</th>
                                <th>Sale Date</th>
                                <th>Staff</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $sales as $sale)
                                <tr>
                                    <td class="text-center">
                                        @if ($sale->productSales->count() > 0)
                                        <a href="#slae_{{$sale->id}}" data-toggle="collapse" style="text-decoration: none !important;"><i class="fas fa-plus-circle"></i></a>
                                        @endif
                                    </td>
                                    <td>{{$sale->quotaion_no}}</td>
                                    <td>{{$sale->customer ? $sale->customer->name : ''}}</td>
                                    <td>{{$sale->total_quantity}}</td>
                                    <td>{{$sale->total_amount}}</td>
                                    <td>{{$sale->money_change}}</td>
                                     <td>{{$sale->total_amount - $sale->money_change}}</td>
                                    <td>{{date('Y-m-d', strtotime($sale->sale_date))}}</td>
                                    <td>{{$sale->staff ? $sale->staff->getFullnameAttribute() : \Auth::user()->name}}</td>
                                    <td rowspan="{{$sale->productSales->count() > 0 ? 2 : 1}}">
                                        <a class="btn btn-circle btn-circle btn-sm btn-warning btn-circle mr-1" 
                                            data-toggle="tooltip" 
                                            data-placement="top"
                                            data-original-title="Invoice #{{$sale->quotaion_no}}"
                                            href="{{route('sale.viewPDF', $sale->id)}}"
                                        ><i class="far far fa-eye"></i>
                                        </a>
                                        <a class="btn btn-circle btn-circle btn-sm btn-danger btn-circle mr-1" 
                                            data-toggle="tooltip" 
                                            data-placement="top"
                                            data-original-title="Invoice #{{$sale->quotaion_no}}"
                                            href="{{route('sale.downloadPDF', $sale->id)}}"
                                        ><i class="far fa-file-pdf"></i>
                                        
                                    </td>
                                </tr>
                                @if ($sale->productSales->count() > 0)
                                <tr>
                                    <td colspan="9" id="slae_{{$sale->id}}" class="collapse p-0">
                                        <table class="table table-borderless mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-primary">Product Name</th>
                                                    <th class="text-primary">Quantity</th>
                                                    <th class="text-primary">Rate</th>
                                                    <th class="text-primary">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                                $total = 0;
                                            @endphp
                                            @foreach ($sale->productSales as $productSale)
                                                @php
                                                    $total +=$productSale->amount;
                                                @endphp
                                                <tr class="border-sale">
                                                    <td class="w-47">{{$productSale->product ? $productSale->product->title : '' }}</td>
                                                    <td>{{$productSale->quantity}}</td>
                                                    <td>{{$productSale->rate}}</td>
                                                    <td>{{$productSale->amount}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr class="border-sale--top">
                                                    <td colspan="3" class="text-right text-primary">Total</td>
                                                    <td>{{$total}}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </td>
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
</div><!--/row-->