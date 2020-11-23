<?php

namespace App\Http\Controllers\Backends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Customer;
use App\Models\Staff;
use App\Http\Constants\DeleteStatus;

class ReportsController extends Controller
{
    public function __construct(Sale $sale) {
        $this->sale = $sale;
    }

    public function index(Request $request)
    {
        try {

            $customers = Customer::ofList();
            $staffs = Staff::ofList();
            $sales = $this->sale->where('is_delete', '<>', DeleteStatus::DELETED)->orderBy('id', 'DESC');

            if($request->customer_name != 'all') {
                if ($request->exists('customer_name') && !empty($request->customer_name)) {
                    $customerName = $request->customer_name;
                    $sales->whereHas('customer', function($customer) use ($customerName){
                        $customer->where('name', 'like', '%' . $customerName . '%');
                    });
                }
            }
            if($request->staff_name != 'all') {
                if ($request->exists('staff_name') && !empty($request->staff_name)) {
                    $staffName = $request->staff_name;
                    $sales->whereHas('staff', function($staff) use ($staffName){
                        $staff->where('name', 'like', '%' . $staffName . '%');
                    });
                }
            }
            
            if ($request->exists('start_date') && !empty($request->start_date) && 
                $request->exists('end_date') && !empty($request->end_date)) 
            {
                $sales->whereBetween('sale_date', [date('Y-m-d', strtotime($request->start_date)), date('Y-m-d', strtotime($request->end_date))]);
            } else {
                $sales->whereBetween('sale_date', [date('Y-m-d', strtotime(date('Y-m-d'))), date('Y-m-d', strtotime(date('Y-m-d')))]);
            }
            // Check flash danger
            flashDanger($sales->count(), __('flash.empty_data'));
            $saleExecls = [];
            if ($request->exists('download_sale') && !empty($request->download_sale)) {
                $saleExecls = $sales->get();
                $this->downloadSale($saleExecls);
            }
            $limit = config('pagination.limit');
            $sales = $sales->paginate($limit);

            return view('backends.reports.index', [
                'request' => $request,
                'sales' => $sales,
                'customers' => $customers,
                'staffs' =>  $staffs,
                'saleCount' => $sales->count()
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.report.index');
        }
    }

    private function downloadSale($saleExecls)
    {
        // $columns = mb_convert_encoding([
        //                 '注文ID',
        //                 'ユーザーID',
        //                 '代理店',
        //                 '店舗',
        //                 'スタッフ名',
        //                 '決済タイプ',
        //                 '商品名',
        //                 '購入確定日',
        //                 '利用停止日',
        //                 '販売金額',
        //                 '手数料'
        //             ],"Shift-JIS");
        
        // $callback = function() use ($saleReports, $columns)
        // {
        //     ob_end_clean();
        //     $file = fopen('php://output', 'w');
        //     fputcsv($file, $columns);
        //     foreach($saleReports as $saleReport) {
        //         $customID = $saleReport->customer ? $saleReport->customer->id : '';
        //         $storeName = $saleReport->staff ? $saleReport->store->name : '';
        //         $staffName = $saleReport->staff ? $saleReport->staff->name : '';
        //         $orderType = $saleReport->paymentType() ? $saleReport->paymentType() : '';
        //         $productName = $saleReport->productName() ? $saleReport->productName() : '';
        //         $price = $saleReport->agencyProduct ? $saleReport->agencyProduct->sell_price : '';
        //         $fee = $saleReport->agencyFee() ? $saleReport->agencyFee() : '';
        //         fputcsv($file, [
        //                 $saleReport->id,
        //                 $customID,
        //                 mb_convert_encoding($saleReport->agencyName(), "Shift-JIS"),
        //                 mb_convert_encoding($storeName, "Shift-JIS"),
        //                 mb_convert_encoding($staffName, "Shift-JIS"),
        //                 $orderType,
        //                 mb_convert_encoding($productName, "Shift-JIS"),
        //                 $saleReport->createdAt(),
        //                 $saleReport->canceledAt(),
        //                 $price,
        //                 $fee,
        //             ]
        //         );
        //     }
        //     fclose($file);
        // };
        // return Response::stream($callback, 200, $headers);
    }
}
