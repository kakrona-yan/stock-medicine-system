@extends('backends.layouts.master')
@section('title', 'RRPS-PHARMA | លក់ផលិតផល')
<style>
    .span-p{
        position: absolute;
        right: -4px;
        top: 5px;
    }
</style>
@section('content')
<div id="sale-list">
    @if(\Auth::user()->isRoleStaff())
    <div class="row {{Auth::user()->isRoleStaff() ? 'sp-staff-block' : ''}}">
        <div class="col-6 mb-4 p-1">
            <div class="card shadow py-2 border-success">
                <div class="card-body text-center">
                    <a href="{{route('sale.index')}}"><i class="far fa-newspaper text-warning"></i> {{__('menu.sale')}}</a>
                </div>
            </div>
        </div>
        <div class="col-6  mb-4 p-1">
            <div class="card shadow py-2 border-success">
                <div class="card-body text-center">
                    <a href="{{route('customer.index')}}"><i class="fas fa-users text-danger"></i> {{__('menu.customer')}}</a>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Page Heading -->
    <div class="border-bottom mb-3 pb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-3">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        {{ __('dashboard.title') }}
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <span class="sub-title"> {{__('sale.sub_title')}}</span>
                </li>
            </ol>
        </nav>
        <a href="{{route('sale.index')}}" 
            class="btn btn-circle btn-primary"
            data-toggle="tooltip" 
            data-placement="left" title="" 
            data-original-title="Sale Product"
        ><i class="fas fa-list mr-1"></i> {{__('sale.sub_title')}}</a>
    </div>
    @endif
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
                                    <div class="col-12 col-md-12 mb-3 ">
                                        <fieldset class="edit-master-registration-fieldset">
                                            <legend class="edit-application-information-legend text-left">{{__('sale.form.sale')}}:</legend>
                                            <div class="form-group select-group row">
                                                <label class="col-12 col-sm-12 col-md-12 col-lg-3 col-form-label" for="sale_date">{{__('sale.form.date_sale')}} <span class="text-danger">*</span></label>
                                                <div class="col-12 col-sm-12 col-md-12 col-lg-9">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="sale_date"
                                                            value="{{ old('sale_date', date('Y-m-d', strtotime($sale->sale_date))) }}" readonly="">
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
                                            <div class="form-group row">
                                                <label class="col-12 col-sm-12 col-md-12 col-lg-3 col-form-label" for="quotaion_no">{{__('sale.form.invoice_code')}}</label>
                                                <div class="col-12 col-sm-12 col-md-12 col-lg-9">
                                                    <input type="text" class="form-control" id="invoiceCode" name="quotaion_no" readonly="" 
                                                    value="{{old('quotaion_no', $sale->quotaion_no)}}">
                                                </div>
                                            </div>
                                            <div class="form-group select-group row">
                                                <label class="col-12 col-sm-12 col-md-12 col-lg-3 col-form-label" for="customer_id">{{__('sale.list.customer_name')}} <span class="text-danger">*</span></label>
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
                                            @if(\Auth::user()->isRoleAdmin() || \Auth::user()->isRoleEditor() || \Auth::user()->isRoleView())
                                            <div class="form-group select-group row">
                                                <label class="col-12 col-sm-12 col-md-12 col-lg-3 col-form-label" for="staff_id">{{__('sale.list.staff_name')}}</label>
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
                                        </fieldset>
                                    </div>
                                    
                                    <div class="col-12 col-sm-12 col-md-3 col-lg-3 mb-3">
                                        <fieldset class="edit-master-registration-fieldset">
                                            <div class="form-group select-group row mb-4">
                                                <div class="col-12 col-sm-12 col-md-12">
                                                    <select class="form-control" id="product_id" name="product_category_id">
                                                        <option value="">{{__('sale.select')}}</option>
                                                        @foreach($products as $id => $name)
                                                            <option value="{{ $id }}" {{ $id == $request->product_category_id ? 'selected' : '' }}>{{ $name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <legend class="edit-application-information-legend text-left">{{__('sale.form.product_list')}}:</legend>
                                            <div class="form-group select-group list-product">
                                                <div id="list_product"></div>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-9 col-lg-9 mb-3">
                                        <div class="row">
                                            <div class="col-12 col-sm-12 col-md-12 mb-3 ">
                                                <fieldset class="edit-master-registration-fieldset">
                                                    <legend class="edit-application-information-legend text-left">{{__('sale.sub_title')}}:</legend>
                                                    <div class="table-responsive cus-table">
                                                        <table class="table table-striped table-bordered">
                                                            <thead class="bg-primary text-light hide-sp">
                                                                <tr>
                                                                    <th style="width: 50px;" class="sale-no-sp">#</th>
                                                                    <th style="width: 280px;">{{__('sale.form.pro_name')}}</th>
                                                                    <th style="width: 100px;">{{__('sale.form.q_t')}}</th>
                                                                    <th style="width: 100px;">{{__('sale.form.pro_free')}}</th>
                                                                    <th style="width: 200px;">{{__('sale.form.rate')}}</th>
                                                                    <th style="width: 200px;">{{__('sale.list.amount')}}</th>
                                                                    <th style="width: 20px;">{{__('sale.form.action')}}</th>
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
                                                                <td class="sale-no-sp">
                                                                <input type="hidden" class="form-control" name="sale_product[{{$i }}][sale_id]" value="{{$productSale->id}}">
                                                                <input type="hidden" class="form-control" name="sale_product[{{$i }}][product_id]" value="{{$productSale->product_id}}">
                                                                <span class="sale-no-sp">លេខរៀងផលិតផលទី {{$productSale->product_id}}</span>
                                                                </td>
                                                                <td>
                                                                    <div class="sale-hide-sp">{{__('sale.form.pro_name')}}</div>
                                                                    <input type="text" class="form-control" value="{{$productSale->product->title}}" readonly="">
                                                                </td>
                                                                <td class="tb-6">
                                                                    <div class="sale-hide-sp">{{__('sale.form.q_t')}}</div>
                                                                    <input type="number" min="0" id="quantity_{{$i}}" data-id="{{$i}}" data-quantity="{{$productSale->quantity}}" class="form-control" name="sale_product[{{$i}}][quantity]" value="{{$productSale->quantity}}" oninput="updateQuantity(this)">
                                                                </td>
                                                                <td class="tb-6">
                                                                    <div class="sale-hide-sp">{{__('sale.form.pro_free')}}</div>
                                                                    <input type="text" id="productFree_{{$i}}" data-id="{{$i}}" data-productfree="{{$productSale->product_free}}" class="form-control sale_rate" name="sale_product[{{$i}}][product_free]" value="{{$productSale->product_free}}" oninput="updateProductFree(this)">
                                                                </td>
                                                                <td class="tb-6">
                                                                    <div class="sale-hide-sp">{{__('sale.form.rate')}}</div>
                                                                    <input type="text" min="0" id="rate_{{$i}}" data-id="{{$i}}" data-rate="{{$productSale->rate}}" class="form-control sale_rate" name="sale_product[{{$i}}][rate]" value="{{$productSale->rate}}" oninput="updateRate(this)">
                                                                </td>
                                                                <td class="tb-6">
                                                                    <div class="sale-hide-sp">{{__('sale.list.amount')}}</div>
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
                                            <div class="col-12 col-sm-12 col-md-12 mt-3">
                                                <fieldset class="edit-master-registration-fieldset">
                                                    <legend class="edit-application-information-legend text-left">{{__('sale.form.payment')}}:</legend>
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-12 col-md-12 col-form-label" for="total_quantity">{{__('sale.form.sum')}}</label>
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
                                                        <label class="col-12 col-sm-12 col-md-12 col-form-label" for="total_amount">{{__('sale.form.total_amount')}}</label>
                                                        <div class="col-12 col-sm-12 col-md-12">
                                                            <input type="text" class="form-control" id="total_amount" name="total_amount" value="{{ old('total_amount', $sale->total_amount ? $sale->total_amount : 0) }}"
                                                                {{ !Auth::user()->isRoleAdmin() ? "readonly" : '' }}
                                                            >
                                                        </div>
                                                    </div>
                                                    {{-- @if(Auth::user()->isRoleAdmin() || Auth::user()->isRoleEditor())
                                                     <div class="form-group row">
                                                        <label class="col-12 col-sm-12 col-md-12 col-form-label" for="total_discount">{{__('sale.form.dicount')}}</label>
                                                        <div class="col-12 col-sm-12 col-md-12">
                                                            <input type="text" class="form-control" id="total_discount" name="total_discount" value="{{ old('dototal_discountb', $sale->total_discount ? $sale->total_discount : 0) }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-12 col-md-12 col-form-label" for="total_money_revice">{{__('sale.form.received_amount')}}<span class="text-danger">*</span></label>
                                                        <div class="col-12 col-sm-12 col-md-12 ">
                                                            <input type="text" class="form-control" id="total_money_revice" name="money_change" value="{{ old('money_change', $sale->money_change ? $sale->money_change : 0) }}"
                                                                oninput="calculatorMoney(this)"
                                                            >
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        @php
                                                            $owed = ($sale->total_amount - $sale->money_change);
                                                        @endphp
                                                        <label class="col-12 col-sm-12 col-md-12 col-lg-3 col-form-label" for="money_owed">{{__('sale.form.owed')}}</label>
                                                        <div class="col-12 col-sm-12 col-md-12 col-lg-9">
                                                            <input type="text" class="form-control" id="money_owed" value="{{currencyFormat($owed)}}">
                                                        </div>
                                                    </div>
                                                    @endif --}}
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-12 col-md-12 col-lg-3 col-form-label" for="note">{{__('sale.form.note')}}</label>
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
        $('.sale_rate').keypress(function(event) {
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
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
        if($("#product_id").val() != ''){
            let routeUrl = "{{config('app.url')}}/sales/product";
            let product_id = e.params.data.id;
            $.ajax({
                url     : routeUrl,
                type    : 'GET',
                data    : {product_id},
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
        $("#product_id").select2({
            allowClear: false
        }).on('select2:select', function (e) {
            let routeUrl = "{{config('app.url')}}/sales/product";
            let product_id = e.params.data.id;
            $.ajax({
                url     : routeUrl,
                type    : 'GET',
                data    : {product_id},
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
        $('input[name=total_discount], input[name=money_change], input[name=money_change], input[name=total_amount]').keypress(function(event) {
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
    });
   
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
    
    var i = {{$i}};
    // var totalQuantity = 0;
    var totalAmount = {{$sale->total_amount}};
    var amountProductFree = {{$saleProductFree}};
    var amountQuantity = {{$saleQuantities}};

    function checkSaleProduct(id, title, price) {
        let html = '<tr id="sale_product_'+i+'">';
            html += '<td class="sale-no-sp sale-bg-no"><input type="hidden" class="form-control" name="sale_product['+i+'][product_id]" value="'+id+'"/><span class="sale-no-sp">លេខរៀងផលិតផលទី '+id+'</span></td>';
            html += '<td><div class="sale-hide-sp">{{__('sale.form.pro_name')}}<input type="text" class="form-control w-260" value="'+title+'" readonly/></td>';
            html += '<td class="tb-6"><div class="sale-hide-sp">{{__('sale.form.q_t')}}</div><input type="number" min="0" id="quantity_'+i+'" data-id="'+i+'" data-quantity="1" class="form-control" name="sale_product['+i+'][quantity]" value="1" oninput="updateQuantity(this)"/></td>';
            html += '<td class="tb-6"><div class="sale-hide-sp">{{__('sale.form.pro_free')}}</div><input type="tect" id="productFree_'+i+'" data-id="'+i+'" data-productFree="0" class="form-control sale_rate" name="sale_product['+i+'][product_free]" value="0" oninput="updateProductFree(this)"/></td>';
            html += '<td class="tb-6"><div class="sale-hide-sp">{{__('sale.list.rate')}}</div><input type="text" min="0" id="rate_'+i+'" data-id="'+i+'" data-rate="'+price+'" class="form-control sale_rate" name="sale_product['+i+'][rate]" value="'+price+'" oninput="updateRate(this)"/></td>';
            html += '<td class="tb-6"><div class="sale-hide-sp">{{__('sale.list.amount')}}</div><input type="text" id="amount_'+i+'" class="form-control" name="sale_product['+i+'][amount]" value="'+price+'" readonly /></td>';
            html += '<td class="text-center">';
            html += '    <button type="button" data-id="'+i+'" data-quantity="1" data-amount="'+price+'" class="remove_product btn btn-circle btn-circle btn-sm btn-danger btn-circle"><i class="fa fa-trash"></i></button>';
            html += '</td>';
            html += '</tr>';
        $('#dynamic_sale_product').append(html);
        $('#product_'+id).prop('disabled', true);
        // calculator
        let quantity = $('#quantity_'+i).val();
        let rate = $('#rate_'+i).val();
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
        let amount = $('#amount_'+id).val();
        let productFree = $('#productFree_'+id).val();
        let totalQuantityPayment = $('#quantities').val();
        let totalProductFreePayment = $('#productFrees').val();
        let totalAmountPayment = $('input[name="total_amount"]').val();

        totalQuantityPayment = Number(totalQuantityPayment) - Number(quantity);
        totalProductFreePayment = Number(totalProductFreePayment) - Number(productFree);
        totalAmountPayment = Number(totalAmountPayment) - Number(amount);

        let totalQuantity = Number(totalQuantityPayment) + Number(totalProductFreePayment);

        $('#quantities').val(totalQuantityPayment);
        $('#productFrees').val(totalProductFreePayment);
        $('input[name="total_quantity"]').val(totalQuantity);
        $('input[name="total_amount"]').val(totalAmountPayment);

        amountQuantity = totalQuantityPayment;
        amountProductFree = totalProductFreePayment;
        totalAmount = totalAmountPayment;
        $('#sale_product_'+id).remove();
        $('#product_'+id).prop('checked', false);
        $('#product_'+id).prop('disabled', false);
        var saleId = $(this).attr('data-sale-id');
   
		element.push(saleId);
        $('#sale_ids').val(element);
        i--;
    });
    // update quantity
    function updateQuantity(data){
        let id = $(data).attr("data-id");
        let quantity = $('#quantity_'+id).val();
        let oldQuantity = $(data).attr("data-quantity");
        let rate = $('#rate_'+id).val();
        let oldAmount = $('#amount_'+id).val();
        // Payment
        let totalQuantityPayment = $('#quantities').val();
        let totalAmountPayment = $('input[name="total_amount"]').val();
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
        totalAmount = totalAmountPayment.toFixed(2);
    }
    // update Rate 
    function updateRate(data){
        let id = $(data).attr("data-id");
        let quantity = $('#quantity_'+id).val();
        let rate = $('#rate_'+id).val();
        let oldRate = $(data).attr("data-rate");
        let oldAmount = $('#amount_'+id).val();
        
        // Payment
        let totalAmountPayment = $('input[name="total_amount"]').val();
        let amount = Number(quantity) * Number(rate);
        totalAmountPayment = Number(totalAmountPayment) - Number(oldAmount);
        totalAmountPayment += Number(amount);
        // output value
        $('input[name="total_amount"]').val(totalAmountPayment.toFixed(2));
        $('#amount_'+id).val(amount.toFixed(2));
        $(data).attr('data-rate', rate);
        totalAmount = totalAmountPayment.toFixed(2);
    }
    // calculatorMoney
    function calculatorMoney(data) {
        let revicePrice = $(data)[0] ? $(data)[0].value : 0;
        let totalAmountPayment = $('input[name="total_amount"]').val();
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
        productFreePayment = (Number(productFreePayment) - Number(oldProductFree))
        productFreePayment += Number(productFree);
        let totalQuantityPayment = (Number(amountQuantity) + Number(productFreePayment))
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