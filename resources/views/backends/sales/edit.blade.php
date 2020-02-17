@extends('backends.layouts.master')
@section('title', 'Create Sale Product')
<style>
    .span-p{
        position: absolute;
        right: -4px;
        top: 5px;
    }
</style>
@section('content')
<div id="category-list">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between border-bottom mb-3 pb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        {{ __('dashboard.title') }}
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <span class="sub-title"> Sale Product</span>
                </li>
            </ol>
        </nav>
        <a href="{{route('sale.index')}}" 
            class="btn btn-circle btn-primary"
            data-toggle="tooltip" 
            data-placement="left" title="" 
            data-original-title="Sale Product"
        ><i class="fas fa-list mr-1"></i> Sale Product</a>
    </div>
    <div class="row mb-2">
        <div class="col-12 col-sm-12 tab-card">
            <!-- Circle Buttons -->
            <div class="card mb-4">
                <div id="supplierList" class="card-body collapse show">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div id="addsupplier" class="tab-pane active">
                            <form id="form_sale_stock" class="form-main" action="{{route('sale.update', $sale->id)}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4 flex-sm-row-reverse flex-md-row-reverse flex-lg-row-reverse">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-5 mb-3">
                                        <div class="form-group" style="background: #eaecf4;padding: 10px;">
                                            <button type="submit" class="btn btn-circle btn-primary w-100 mr-2"><i class="fas fa-money-bill-alt mr-2"></i>Sale</button>
                                        </div>
                                        <fieldset class="edit-master-registration-fieldset">
                                            <div class="form-group select-group row mb-4">
                                                <label class="col-12 col-sm-12 col-md-12 col-lg-3 col-form-label" for="invoiceCode">Category</label>
                                                <div class="col-12 col-sm-12 col-md-12 col-lg-9">
                                                    <select class="form-control" id="category_id" name="category_id">
                                                        <option value="">Please select</option>
                                                        @foreach($categories as $id => $name)
                                                            <option value="{{ $id }}" {{ $id == 1 || $id == $request->category_id ? 'selected' : '' }}>{{ $name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <legend class="edit-application-information-legend text-left">Product List:</legend>
                                            <div class="form-group select-group list-product">
                                                <div id="list_product"></div>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-7 mb-3">
                                        <fieldset class="edit-master-registration-fieldset">
                                            <legend class="edit-application-information-legend text-left">Sale:</legend>
                                            <div class="form-group row">
                                                <label class="col-12 col-sm-12 col-md-12 col-lg-3 col-form-label" for="invoiceCode">Invoice Code</label>
                                                <div class="col-12 col-sm-12 col-md-12 col-lg-9">
                                                    <input type="text" class="form-control" id="invoiceCode" name="quotaion_no" readonly="" 
                                                    value="{{old('quotaion_no', $sale->quotaion_no)}}">
                                                </div>
                                            </div>
                                            <div class="form-group select-group row">
                                                <label class="col-12 col-sm-12 col-md-12 col-lg-3 col-form-label" for="invoiceCode">Customer <span class="text-danger">*</span></label>
                                                <div class="col-12 col-sm-12 col-md-12 col-lg-9">
                                                    <select class="form-control" id="customer_id" name="customer_id">
                                                        <option value="">Please select</option>
                                                        @foreach($customers as $id => $name)
                                                            <option value="{{ $id }}" {{ $id == $sale->customer_id ? 'selected' : '' }}>{{ $name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="text-danger">
                                                        <strong id="customer_id_error">{{ $errors->first('customer_id') }}</strong>
                                                    </span>
                                                </div>
                                            </div>
                                            @if(\Auth::user()->isRoleAdmin() || \Auth::user()->isRoleEditor())
                                            <div class="form-group select-group row">
                                                <label class="col-12 col-sm-12 col-md-12 col-lg-3 col-form-label" for="invoiceCode">Staff</label>
                                                <div class="col-12 col-sm-12 col-md-12 col-lg-9">
                                                    <select class="form-control" id="staff_id" name="staff_id">
                                                        <option value="">Please select</option>
                                                        @foreach($staffs as  $staff)
                                                            <option value="{{ $staff->id }}" {{ $staff->id  == $sale->staff_id ? 'selected' : '' }}>{{ $staff->getFullnameAttribute() }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="form-group select-group row">
                                                <label class="col-12 col-sm-12 col-md-12 col-lg-3 col-form-label" for="invoiceCode">Date Sale <span class="text-danger">*</span></label>
                                                <div class="col-12 col-sm-12 col-md-12 col-lg-9">
                                                    <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                                                        <input type="text" class="form-control" name="sale_date"
                                                            value="{{ old('sale_date', date('Y-m-d', strtotime($sale->sale_date))) }}">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><span class="far fa-calendar-alt"></span></div>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('sale_date'))
                                                        <span class="text-danger">
                                                            <strong>{{ $errors->first('sale_date') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </fieldset>
                                        <div class="row">
                                            <div class="col-12 col-sm-12 col-md-12 mb-3 mt-md-2">
                                                <fieldset class="edit-master-registration-fieldset">
                                                    <legend class="edit-application-information-legend text-left">Sale Product:</legend>
                                                    <div class="table-responsive cus-table">
                                                    <table class="table table-striped table-bordered">
                                                        <thead class="bg-primary text-light">
                                                            <tr>
                                                                <th style="width: 50px;">#</th>
                                                                <th>Product Name</th>
                                                                <th>Quantity</th>
                                                                <th>Product Free</th>
                                                                <th>Unit Price</th>
                                                                <th>Total</th>
                                                                <th style="width: 20px;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="dynamic_sale_product">
                                                            <input id="sale_ids" type="hidden" name="sale_ids" value="">
                                                            @php
                                                            $i = 0;
                                                            $saleQuantities = 0;
                                                            $saleProductFree = 0;
                                                            @endphp
                                                            @foreach ($sale->productSales as $productSale)
                                                            <tr id="sale_product_{{$i}}">
                                                                <td>
                                                                <input type="hidden" class="form-control" name="sale_product[{{$i }}][product_id]" value="{{$productSale->product_id}}">{{$productSale->product_id}}
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" value="{{$productSale->product->title}}" readonly="">
                                                                </td>
                                                                <td>
                                                                    <input type="number" min="0" id="quantity_{{$i}}" data-id="{{$i}}" data-quantity="{{$productSale->quantity}}" class="form-control" name="sale_product[{{$i}}][quantity]" value="{{$productSale->quantity}}" oninput="updateQuantity(this)">
                                                                </td>
                                                                <td>
                                                                    <input type="number" min="0" id="productFree_{{$i}}" data-id="{{$i}}" data-productfree="{{$productSale->product_free}}" class="form-control" name="sale_product[{{$i}}][product_free]" value="{{$productSale->product_free}}" oninput="updateProductFree(this)">
                                                                </td>
                                                                <td>
                                                                    <input type="text" min="0" id="rate_{{$i}}" data-id="{{$i}}" data-rate="{{$productSale->rate}}" class="form-control sale_rate" name="sale_product[{{$i}}][rate]" value="{{$productSale->rate}}" oninput="updateRate(this)">
                                                                </td>
                                                                <td>
                                                                    <input type="text" id="amount_{{$i}}" class="form-control" name="sale_product[{{$i}}][amount]" value="{{$productSale->amount}}" readonly="">
                                                                </td>
                                                                <td class="text-center">    
                                                                    <button type="button" data-sale-id="{{$productSale->id}}" data-id="{{$i}}" data-quantity="{{$productSale->quantity}}" data-amount="{{$productSale->amount}}" class="remove_product btn btn-circle btn-circle btn-sm btn-danger btn-circle"><i class="fa fa-trash"></i></button>
                                                                </td>
                                                            </tr>
                                                            @php
                                                                $i ++;
                                                                $saleQuantities += $productSale->quantity;
                                                                $saleProductFree += $productSale->product_free;
                                                            @endphp
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-md-12">
                                                <fieldset class="edit-master-registration-fieldset">
                                                    <legend class="edit-application-information-legend text-left">Payment:</legend>
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-12 col-md-12 col-form-label" for="total_quantity">Total Quantity = Quantities + Product Frees</label>
                                                        <div class="col-4 col-md-4">
                                                            <input type="text" class="form-control" id="total_quantity" name="total_quantity" readonly="" value="{{ old('total_quantity', $sale->total_quantity ? $sale->total_quantity : 0) }}">
                                                            <span class="span-p">=</span>
                                                        </div>
                                                        <div class="col-4 col-md-4">
                                                            <input type="text" class="form-control" id="quantities" readonly="" value="{{$saleQuantities}}">
                                                            <span class="span-p">+</span>
                                                        </div>
                                                        <div class="col-4 col-md-4">
                                                            <input type="text" class="form-control" id="productFrees" readonly="" value="{{$saleProductFree}}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-12 col-md-12 col-form-label" for="total_amount">Total Amount</label>
                                                        <div class="col-12 col-sm-12 col-md-12">
                                                            <input type="text" class="form-control" id="total_amount" name="total_amount" value="{{ old('total_amount', $sale->total_amount ? $sale->total_amount : 0) }}"
                                                                {{ !Auth::user()->isRoleAdmin() ? "readonly" : '' }}
                                                            >
                                                        </div>
                                                    </div>
                                                    @if(Auth::user()->isRoleAdmin() || Auth::user()->isRoleEditor())
                                                     <div class="form-group row">
                                                        <label class="col-12 col-sm-12 col-md-12 col-form-label" for="total_discount">Dicount</label>
                                                        <div class="col-12 col-sm-12 col-md-12">
                                                            <input type="text" class="form-control" id="total_discount" name="total_discount" value="{{ old('dototal_discountb', $sale->total_discount ? $sale->total_discount : 0) }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-12 col-md-12 col-form-label" for="total_money_revice">Received Amount<span class="text-danger">*</span></label>
                                                        <div class="col-12 col-sm-12 col-md-12 ">
                                                            <input type="text" class="form-control" id="total_money_revice" name="money_change" value="{{ old('money_change', $sale->money_change ? $sale->money_change : 0) }}"
                                                                oninput="calculatorMoney(this)"
                                                            >
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-12 col-md-12 col-lg-3 col-form-label" for="money_owed">Owed</label>
                                                        <div class="col-12 col-sm-12 col-md-12 col-lg-9">
                                                            <input type="text" class="form-control" id="money_owed" value="0">
                                                        </div>
                                                    </div>
                                                    @endif
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-12 col-md-12 col-lg-3 col-form-label" for="note">Note</label>
                                                        <div class="col-12 col-sm-12 col-md-12 col-lg-9">
                                                            <textarea class="form-control" name="note">{{ old('note', $sale->note) }}</textarea>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-7">
                                        <div class="form-group mt-2" style="background: #eaecf4;padding: 10px;">
                                            <button type="submit" class="btn btn-circle btn-primary w-100 mr-2"><i class="fas fa-money-bill-alt mr-2"></i>Sale</button>
                                        </div>
                                    </div>
                                </div>
                            </form><!--/form-main-->
                        </div><!--/tab-add-category-->
                    </div>
                </div>
            </div>
        </div>
    </div><!--/row-->
</div>
@endsection
@push('footer-script')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
    $(function(){
        const formSale = $('#form_sale_stock');
        formSale.submit(function(e) {
            e.preventDefault();
            $.ajax({
                url     : formSale.attr('action'),
                type    : formSale.attr('method'),
                data    : formSale.serialize(),
                dataType: 'json',
                success : function (json) {
                    location.href = '{{ route("sale.index") }}';
                },
                error: function(json){
                    $.each(json.responseJSON.errors, function (key, value) {
                        $(`#${key}_error`).text(value);
                    });
                    $("html, body").animate({ scrollTop: 0 }, 500);
                }
            });
        });
    });
    // filter product
    $(function(){
        if($("#category_id").val() != ''){
            let routeUrl = "{{config('app.url')}}/sales/product";
            let category_id = $("#category_id").val();
            $.ajax({
                url     : routeUrl,
                type    : 'GET',
                data    : {category_id},
                dataType: 'json',
                success : function (data) {
                    if(data.code == 200) {
                        $('#list_product').html(renderProduct(data.productOrders));
                    }
                },
                error: function(json){
                }
            });
        }
        $("#category_id").select2({
            allowClear: false
        }).on('select2:select', function (e) {
            let routeUrl = "{{config('app.url')}}/sales/product";
            let category_id = e.params.data.id;
            $.ajax({
                url     : routeUrl,
                type    : 'GET',
                data    : {category_id},
                dataType: 'json',
                success : function (data) {
                    if(data.code == 200) {
                        $('#list_product').html(renderProduct(data.productOrders));
                    }
                },
                error: function(json){
                }
            });
        });
        $('#customer_id, #staff_id').select2({
            allowClear: false
        });
        $("input[name=total_discount], input[name=money_change], input[name=money_change], input[name=total_amount]").keydown(function (e) {
            allowNumber(e)
        });
    });
   
    function allowNumber(e) {
            // Deny if double dot is inputed
            if (e.keyCode == 190) {
                e.preventDefault();
                return;
            }
            // Allow: backspace, delete, tab, escape, enter
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
                // Allow: Ctrl+A, Command+A
                (e.keyCode === 190 || e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                // Allow: home, end, left, right, down, up
                (e.keyCode >= 35 && e.keyCode <= 40)) {
                    // let it happen, don't do anything
                    return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
    }
    /**
    * Render is html
    * @param productOrders
    * return {html}
    */
    function renderProduct(productOrders) {
        let html = '';
        html +='<div class="check-product">';
        html +='<ul class="m-0 p-0">';
        for (let index = 0; index < productOrders.length; index++) {
            const productOrder = productOrders[index];
            
            let img = productOrder.thumbnail ? `{{config('app.url')}}/storage/images/product/${productOrder.thumbnail}` : '{{config('app.url')}}//images/no-thumbnail.jpg';
            html +='<li>';
            html +=`<input type="checkbox" id="product_${productOrder.id}" onclick="checkSaleProduct(${productOrder.id}, '${productOrder.title}', '${productOrder.price}')"/>`;
            html +=`<label for="product_${productOrder.id}">`;
            html +=`<img src="${img}" />`;
            html +=`<div class="py-1 text-center pro-title">${productOrder.title}</div>`;
            html +=`<div class="py-1 text-center text-danger">${productOrder.price}$</div>`;
            html +='</label>';
            html +='</li>';
        }
        html +='</ul></div>';
        return html;
    }
    var index = {{$i}};
    var i = index;
    // var totalQuantity = 0;
    var totalAmount = {{$sale->total_amount}};
    var amountProductFree = {{$saleProductFree}};
    var amountQuantity = {{$saleQuantities}};

    function checkSaleProduct(id, title, price) {
        let html = '<tr id="sale_product_'+i+'">';
            html += '<td><input type="hidden" class="form-control" name="sale_product['+i+'][product_id]" value="'+id+'"/>'+id+'</td>';
            html += '<td><input type="text" class="form-control" value="'+title+'" readonly/></td>';
            html += '<td><input type="number" min="0" id="quantity_'+i+'" data-id="'+i+'" data-quantity="1" class="form-control" name="sale_product['+i+'][quantity]" value="1" oninput="updateQuantity(this)"/></td>';
            html += '<td><input type="number" min="0" id="productFree_'+i+'" data-id="'+i+'" data-productFree="0" class="form-control" name="sale_product['+i+'][product_free]" value="0" oninput="updateProductFree(this)"/></td>';
            html += '<td><input type="text" min="0" id="rate_'+i+'" data-id="'+i+'" data-rate="'+price+'" class="form-control sale_rate" name="sale_product['+i+'][rate]" value="'+price+'" oninput="updateRate(this)"/></td>';
            html += '<td><input type="text" id="amount_'+i+'" class="form-control" name="sale_product['+i+'][amount]" value="'+price+'" readonly /></td>';
            html += '<td class="text-center">';
            html += '    <button type="button" data-id="'+i+'" data-quantity="1" data-amount="'+price+'" class="remove_product btn btn-circle btn-circle btn-sm btn-danger btn-circle"><i class="fa fa-trash"></i></button>';
            html += '</td>';
            html += '</tr>';
        $('#dynamic_sale_product').append(html);
        $('#product_'+id).prop('disabled', true);
        // calculator
        let quantity = $('#quantity_'+i).val();
        let rate = formatMoney($('#rate_'+i).val());
        let productFree = $('#productFree_'+i).val();
        let amount = Number(quantity) * Number(rate);
        
        amountQuantity += Number(quantity);
        amountProductFree += Number(productFree);
        totalAmount += Number(amount);
        
        $('#quantities').val(amountQuantity);
        $('#productFrees').val(amountProductFree);
        let totalQuantity = amountQuantity + amountProductFree;
        $('input[name="total_quantity"]').val(totalQuantity);
        $('input[name="total_amount"]').val(totalAmount.toFixed(2));
        i++;

        $('.sale_rate').keypress(function(event) {
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });
    }
    // remove product
    var element = [];
    $(document).on('click', '.remove_product', function(e){
        e.preventDefault();
        let id = $(this).attr("data-id");
        let quantity = $('#quantity_'+id).val();
        let amount = formatMoney($('#amount_'+id).val());
        let productFree = $('#productFree_'+id).val();
        let totalQuantityPayment = $('#quantities').val();
        let totalProductFreePayment = $('#productFrees').val();
        let totalAmountPayment = formatMoney($('input[name="total_amount"]').val());

        totalQuantityPayment = Number(totalQuantityPayment) - Number(quantity);
        totalProductFreePayment = Number(totalProductFreePayment) - Number(productFree);
        totalAmountPayment = Number(totalAmountPayment) - Number(amount);

        let totalQuantity = Number(totalQuantityPayment) + Number(totalProductFreePayment);

        $('#quantities').val(totalQuantityPayment);
        $('#productFrees').val(totalProductFreePayment);
        $('input[name="total_quantity"]').val(totalQuantity);
        $('input[name="total_amount"]').val(totalAmountPayment.toFixed(2));

        amountQuantity = totalQuantityPayment;
        amountProductFree = totalProductFreePayment;
        totalAmount = totalAmountPayment;
        $('#sale_product_'+id).remove();
        $('#product_'+id).prop('checked', false);
        $('#product_'+id).prop('disabled', false);
        var saleId = $(this).attr('data-sale-id');
        console.log(saleId);
        
		element.push(saleId);
        $('#sale_ids').val(element);
        i--;
    });
    // update quantity
    function updateQuantity(data){
        let id = $(data).attr("data-id");
        let quantity = $('#quantity_'+id).val();
        let oldQuantity = $(data).attr("data-quantity");
        let rate = formatMoney($('#rate_'+id).val());
        let oldAmount = formatMoney($('#amount_'+id).val());
        // Payment
        let totalQuantityPayment = $('#quantities').val();
        let totalAmountPayment = formatMoney($('input[name="total_amount"]').val());
        let amount = Number(quantity) * Number(rate);

        totalQuantityPayment = Number(totalQuantityPayment) - Number(oldQuantity);
        totalQuantityPayment += Number(quantity);
        totalAmountPayment = totalAmountPayment - oldAmount;
        totalAmountPayment += Number(amount);
        let totalQuantity =  Number(totalQuantityPayment) + Number(amountProductFree);
        // output value
        $('#quantities').val(totalQuantityPayment)
        $('input[name="total_quantity"]').val(totalQuantity);
        $('input[name="total_amount"]').val(totalAmountPayment.toFixed(2));
        $('#amount_'+id).val(amount.toFixed(2));
        $(data).attr('data-quantity', quantity);
        amountQuantity = totalQuantityPayment;
        totalAmount = formatMoney(totalAmountPayment);
    }
    // update Rate 
    function updateRate(data){
        let id = $(data).attr("data-id");
        let quantity = $('#quantity_'+id).val();
        let rate = formatMoney($('#rate_'+id).val());
        let oldRate = formatMoney($(data).attr("data-rate"));
        let oldAmount = formatMoney($('#amount_'+id).val());
        
        // Payment
        let totalAmountPayment = formatMoney($('input[name="total_amount"]').val());
        let amount = Number(quantity) * Number(rate);
        totalAmountPayment = Number(totalAmountPayment) - Number(oldAmount);
        totalAmountPayment += Number(amount);
        // output value
        $('input[name="total_amount"]').val(totalAmountPayment.toFixed(2));
        $('#amount_'+id).val(amount.toFixed(2));
        $(data).attr('data-rate', rate);
        totalAmount = formatMoney(totalAmountPayment);
    }
    // calculatorMoney
    function calculatorMoney(data) {
        let revicePrice = $(data)[0] ? $(data)[0].value : 0;
        let totalAmountPayment = formatMoney($('input[name="total_amount"]').val());
        let moneyOwed = Number(totalAmountPayment)- Number(revicePrice);
        $("#money_owed").val(moneyOwed.toFixed(2));
    }
    // updateProductFree
    function updateProductFree(data) {
        let id = $(data).attr("data-id");
        let productFree = $('#productFree_'+id).val();
        let oldProductFree = $(data).attr("data-productFree");
        // Payment
        let productFreePayment = $('#productFrees').val();
        productFreePayment = Number(productFreePayment) - Number(oldProductFree)
        productFreePayment += Number(productFree);
        let totalQuantityPayment = Number(amountQuantity) + Number(productFreePayment)
        // output value
        $('#productFrees').val(productFreePayment);
        $('input[name="total_quantity"]').val(totalQuantityPayment);
        $(data).attr('data-productFree', productFree);
        amountProductFree = productFreePayment;
        
    }
    // format money
    function formatMoney(money) {
        return money != '' ? Number(money).toFixed(2) : 0;
    }
</script>
@endpush