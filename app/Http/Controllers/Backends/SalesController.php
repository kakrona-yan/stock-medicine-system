<?php

namespace App\Http\Controllers\Backends;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleProduct;
use Barryvdh\DomPDF\Facade as PDF;
use Dompdf\Dompdf;

class SalesController extends Controller
{
    public function __construct(
        Category $category,
        Product $product,
        Customer $customer,
        Sale $sale,
        SaleProduct $saleProduct
    ) {
        $this->category = $category;
        $this->product = $product;
        $this->customer = $customer;
        $this->sale = $sale;
        $this->saleProduct = $saleProduct;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $sales  = $this->sale->filter($request);
            return view('backends.sales.index', [
                'request' => $request,
                'sales' => $sales
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.sales.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $categories = $this->category->getCategoryNameByProducts();
            $customers = $this->customer->getCustomer();
            $invoiceCode =  $this->sale->incrementStringUniqueInvoiceCode();
            return view('backends.sales.create', [
                'request' => $request,
                'categories' => $categories,
                'customers' => $customers,
                'invoiceCode' => $invoiceCode
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.sales.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // Rules of field
            $rules = [
                'customer_id' => 'required',
                'sale_date' => 'required',
                'money_change' => 'required',
            ];
            // Set field of Validattion
            $validator = \Validator::make([
                'customer_id' => $request->customer_id,
                'sale_date' => $request->sale_date,
                'money_change' => $request->money_change,
            ], $rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                // insert to table sales
                $staff = \Auth::user()->staff;
                $requestSale = [];
                if ($request->exists('sale_product') && !empty($request->sale_product)) {
                    $requestSale['staff_id'] = $staff ? $staff->id : \Auth::id();
                    $requestSale['customer_id'] = $request->customer_id;
                    $requestSale['quotaion_no'] = $request->quotaion_no;
                    $requestSale['money_change'] = $request->money_change;
                    $requestSale['total_quantity'] = $request->total_quantity;
                    $requestSale['total_discount'] = $request->total_discount;
                    $requestSale['total_amount'] = $request->total_amount;
                    $requestSale['sale_date'] = date('Y-m-d', strtotime($request->sale_date));
                    $requestSale['note'] = $request->note;
                    $sale = $this->sale->create($requestSale);
                    // insert to table salesProduct
                    if($sale) {
                        $saleProducts = [];
                        $saleProducts = $request->sale_product;
                        foreach ($saleProducts as $key => $saleProduct) {
                            $this->saleProduct->create([
                                'sale_id'       => $sale->id,
                                'product_id'    => $saleProduct['product_id'],
                                'rate'          => $saleProduct['rate'],
                                'quantity'      => $saleProduct['quantity'],
                                'product_free'  => $saleProduct['product_free'],
                                'amount'        => $saleProduct['amount']
                            ]);
                        }
                    }
                    return \Redirect::route('sale.index')
                        ->with('success', __('flash.store'));
                } else {
                    return \Redirect::route('sale.create')
                    ->with('danger', __('flash.empty_data'));
                }
            }
        } catch (\ValidationException $e) {
            return exceptionError($e, 'customers.create');
        }
    }

    public function getProductByCategory(Request $request)
    {
        $productOrders = [];
        try {
            $productOrders = $this->product->where('is_delete', '<>', 0)
                ->where('is_active', 1) // is_delete = 1 and is_active = 1
                ->where('category_id', $request->category_id)
                ->select(['id', 'category_id', 'title', 'price', 'in_store', 'thumbnail'])
                ->orderBy('id', 'DESC')
                ->get();
            return response()
                ->json([
                    'productOrders' => $productOrders,
                    'code' => 200,
                    'status'  => 'success'
                ]);
        } catch (\Exception $e) {
            return response()
                ->json([
                    'status'  => 'fail',
                    'message' => $e->getMessage()
                ]);
        }
    }

    function downloadInvoiceSalePDF(int $id) 
    {
       
        try {
            $sale = $this->sale->available($id);
            if (!$sale) {
                return response()->view('errors.404', [], 404);
            }
            return view('backends.sales.invoiceSale', [
                'sale' => $sale,
            ]);
            $dateSale = date('Y-m-d', strtotime($sale->sale_date));
            $pdfName = "{$sale->customer->name}-{$sale->quotaion_no}-{$dateSale}" . ".pdf";
            $pdfSale = PDF::loadView('backends.sales.invoiceSale', ['sale' => $sale]);
            $pdfSale->setPaper('a4');
            return $pdfSale->download($pdfName);

        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.sales.index');
        }
    }
    function viewInvoiceSalePDF(int $id)
    {

        try {
            $sale = $this->sale->available($id);
            if (!$sale) {
                return response()->view('errors.404', [], 404);
            }
            $dateSale = date('Y-m-d', strtotime($sale->sale_date));
            $pdfName = "{$sale->customer->name}-{$sale->quotaion_no}-{$dateSale}" . ".pdf";

            // $pdfSale = PDF::loadView('backends.sales.invoiceSale', ['sale' => $sale]);
            // $pdfSale->setPaper('a4');
            // Send data to the view using loadView function of PDF facade
            $view = view('backends.sales.invoiceSale', ['items' => $data]);
            $html = mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');
            $html_decode = html_entity_decode($html);
            $pdfSale = new Dompdf();
            $pdfSale->loadHtml($html_decode)
                ->setPaper('a4')
                ->render();;
            return $pdfSale->stream($pdfName);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.sales.index');
        }
    }
}
