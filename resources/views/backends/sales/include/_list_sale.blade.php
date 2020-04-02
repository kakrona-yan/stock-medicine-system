<div class="row mb-2">
    <div class="col-12">
        <!-- Circle Buttons -->
        <div class="card mb-4">
            <div class="card-body">
                @if(Auth::user()->isRoleAdmin() || Auth::user()->isRoleView() || Auth::user()->isRoleEditor())
                <form id="sale-search" action="{{ route('sale.index') }}" method="GET" class="form form-horizontal form-search form-inline mb-2 d-inline-flex">
                    <div class="form-group mb-2 mr-2">
                        <label for="title" class="mr-sm-2">{{__('sale.list.invoice_code')}}:</label>
                        <input type="text" class="form-control mr-1" id="quotaion_no" 
                            name="quotaion_no" value="{{ old('quotaion_no', $request->quotaion_no)}}"
                            placeholder="{{__('sale.list.invoice_code')}}"
                        >
                    </div>
                    <div class="form-group mb-2 mr-2">
                        <label for="title" class="mr-sm-2">{{__('sale.list.customer_name')}}:</label>
                        <input type="text" class="form-control mr-1" id="customer" 
                            name="customer_name" value="{{ old('customer_name', $request->customer_name)}}"
                            placeholder="{{__('sale.list.customer_name')}}"
                        >
                    </div>
                    <div class="form-group mb-2 mr-2">
                        <label for="title" class="mr-sm-2">{{__('sale.list.staff_name')}}:</label>
                        <input type="text" class="form-control mr-1" id="staff_name" 
                            name="staff_name" value="{{ old('staff_name', $request->staff_name)}}"
                            placeholder="{{__('sale.list.staff_name')}}"
                        >
                    </div>
                    <div class="form-group mb-2 mr-2">
                        <label for="sale_date" class="mr-sm-2">{{__('sale.list.sale_date')}}:</label>
                        <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                            <input type="text" class="form-control" name="sale_date"
                                value="{{ old('sale_date', $request->sale_date) }}">
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="far fa-calendar-alt"></span></div>
                            </div>
                        </div>
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
                @endif
               <div class="table-responsive cus-table">
                    <table class="table table-bordered">
                        <thead class="bg-primary text-light">
                            <tr>
                                <th class="text-center">#</th>
                                <th>{{__('sale.list.invoice_code')}}</th>
                                <th>{{__('sale.list.customer_name')}}</th>
                                <th>{{__('sale.list.quantity')}}</th>
                                <th>{{__('sale.list.price')}}</th>
                                <th>{{__('sale.list.received_price')}}</th>
                                <th>{{__('sale.list.owed')}}</th>
                                <th>{{__('sale.list.sale_date')}}</th>
                                <th>{{__('sale.list.staff_name')}}</th>
                                @if(Auth::user()->isRoleAdmin() || Auth::user()->isRoleEditor())
                                <th>{{__('sale.list.action')}}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $sales as $sale)
                                @php
                                   $owed = ($sale->total_amount - $sale->money_change);
                                @endphp
                                <tr>
                                    <td class="text-center">
                                        @if ($sale->productSales->count() > 0)
                                        <a href="#slae_{{$sale->id}}" data-toggle="collapse" style="text-decoration: none !important;" class="collapsed"><i class="fas fa-minus-circle"></i></a>
                                        @endif
                                    </td>
                                    <td>{{$sale->quotaion_no}}</td>
                                    <td>{{$sale->customer ? $sale->customer->customerFullName() : ''}}</td>
                                    <td>{{$sale->total_quantity}}</td>
                                    <td>{{currencyFormat($sale->total_amount)}}</td>
                                    <td>{{currencyFormat($sale->money_change)}}</td>
                                     <td>{{currencyFormat($owed)}}</td>
                                    <td>{{date('Y-m-d h:i', strtotime($sale->sale_date))}}</td>
                                    <td>{{$sale->staff ? $sale->staff->getFullnameAttribute() : \Auth::user()->name}}</td>
                                    @if(Auth::user()->isRoleAdmin() || Auth::user()->isRoleView() || Auth::user()->isRoleEditor())
                                    <td rowspan="{{$sale->productSales->count() > 0 ? 2 : 1}}">   
                                        {{-- <a class="btn btn-circle btn-circle btn-sm btn-warning btn-circle mr-1" 
                                            data-toggle="tooltip" 
                                            data-placement="top"
                                            data-original-title="Invoice #{{$sale->quotaion_no}}"
                                            href="{{route('sale.viewPDF', $sale->id)}}"
                                        ><i class="far far fa-eye"></i>
                                        </a> --}}
                                        <a class="btn btn-circle btn-circle btn-sm btn-success btn-circle mr-1" 
                                            data-toggle="tooltip" 
                                            data-placement="top"
                                            data-original-title="Invoice #{{$sale->quotaion_no}}"
                                            href="{{route('sale.downloadPDF', $sale->id)}}"
                                            ><i class="far fa-file-pdf"></i>
                                        </a>
                                        @if(Auth::user()->isRoleAdmin() || Auth::user()->isRoleEditor())
                                        <a class="btn btn-circle btn-sm btn-warning btn-circle" 
                                            data-toggle="tooltip" 
                                            data-placement="top"
                                            data-original-title="{{__('button.edit')}}"
                                            href="{{route('sale.edit', $sale->id)}}"
                                            ><i class="far fa-edit"></i>
                                        </a>
                                        <button type="button"
                                            id="btn-deleted"
                                            class="btn btn-circle btn-sm btn-danger btn-circle"
                                            onclick="deletePopup(this)"
                                            data-id="{{ $sale->id }}"
                                            data-toggle="modal" data-target="#deleteSale"
                                            title="{{__('button.delete')}}"
                                            ><i class="fa fa-trash"></i>
                                        </button>
                                        @endif
                                    </td>
                                    @endif
                                </tr>
                                @if ($sale->productSales->count() > 0)
                                <tr>
                                    <td colspan="9" id="slae_{{$sale->id}}" class="collapse p-0">
                                        <table class="table table-borderless mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-primary">{{__('sale.list.product_name')}}</th>
                                                    <th class="text-primary">{{__('sale.list.quantity')}}</th>
                                                    <th class="text-primary">{{__('sale.list.product_free')}}</th>
                                                    <th class="text-primary">{{__('sale.list.rate')}}</th>
                                                    <th class="text-primary">{{__('sale.list.amount')}}</th>
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
                                                    <td>{{$productSale->product_free}}</td>
                                                    <td>{{$productSale->rate}}</td>
                                                    <td>{{$productSale->amount}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr class="border-sale--top">
                                                    <td colspan="4" class="text-right text-primary">{{__('sale.list.total')}}</td>
                                                    <td>{{currencyFormat($sale->total_amount)}}</td>
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
<!--Modal delete product-->
<div class="modal fade" id="deleteSale">
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
            <form id="delete_product_form" action="{{route('sale.destroy')}}" method="POST">
                @csrf
                <input type="hidden" type="form-control" name="sale_id">
                <button type="submit" class="btn btn-circle btn-primary px-4">{{__('button.ok')}}</button>
                <button type="button" class="btn btn-circle btn-danger px-4" data-dismiss="modal"
                    onclick="clearData()"
                >{{__('button.close')}}</button>
            </form>
        </div>
    </div>
    </div>
</div>
@push('footer-script')
<script>
    (function( $ ){
        $("[name='limit']").select2({
            allowClear: false
        }).on('select2:select', function (e) {
            $('#sale-search').submit();
        });
    })( jQuery );
    function deletePopup(obj) {
        $('input[name="sale_id"]').val($(obj).attr("data-id"));
        $("#modal-name" ).html($(obj).attr("data-name"));
    }

    function clearData() {
        $('input[name="sale_id"]').val('');
        $("#modal-name" ).html('');
    }
</script>
@endpush