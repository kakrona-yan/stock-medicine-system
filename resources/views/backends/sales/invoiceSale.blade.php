<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice - #{{ $sale->quotaion_no }}</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <style type="text/css">
        /* @font-face {
			font-family: KhmerOSBattambang;
			src: url("{{ asset('fonts/KhmerOSBattambang-Regular.ttf') }}") format('truetype');
		} */
		@page {
			margin: 0cm 0cm;
		}
		*,
		*::before,
		*::after {
		-webkit-box-sizing: border-box;
				box-sizing: border-box;
			margin: 0px;
			padding: 0px;
		}
        html, body {
            margin: 0px;
            color: #333;
            text-align: left;
            line-height: 24px;
            font-family: Roboto, serif !important;
            font-size: 14px;
        }
        div,
        p{
            font-family: Roboto, serif !important;
        }
        a {
            color: #fff;
            text-decoration: none;
        }
        table {
            color: #333;
            border-collapse: collapse;
        }
        table th, table td{           
            color: #333;
        }
        tfoot tr td {
            color: #333;
        }
        .container{
            padding-left: 25px;
            padding-right: 25px;
            max-width: 794px;
            width: 100%;
            margin: 0px auto;
        }
        h1{
            font-size: 25px;
            margin: 5px 0px;
        }
        p {
            margin-top: 0;
            margin-bottom: 5px;
        }
        .table {
            width: 100%;
            margin-bottom: 5px;
        }
        .table-bordered {
            border: 1px solid #e3e6f0;
        }
        .table th, .table td {
            padding: 6px;
            vertical-align: top;
            border-top: 1px solid #e3e6f0;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #e3e6f0;
        }
        .text-right {
            text-align: right;
        }
        .table td.pd-left {
            padding-left: 0px;
        }
        .table td.pd-right {
            padding-left: 6px;
            padding-right: 0px ;
        }
        .mt-5{
            margin-top: 50px;
        }
        .company-info--name{
            margin-bottom: 10px;
        }
        .form-control{
            display: block;
            width: 100%;
            height: calc(2.15rem + 2px);
            padding: 12px 10px;
            line-height: 1.6;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
        }
    </style>

</head>
<body style="font-family: KhmerOSBattambang, sans-serif !important;">
    <div class="container mt-5">
        <div class="company-info mb-3">
            <div class="company-info--name">
                <h1>RRPS PHARMA CO., LTD</h1>
            </div>
            <div class="company-info--address">
                <p>Address : NO. 01, ST. 182, SANGKAT VIEL VONG, Khan 7  Makara, Phnom Penh</p>
                <p>Tel     : 093 399 330 </p>
            </div>
        </div>
        <table class="table">
            <tr>
                <td class="pd-left" style="width: 400px;">
                    <table class="table table-bordered">
                        <thead style="background:#eee">
                            <tr>
                                <th>Customer</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="height:98px;">
                                <p>
                                    {{ $sale->customer ? $sale->customer->name : '' }}
                                </p>
                                <p>
                                    {{ $sale->customer ? $sale->customer->phone1 : '' }}
                                </p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
                <td class="pd-right">
                    <table class="table table-bordered">
                        <thead style="background:#eee">
                            <tr style="text-align: center;">
                                <th>Date</th>
                                <th>Invoice code #</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="text-align: center;">
                                <td>{{ date('d/m/Y', strtotime($sale->sale_date))}}</td>
                                <td>{{ $sale->quotaion_no }}</td>
                            </tr>
                            <tr style="background:#eee; text-align: center;">
                                <th style="text-align: center;">Staff</th>
                                <th>Stock</th>
                            </tr>
                            <tr style="text-align: center;">
                                <td>{{$sale->staff ? $sale->staff->getFullnameAttribute() : \Auth::user()->name}}</td>
                                <td>RRPS PHARMA</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
        <div class="list-sale">
            <table class="table table-bordered">
            <thead style="background:#eee">
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>ProductFree</th>
                    <th>Rate</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($sale->productSales as $productSale)
                <tr>
                    <td style="width:340px;">{{$productSale->product ? $productSale->product->title : '' }}</td>
                    <td class="text-right">{{$productSale->quantity}}</td>
                    <td class="text-right">{{$productSale->product_free}}</td>
                    <td class="text-right">{{$productSale->rate}}</td>
                    <td class="text-right">{{$productSale->amount}}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right">Total</td>
                    <td class="text-right">USA {{money_format('%.2n', $sale->total_amount)}}</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right"><strong>Receive Product Amount :</strong></td>
                    <td class="text-right" style="background:#b9b9b9">
                        <input type="text" class="form-control" name="receive_money" accept="application/pdf">
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right"><strong>Pay Amount </strong></td>
                    <td class="text-right" style="background:#b9b9b9">
                        <input type="text" class="form-control" name="pay_amount" accept="application/pdf">
                    </td>
                </tr>
                
            </tfoot>
        </table>
        <div style="text-align: right; margin-top: 50px;">
            <p>Sign ..............................................</p>
        </div>
        </div>
    </div>
</body>
</html>