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
use App\Models\Staff;
use App\Http\Constants\DeleteStatus;

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
            $products = $this->product->getProductName();
            $customers = $this->customer->getCustomer();
            $invoiceCode =  $this->sale->incrementStringUniqueInvoiceCode();
            $staffs = Staff::select(['id', 'name'])->get();
            return view('backends.sales.create', [
                'request' => $request,
                'products' => $products,
                'customers' => $customers,
                'invoiceCode' => $invoiceCode,
                'staffs' => $staffs
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
            ];
            // Set field of Validattion
            $validator = \Validator::make([
                'customer_id' => $request->customer_id,
                'sale_date' => $request->sale_date,
            ], $rules);
            if ($validator->fails()) {
                return response()->json(["errors" => $validator->errors()], 422);
            } else {
                // insert to table sales
                if(\Auth::user()->isRoleAdmin() || \Auth::user()->isRoleEditor() || \Auth::user()->isRoleView()) {
                    $staff = $request->staff_id ? $request->staff_id : \Auth::id() ;
                }else{
                    $staff = \Auth::user()->staff ? \Auth::user()->staff->id : \Auth::id();
                }

                $requestSale = [];
                if ($request->exists('sale_product') && !empty($request->sale_product)) {
                    $requestSale['staff_id'] = $staff;
                    $requestSale['customer_id'] = $request->customer_id;
                    $requestSale['quotaion_no'] = $request->quotaion_no;
                    $requestSale['money_change'] = $request->money_change;
                    $requestSale['total_quantity'] = $request->total_quantity;
                    $requestSale['total_discount'] = $request->total_discount;
                    $requestSale['total_amount'] = $request->total_amount;
                    $saleDate = $request->sale_date . ' ' . date('h:i:s');
                    $requestSale['sale_date'] = date('Y-m-d h:i:s', strtotime($saleDate));
                    $requestSale['note'] = $request->note;
                    $sale = $this->sale->create($requestSale);
                    if($sale) {
                        $sale->update([
                            "quotaion_no" => date('d/m') ."/C".$sale->id
                        ]);
                    }
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
                    return responseSuccess(
                        \Session::flash('success', __('flash.store')),
                            ['danger', 'danger']
                        );
                } else {
                    return responseSuccess(
                        \Session::flash('danger', __('flash.empty_data')),
                        ['success', 'danger']
                    );
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
                ->where('id', $request->product_id)
                ->select(['id', 'category_id', 'title', 'price', 'in_store', 'thumbnail'])
                ->orderBy('title', 'ASC')
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
            $pdfName = "{$sale->customer->customerFullName()}-{$sale->quotaion_no}-{$dateSale}" . ".pdf";
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
            $pdfName = "{$sale->customer->customerFullName()}-{$sale->quotaion_no}-{$dateSale}" . ".pdf";
            $view = view('backends.sales.invoiceSale', ['sale' => $sale]);
            $html = mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');

            $pdfSale = PDF::loadHTML($html)
                ->setPaper('a4')
                ->setWarnings(false)
                ->setOptions(['isFontSubsettingEnabled' => true]);
            return $pdfSale->stream($pdfName)->header('Content-Type','application/pdf');
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.sales.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, int $id)
    {
        try {
            $sale = $this->sale->available($id);
            if (!$sale) {
                return response()->view('errors.404', [], 404);
            }

            $products = $this->product->getProductName();
            $customers = $this->customer->getCustomer();
            $staffs = Staff::select(['id', 'name'])
                ->get();

            return view('backends.sales.edit', [
                'sale' => $sale,
                'request' => $request,
                'products' => $products,
                'customers' => $customers,
                'staffs' => $staffs
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.products.edit');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            // Rules of field
            $rules = [
                'customer_id' => 'required',
                'sale_date' => 'required',
            ];
            // Set field of Validattion
            $validator = \Validator::make([
                'customer_id' => $request->customer_id,
                'sale_date' => $request->sale_date,
            ], $rules);
            if ($validator->fails()) {
                return response()->json(["errors" => $validator->errors()], 422);;
            } else {
                $sale = $this->sale->available($id);
                if (!$sale) {
                    return response()->view('errors.404', [], 404);
                }
                // insert to table sales
                if(\Auth::user()->isRoleAdmin() || \Auth::user()->isRoleEditor() || \Auth::user()->isRoleView()) {
                    $staff = $request->staff_id ? $request->staff_id : \Auth::id() ;
                }else{
                    $staff = \Auth::user()->staff ? \Auth::user()->staff->id : \Auth::id();
                }

                $requestSale = [];
                if ($request->exists('sale_product') && !empty($request->sale_product)) {
                    $requestSale['staff_id'] = $staff;
                    $requestSale['customer_id'] = $request->customer_id;
                    $requestSale['quotaion_no'] = $request->quotaion_no;
                    $requestSale['money_change'] = $request->money_change;
                    $requestSale['total_quantity'] = $request->total_quantity;
                    $requestSale['total_discount'] = $request->total_discount;
                    $requestSale['total_amount'] = $request->total_amount;
                    $saleDate = $request->sale_date.' '. date('h:i:s');
                    $requestSale['sale_date'] = date('Y-m-d h:i:s', strtotime($saleDate));
                    $requestSale['note'] = $request->note;
                    $sale->update($requestSale);
                    // insert to table salesProduct
                    if($sale) {
                        $saleProducts = [];
                        $saleProducts = $request->sale_product;
                        foreach ($saleProducts as $key => $saleProduct) {
                            if (isset($saleProduct['sale_id']) && $saleProduct['sale_id'] != null) {
                                $findProductSales = $this->saleProduct
                                    ->where('id', $saleProduct['sale_id'])
                                    ->where('sale_id', $sale->id)
                                    ->where('product_id', $saleProduct['product_id'])
                                    ->first();
                                if ($findProductSales) {
                                    $findProductSales->update([
                                        'sale_id'       => $sale->id,
                                        'product_id'    => $saleProduct['product_id'],
                                        'rate'          => $saleProduct['rate'],
                                        'quantity'      => $saleProduct['quantity'],
                                        'product_free'  => $saleProduct['product_free'],
                                        'amount'        => $saleProduct['amount']
                                    ]);
                                } else {
                                    $this->saleProduct->create([
                                        'sale_id'       => $sale->id,
                                        'product_id'    => $saleProduct['product_id'],
                                        'rate'          => $saleProduct['rate'],
                                        'quantity'      => $saleProduct['quantity'],
                                        'product_free'  => $saleProduct['product_free'],
                                        'amount'        => $saleProduct['amount']
                                    ]);
                                }
                            } else {
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
                        // update and delete table
                        if (isset($request->sale_ids)) {
                            $saleIds = preg_split("/,/", $request->sale_ids);
                            foreach ($saleIds as $key => $saleId) {
                                $saleProductFind = $this->saleProduct->where('sale_id', $sale->id)
                                    ->where('id', $saleId)
                                    ->first();
                                if ($saleProductFind) {
                                    $saleProductFind->delete();
                                }
                            }
                        }
                    }
                    return responseSuccess(
                        \Session::flash('warning', __('flash.update')),
                            ['danger', 'danger']
                        );
                } else {
                    return responseSuccess(
                        \Session::flash('danger', __('flash.empty_data')),
                        ['warning', 'danger']
                    );
                }
            }
        } catch (\ValidationException $e) {
            return exceptionError($e, 'customers.create');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            $id = $request->sale_id;
            $sale = $this->sale->available($id);
            if (!$sale) {
                return response()->view('errors.404', [], 404);
            }
            $saleProduct = $this->saleProduct->where('sale_id', $id)
                            ->first();
            if ($saleProduct) {
                $saleProduct->delete();
            }
            $sale->remove();
            return redirect()->route('sale.index')
                ->with('danger', __('flash.destroy'));
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.sale.index');
        }
    }
}
