@extends('backends.layouts.master')
@section('title', 'Create Sale Product')
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
        <div class="col-12 tab-card">
            <!-- Circle Buttons -->
            <div class="card mb-4">
                <div id="supplierList" class="card-body collapse show">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div id="addsupplier" class="tab-pane active">
                            <form class="form-main" action="{{route('sale.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group w-50 d-inline-flex">
                                    <button type="submit" class="btn btn-circle btn-primary w-25 mr-2">Update Sale</button>
                                    <a href="{{route('sale.index')}}" class="btn btn-circle btn-secondary w-25">Back to sale list</a>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-12 col-md-7 mb-3">
                                        <fieldset class="edit-master-registration-fieldset">
                                            <legend class="edit-application-information-legend text-left">Sale:</legend>
                                            <div class="form-group select-group row mb-4">
                                                <label class="col-12 col-md-3 col-form-label" for="invoiceCode">Category</label>
                                                <div class="col-9">
                                                    <select class="form-control" id="category_id" name="category_id">
                                                        <option value="">Please select</option>
                                                        @foreach($categories as $id => $name)
                                                            <option value="{{ $id }}" {{ $id == $sale->category_id ? 'selected' : '' }}>{{ $name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-12 col-md-3 col-form-label" for="invoiceCode">Invoice Code</label>
                                                <div class="col-9">
                                                    <input type="text" class="form-control" id="invoiceCode" name="invoiceCode" readonly="">
                                                </div>
                                            </div>
                                            <div class="form-group select-group row">
                                                <label class="col-12 col-md-3 col-form-label" for="invoiceCode">Customer <span class="text-danger">*</span></label>
                                                <div class="col-9">
                                                    <select class="form-control" id="customer_id" name="customer_id">
                                                        <option value="">Please select</option>
                                                        @foreach($customers as $id => $name)
                                                            <option value="{{ $id }}" {{ $id == $sale->customer_id ? 'selected' : '' }}>{{ $name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group select-group row">
                                                <label class="col-12 col-md-3 col-form-label" for="invoiceCode">Date Sale <span class="text-danger">*</span></label>
                                                <div class="col-9">
                                                    <div class="input-group date" data-provide="datepicker" data-date-format="YYYY-MM-MM">
                                                        <input type="text" class="form-control" name="sale_date"
                                                            value="{{ old('dob', date('Y-m-d')) }}">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><span class="far fa-calendar-alt"></span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-12 col-md-5 mb-3">
                                        <fieldset class="edit-master-registration-fieldset">
                                            <legend class="edit-application-information-legend text-left">Product List:</legend>
                                            <div class="form-group select-group list-product">
                                                <div id="list_product"></div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-5 mb-3">
                                        <fieldset class="edit-master-registration-fieldset">
                                            <legend class="edit-application-information-legend text-left">Payment:</legend>
                                            <div class="form-group row">
                                                <label class="col-12 col-md-3 col-form-label" for="total_quantity">Total Quantity</label>
                                                <div class="col-9">
                                                    <input type="text" class="form-control" id="total_quantity" name="total_quantity" readonly="" value="{{ old('total_quantity', $sale->total_quantity ? $sale->total_quantity : 0) }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-12 col-md-3 col-form-label" for="total_amount">Total Amount</label>
                                                <div class="col-9">
                                                    <input type="text" class="form-control" id="total_amount" name="total_amount" readonly="" value="{{ old('total_amount', $sale->total_amount ? $sale->total_amount : 0) }}">
                                                </div>
                                            </div>
                                             <div class="form-group row">
                                                <label class="col-12 col-md-3 col-form-label" for="total_discount">Dicount</label>
                                                <div class="col-9">
                                                    <input type="text" class="form-control" id="total_discount" name="total_discount" value="{{ old('dototal_discountb', $sale->total_discount ? $sale->total_discount : 0) }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-12 col-md-3 col-form-label" for="total_money_revice">Received Amount<span class="text-danger">*</span></label>
                                                <div class="col-9">
                                                    <input type="text" class="form-control" id="total_money_revice" name="money_change" value="{{ old('money_change', $sale->money_change ? $sale->money_change : 0) }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-12 col-md-3 col-form-label" for="money_owed">Owed</label>
                                                <div class="col-9">
                                                    <input type="text" class="form-control" id="money_owed" readonly="" value="0">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-12 col-md-3 col-form-label" for="money_owed">Note</label>
                                                <div class="col-9">
                                                    <textarea class="form-control" name="note">{{ old('dob', $sale->note) }}</textarea>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-12 col-md-7 mb-3">
                                        <fieldset class="edit-master-registration-fieldset">
                                            <legend class="edit-application-information-legend text-left">Sale Product:</legend>
                                            <div class="table-responsive cus-table">
                                            <table class="table table-striped table-bordered">
                                                <thead class="bg-primary text-light">
                                                    <tr>
                                                        <th>#No</th>
                                                        <th>Product Name</th>
                                                        <th>Quantity</th>
                                                        <th>Unit Price</th>
                                                        <th>Total</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </fieldset>
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
        $("#category_id").select2({
            allowClear: false
        }).on('select2:select', function (e) {
            let routeUrl = "{{config('app.url')}}/admin/sales/product";
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
        $('#customer_id').select2({
            allowClear: false
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
            html +=`<input type="checkbox" id="product_${productOrder.id}" />`;
            html +=`<label for="product_${productOrder.id}">`;
            html +=`<img src="${img}" />`;
            html +=`<div class="py-1 text-center">${productOrder.title.slice(0, 10)+'...'}</div>`;
            html +=`<div class="py-1 text-center text-danger">${productOrder.price}$</div>`;
            html +='</label>';
            html +='</li>';
        }
        html +='</ul></div>';
        return html;
    }
</script>
@endpush