<?php

namespace App\Http\Controllers\Backends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Customer;
use App\Models\Staff;
use App\Http\Constants\DeleteStatus;
use App\Models\Product;
use DB;

class ReportsController extends Controller
{
    public function __construct(
        Sale $sale,
        Product $product
    ) {
        $this->sale = $sale;
        $this->product = $product;
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
                        $customer->where('id', $customerName);
                    });
                }
            }
            if($request->staff_name != 'all') {
                if ($request->exists('staff_name') && !empty($request->staff_name)) {
                    $staffName = $request->staff_name;
                    $sales->whereHas('staff', function($staff) use ($staffName){
                        $staff->where('id', $staffName);
                    });
                }
            }
            $startOfDate = date('Y-m-d');
            $endOfDate =  date('Y-m-d');
            if ($request->exists('start_date') && !empty($request->start_date) && $request->exists('end_date') && !empty($request->end_date)) {
                $startOfDate = $request->start_date;
                $endOfDate =  $request->end_date;
            } 
            $sales->whereBetween(\DB::raw("DATE_FORMAT(sale_date,'%Y-%m-%d')"), [$startOfDate, $endOfDate]);
            // Check flash danger
            flashDanger($sales->count(), __('flash.empty_data'));
            $saleExecls = [];
            $sumTotalQuantity = $sales->count() > 0 ? $sales->sum('total_quantity') : 0;
            $sumTotalamount = $sales->count() > 0 ? $sales->sum('total_amount') : 0;
            if($request->exists('search_report') && !empty($request->search_report)) {
                $request->download_sale = 1;
            }
           
            if ($request->exists('download_sale') && !empty($request->download_sale) && $request->download_sale == 2) { 
                $saleExecls = $sales->get();
                $now = now();
                $headers = [
                    "Content-type" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                    "Content-Disposition" => "attachment; filename=$now-sale-report.xlsx"
                ];
                
                $columnSummary = mb_convert_encoding([
                    __('report.total_medicine').":{$sumTotalQuantity}",
                    __('report.total_currency'). ": {$sumTotalamount}"
                ],'UTF-8');
                $columns = mb_convert_encoding([
                    '#',
                    __('sale.list.invoice_code'),
                    __('sale.list.customer_name'),
                    __('sale.list.staff_name'),
                    __('sale.list.product_name'),
                    __('report.quantity'),
                    __('report.product_free'),
                    __('report.total_quantity'),
                    __('sale.list.amount'),
                    __('customer_owed.list.status_pay'),
                ],'UTF-8');
        
                $callback = function() use ($saleExecls, $columnSummary,  $columns)
                {
                    ob_end_clean();
                    $file = fopen('php://output', 'w');
                    
                    fputcsv($file, $columnSummary);
                    fputcsv($file, $columns);
                    foreach($saleExecls as $saleExecl) {
                        $quotaionNo = $saleExecl->quotaion_no .' '. date('h:i', strtotime($saleExecl->sale_date));
                        $productTitle = [];
                        $productQuantity = [];
                        $productFree = [];
                        foreach ($saleExecl->productSales as $key => $productSale){
                            $productTitle[] = ($key + 1).'.'.$productSale->product->title.'\n';
                            $productQuantity[] = $productSale->quantity;
                            $productFree[] = $productSale->product_free;
                        }
                        $strProductTitle = implode('', $productTitle);
                        $strProductQuantity = implode('', $productQuantity);
                        $strProductFree = implode('', $productFree);
                        $strProductAmount = implode('', $productAmount);
                        fputcsv(
                            $file,[
                                $saleExecl->id,
                                $quotaionNo,
                                $saleExecl->customer ? $saleExecl->customer->customerFullName() : '',
                                $saleExecl->staff ? $saleExecl->staff->getFullnameAttribute() : \Auth::user()->name,
                                $strProductTitle,
                                $strProductQuantity,
                                $strProductFree,
                                $saleExecl->total_quantity,
                                $saleExecl->total_amount,
                                $saleExecl->customerOwed()->exists() ? $saleExecl->customerOwed->statusPay()['statusText'] : 'មិនទាន់សង'
                            ]
                        );
                    }
                    fclose($file);
                };
                
                return \Response::stream($callback, 200, $headers);
            }
            $limit = config('pagination.limit');
            $sales = $sales->paginate($limit);
            // Get each product monthly sales
            $products = $this->product->join('sale_products', 'sale_products.product_id', '=', 'products.id')
                ->whereBetween(\DB::raw("DATE_FORMAT(sale_products.created_at,'%Y-%m-%d')"), [$startOfDate, $endOfDate])
                ->groupBy('sale_products.product_id')
                ->orderBy('products.title')
                ->get([
                    'products.id',
                    'products.title', 
                    DB::raw('sum(sale_products.quantity) as total_quantity'),
                    DB::raw('sum(sale_products.product_free) as total_product_free'),
                    DB::raw('sum(sale_products.amount) as total_amount')
                ]);

            return view('backends.reports.index', [
                'request' => $request,
                'sales' => $sales,
                'customers' => $customers,
                'staffs' =>  $staffs,
                'saleCount' => $sales->count(),
                'sumTotalQuantity' =>  $sumTotalQuantity,
                'sumTotalamount' => $sumTotalamount,
                'products' => $products
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.report.index');
        }
    }


}
