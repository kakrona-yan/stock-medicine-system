<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice - #{{ $sale->quotaion_no }}</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <style type="text/css">
        @font-face {
			font-family: 'KhmerOSBattambang';
            font-style: normal;
            font-weight: normal;
			src: url("{{ asset('fonts/KhmerOSBattambang-Regular.ttf') }}") format('truetype');
		}
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
            font-family: 'KhmerOSBattambang', 'Roboto', sans-serif;
            font-size: 14px;
            font-weight: normal;
            position: relative;
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
            font-weight: normal;
        }
        .container{
            padding-left: 25px;
            padding-right: 25px;
            max-width: 794px;
            width: 100%;
            margin: 0px auto;
            position: relative;
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
        .table th, .table td {
            padding: 6px;
            vertical-align: top;
            border-top: 1px solid #e3e6f0;
            font-weight: normal;
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
        .d-inline{
            display: inline-block;
            width: 320px;
            margin: 0px 20px;
        }
        .btn-print{
            width: 100px;
            height: 100px;
            background: #ffffff;
            border-radius: 50px;
            color: #0dc6d3;
            position: absolute;
            right: 30px;
            z-index: 99;
            top: -20px;
            font-size: 20px;
            cursor: pointer;
        }
        @media print {
            .btn-print {
                display: none;
            }
            .con-print {
                display: block;
            }
        }
    </style>
    <script>
        function printInvoice() {
          window.print();
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <button type="button" class="btn-print" id="btn-print" onclick="printInvoice()">Print</button>
        <div class="con-print">
            <div class="company-info mb-3">
                <div class="company-info--name">
                    <h1>RRPS PHARMA CO., LTD</h1>
                </div>
                <div class="company-info--address">
                    <p>អាស័យដ្ឋាន : NO. 01, ST. 182, សង្កាត់ វាលវង់, ខណ្ឌ ៧មករា, រាជធានីភ្នំពេញ</p>
                    <p>ទូរស័ព្ទ     : 093 399 330 </p>
                </div>
            </div>
            <table class="table">
                <tr>
                    <td class="pd-left" style="width: 400px;">
                        <table class="table table-bordered">
                            <thead style="background:#eee">
                                <tr>
                                    <th>អតិថិជន</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td style="height:135px;">
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
                                    <th>កាលបរិច្ឆេទ</th>
                                    <th>លេខកូដវិក្កយបត្រ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="text-align: center;">
                                    <td>{{ date('d/m/Y', strtotime($sale->sale_date))}}</td>
                                    <td>{{ $sale->quotaion_no }}</td>
                                </tr>
                                <tr style="background:#eee; text-align: center;">
                                    <th style="text-align: center;">លក់ដោយ</th>
                                    <th>ស្តុក</th>
                                </tr>
                                <tr style="text-align: center;">
                                    <td>
                                        {{$sale->staff ? $sale->staff->getFullnameAttribute() : \Auth::user()->name}}<br/>
                                        {{$sale->staff->phone1}} {{$sale->staff->phone2 ? '/'.$sale->staff->phone1 : ''}}
                                    </td>
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
                        <th>ផលិតផល</th>
                        <th>បរិមាណ</th>
                        <th>ចំនួនថែម</th>
                        <th>តម្លែ(ឯកតា)</th>
                        <th class="text-right">ចំនួនទឹកប្រាក់</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($sale->productSales as $productSale)
                    <tr>
                        <td style="width:340px;">{{$productSale->product ? $productSale->product->title : '' }}</td>
                        <td class="text-right">{{$productSale->quantity}}</td>
                        <td class="text-right">{{$productSale->product_free}}</td>
                        <td class="text-right">{{$productSale->rate}}</td>
                        <td class="text-right" style="width: 219px;">{{$productSale->amount}}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right">សរុប</td>
                        <td class="text-right">
                            USD {{$sale->total_amount}}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right" style="border: none;"><strong>ទទួលបានចំនួនផលិតផល:</strong></td>
                        <td class="text-right">
                            <input type="text" class="form-control" name="receive_money" accept="application/pdf">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right" style="border: none;"><strong>ចំនួនទឹកប្រាក់ទូទាត់:</strong></td>
                        <td class="text-right">
                            <input type="text" class="form-control" name="pay_amount" accept="application/pdf">
                        </td>
                    </tr>
                    
                </tfoot>
            </table>
            <div style="text-align: center; margin-top: 50px;width:100%;">
                <div class="d-inline">
                    <p>ត្រូតពិនិត្យដោយ</p>
                    <p style="margin-top: 50px;border-bottom: 1px solid #000;"></p>
                </div>
                <div class="d-inline">
                    <p>ហត្ថលេខាអតិថិជន</p>
                    <p style="margin-top: 50px;border-bottom: 1px solid #000;"></p>
                </div>
            </div>
            </div>
        </div>
    </div>
</body>
</html>