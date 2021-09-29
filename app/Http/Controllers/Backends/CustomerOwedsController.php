<?php

namespace App\Http\Controllers\Backends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerOwed;
use App\Models\Sale;
use App\Http\Constants\DeleteStatus;
use App\Models\CustomerOwedHistory;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomerOwedsController extends Controller
{
    const STATUS_NOT_PAY = 0;
    const STATUS_SamePay_PAY = 1;
    const STATUS_ALL_PAY = 2;

    public function __construct(
        Customer $customer,
        CustomerOwed $customerOwed,
        Sale $sale
    ) {
        $this->customer = $customer;
        $this->customerOwed = $customerOwed;
        $this->sale = $sale;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $notPay = self::STATUS_NOT_PAY;
            $SamePayPay = self::STATUS_SamePay_PAY;
            $allPay = self::STATUS_ALL_PAY;

            // customer of sale
            $sales = $this->sale->where('is_delete', '<>', 0)->with('customerOwed');

            if ($request->exists('quotaion_no') && !empty($request->quotaion_no)) {
                $quotationNo = $request->quotaion_no;
                $sales = $sales->where('quotaion_no', 'like', '%' . $quotationNo . '%');
            }
            if ($request->exists('customer_name') && !empty($request->customer_name)) {
                $customerName = $request->customer_name;
                $sales->whereHas('customer', function ($customer) use ($customerName) {
                    $customer->where('name', 'like', '%' . $customerName . '%');
                });
            }
            if ($request->exists('staff_name') && !empty($request->staff_name)) {
                $staffName = $request->staff_name;
                $sales->whereHas('staff', function ($staff) use ($staffName) {
                    $staff->where('name', 'like', '%' . $staffName . '%');
                });
            }
            if ($request->exists('orderby') && !empty($request->orderby)) {
                $orderType = strtolower($request->order) == 'desc' ? 'desc' : 'asc';
                switch ($request->orderby) {
                    case 'id':
                   case 'created_at':
                            $sales = $sales->orderBy($request->orderby, $orderType);
                        break;
                }
            } else {
                $sales = $sales->orderBy('id', 'DESC');
            }
            // Check flash danger
            flashDanger($sales->count(), __('flash.empty_data'));
            $sales = $sales->paginate(30, ['*'], 'sale-page');

            $statusPays = CustomerOwed::STATUS_PAY_TEXT_FORM;
            return view('backends.customer_oweds.index', [
                'request' => $request,
                'sales' => $sales,
                'statusPays' => $statusPays
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'customer_owed.index');
        }
    }

    public function somPay(Request $request)
    {
        try {
            $notPay = self::STATUS_NOT_PAY;
            $SamePayPay = self::STATUS_SamePay_PAY;
            $allPay = self::STATUS_ALL_PAY;

            // customer of sale
            $saleSomePays = $this->sale->where('is_delete', '<>', 0)
                ->with('customerOwed')
                ->whereHas('customerOwed', function ($customerOwed) use ($SamePayPay) {
                    $customerOwed->where('status_pay',  $SamePayPay);
                });

            if ($request->exists('quotaion_no') && !empty($request->quotaion_no)) {
                $quotationNo = $request->quotaion_no;
                $saleSomePays = $saleSomePays->where('quotaion_no', 'like', '%' . $quotationNo . '%');
            }
            if ($request->exists('customer_name') && !empty($request->customer_name)) {
                $customerName = $request->customer_name;
                $saleSomePays->whereHas('customer', function ($customer) use ($customerName) {
                    $customer->where('name', 'like', '%' . $customerName . '%');
                });
            }
            if ($request->exists('staff_name') && !empty($request->staff_name)) {
                $staffName = $request->staff_name;
                $saleSomePays->whereHas('staff', function ($staff) use ($staffName) {
                    $staff->where('name', 'like', '%' . $staffName . '%');
                });
            }
            if ($request->exists('status_pay') && !empty($request->status_pay)) {
                $status_pay = $request->status_pay;
                $saleSomePays->whereHas('customerOwed', function ($customerOwed) use ($status_pay) {
                    $customerOwed->where('status_pay',  $status_pay);
                });
            }
            if ($request->exists('orderby') && !empty($request->orderby)) {
                $orderType = strtolower($request->order) == 'desc' ? 'desc' : 'asc';
                switch ($request->orderby) {
                    case 'id':
                    case 'created_at':
                            $saleSomePays = $saleSomePays->orderBy($request->orderby, $orderType);
                        break;
                }
            } else {
                $saleSomePays = $saleSomePays->orderBy('id', 'DESC');
            }
            // Check flash danger
            flashDanger($saleSomePays->count(), __('flash.empty_data'));
            $saleSomePays = $saleSomePays->get()->sortByDesc('customerOwed.receive_date');
            $saleSomePays = $this->paginateArrayToCllect($saleSomePays, $request, 40);
            $statusPays = CustomerOwed::STATUS_PAY_TEXT_FORM;
            return view('backends.customer_oweds.index_some_pay', [
                'request' => $request,
                'saleSomePays' => $saleSomePays,
                'statusPays' => $statusPays
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'customer_owed.index');
        }
    }

    public function allPay(Request $request)
    {
        try {
            $notPay = self::STATUS_NOT_PAY;
            $SamePayPay = self::STATUS_SamePay_PAY;
            $allPay = self::STATUS_ALL_PAY;

            // customer of sale
            $saleAllPays = $this->sale->where('is_delete', '<>', 0)
                ->with('customerOwed')
                ->whereHas('customerOwed', function ($customerOwed) use ($allPay) {
                    $customerOwed->whereIn('status_pay',  [2, 3]);
                });

            if ($request->exists('quotaion_no') && !empty($request->quotaion_no)) {
                $quotationNo = $request->quotaion_no;
                $saleAllPays = $saleAllPays->where('quotaion_no', 'like', '%' . $quotationNo . '%');
            }
            if ($request->exists('customer_name') && !empty($request->customer_name)) {
                $customerName = $request->customer_name;
                $saleAllPays->whereHas('customer', function ($customer) use ($customerName) {
                    $customer->where('name', 'like', '%' . $customerName . '%');
                });
            }
            if ($request->exists('staff_name') && !empty($request->staff_name)) {
                $staffName = $request->staff_name;
                $saleAllPays->whereHas('staff', function ($staff) use ($staffName) {
                    $staff->where('name', 'like', '%' . $staffName . '%');
                });
            }
            if ($request->exists('status_pay') && !empty($request->status_pay)) {
                $status_pay = $request->status_pay;
                $saleAllPays->whereHas('customerOwed', function ($customerOwed) use ($status_pay) {
                    $customerOwed->where('status_pay',  $status_pay);
                });
            }
            if ($request->exists('orderby') && !empty($request->orderby)) {
                $orderType = strtolower($request->order) == 'desc' ? 'desc' : 'asc';
                switch ($request->orderby) {
                    case 'id':
                    case 'created_at':
                            $saleAllPays = $saleAllPays->orderBy($request->orderby, $orderType);
                        break;
                }
            } else {
                $saleAllPays = $saleAllPays->orderBy('id', 'DESC');
            }
            // Check flash danger
            flashDanger($saleAllPays->count(), __('flash.empty_data'));
            $saleAllPays = $saleAllPays->get()->sortByDesc('customerOwed.receive_date');
            $saleAllPays = $this->paginateArrayToCllect($saleAllPays, $request, 40);
            $statusPays = CustomerOwed::STATUS_PAY_TEXT_FORM;
            return view('backends.customer_oweds.index_all_pay', [
                'request' => $request,
                'saleAllPays' => $saleAllPays,
                'statusPays' => $statusPays
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'customer_owed.index');
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
            $statusPays = CustomerOwed::STATUS_PAY_TEXT_FORM;
            $discountType = CustomerOwed::DICOUNT_TYPE_TEXT;
            return view('backends.customer_oweds.edit', [
                'sale' => $sale,
                'request' => $request,
                'statusPays' => $statusPays,
                'discountType' => $discountType,
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.products.edit');
        }
    }

    public function editPayAll(Request $request, int $id)
    {
        try {
            $sale = $this->sale->available($id);
            if (!$sale) {
                return response()->view('errors.404', [], 404);
            }
            $statusPays = CustomerOwed::STATUS_PAY_ALL_TEXT;
            return view('backends.customer_oweds.edit', [
                'sale' => $sale,
                'request' => $request,
                'statusPays' => $statusPays
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
                'sale_id' => 'required',
                'customer_id' => 'required',
                'amount' => 'required',
                'receive_date' => 'required'
            ];
            // Set field of Validattion
            $validator = \Validator::make([
                'sale_id' => $request->sale_id,
                'customer_id' => $request->customer_id,
                'amount' => $request->amount,
                'receive_date' => $request->receive_date
            ], $rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $saleRequest = $request->all();
                $sale = $this->sale->available($id);
                $sale->update([
                    'money_change' => $saleRequest['receive_amount']
                ]);
                if ($sale) {
                    $customerOwed = $this->customerOwed->where('sale_id', $saleRequest["sale_id"])
                        ->where('customer_id', $saleRequest["customer_id"])
                        ->first();
                    if ($customerOwed) {
                        $customerOwed->update($saleRequest);
                        // store customer owed history
                        $customerOwedHistory = CustomerOwedHistory::where('customer_owed_id', $customerOwed->id)
                            ->first();
                        if ($customerOwedHistory) {
                            $customerOwedHistory->update([
                                'customer_owed_id' => $customerOwed->id,
                                'receive_amount' => $request->amount_pay,
                                'recipient' => \Auth::user()->name
                            ]);
                        } else {
                            CustomerOwedHistory::create([
                                'customer_owed_id' => $customerOwed->id,
                                'receive_amount' => $request->amount_pay,
                                'recipient' => \Auth::user()->name
                            ]);
                        }
                    } else {
                        $customerOwed = $this->customerOwed->create($saleRequest);
                    }
                }
                return \Redirect::route('customer_owed.index')
                    ->with('warning', __('flash.update'));
            }
        } catch (\ValidationException $e) {
            return exceptionError($e, 'customers.index');
        }
    }

    public function updatePayModal(Request $request)
    {
        try {
            $saleRequest = $request->all();
            $sale = $this->sale->available($saleRequest["sale_id"]);

            $sale->update([
                'money_change' => $sale->total_amount
            ]);
            if ($sale) {
                $customerOwed = $this->customerOwed->where('sale_id', $saleRequest["sale_id"])
                    ->where('customer_id', $saleRequest["customer_id"])
                    ->first();

                $saleRequest['amount'] = $sale->total_amount;
                $saleRequest['receive_amount'] = $sale->total_amount;
                $saleRequest['owed_amount'] = 0;
                $saleRequest['receive_date'] = date('Y-m-d h:i:s');
                $saleRequest['status_pay'] = 2;
                $saleRequest['date_pay'] = date('Y-m-d h:i:s');
                if ($customerOwed) {
                    $customerOwed->update($saleRequest);
                    // store customer owed history
                    $customerOwedHistory = CustomerOwedHistory::where('customer_owed_id', $customerOwed->id)
                        ->first();
                    if ($customerOwedHistory) {
                        $customerOwedHistory->update([
                            'customer_owed_id' => $customerOwed->id,
                            'receive_amount' => $request->amount_pay,
                            'recipient' => \Auth::user()->name
                        ]);
                    } else {
                        CustomerOwedHistory::create([
                            'customer_owed_id' => $customerOwed->id,
                            'receive_amount' => $request->amount_pay,
                            'recipient' => \Auth::user()->name
                        ]);
                    }
                } else {
                    $this->customerOwed->create($saleRequest);
                }
            }
            return \Redirect::route('customer_owed.index', ['pay_model' => 1])
                ->with('warning', __('flash.update'));
        } catch (\ValidationException $e) {
            return exceptionError($e, 'customers.index');
        }
    }

    public function updateSetDateModal(Request $request)
    {
        try {
            $saleRequest = $request->all();
            $sale = $this->sale->available($saleRequest["sale_id"]);
            if ($sale) {
                $customerOwed = $this->customerOwed->where('sale_id', $saleRequest["sale_id"])
                    ->where('customer_id', $saleRequest["customer_id"])
                    ->first();
                if ($customerOwed) {
                    $customerOwed->update($saleRequest);
                } else {
                    $sale = $this->sale->available($saleRequest["sale_id"]);
                    if ($sale) {
                        $this->customerOwed->create([
                            'sale_id' => $saleRequest["sale_id"],
                            'customer_id' => $saleRequest["customer_id"],
                            'amount' => $sale->total_amount,
                            'amount_pay' => $sale->total_amount,
                            'owed_amount' => $sale->total_amount,
                            'status_pay' => 0,
                            'date_pay' => $saleRequest["date_pay"]
                        ]);
                    }
                }
            }
            return \Redirect::route('customer_owed.index', ['pay_model' => 1])
                ->with('warning', __('flash.update'));
        } catch (\ValidationException $e) {
            return exceptionError($e, 'customers.index');
        }
    }

    public function getSaleByCustomer(Request $request)
    {
        $sales = [];
        try {
            $sales = $this->sale->where('is_delete', '<>', 0)
                ->where('is_active', 1) // is_delete = 1 and is_active = 1
                ->where('customer_id', $request->customer_id)
                ->orderBy('id', 'DESC')
                ->get();

            return response()
                ->json([
                    'sales' => $sales,
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

    public function paginateArrayToCllect($dataArray, $request, $perPage = 1)
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage(); // Get current page form url e.x. &page=1
        $itemCollection = collect($dataArray); // Create a new Laravel collection from the array data
        // Slice the collection to get the items to display in current page
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
        // Create our paginator and pass it to the view
        $paginatedItems = new LengthAwarePaginator($currentPageItems, count($itemCollection), $perPage);
        // set url path for generted links
        return $paginatedItems->setPath($request->url());
    }

    public function downloadPDF()
    {
        try {
            $sales = $this->sale->where('is_delete', '<>', 0)
                ->orderBy('created_at', 'DESC')
                ->with('customerOwed')
                ->get();
            $total = 0;
            // not pay
            $totalOweds = 0;
            $amount = 0;
            $customerOwed = 0;
            $receiveAmount = 0;
            foreach ($sales as $sale) {
                if(!$sale->customerOwed()->exists() || $sale->customerOwed()->exists() && $sale->customerOwed->status_pay == 0 || $sale->customerOwed()->exists() && $sale->customerOwed->status_pay == 1) {
                    $customerOwed = 0;
                    $amount = $sale->customerOwed()->exists() && $sale->customerOwed->amount ? $sale->customerOwed->amount : $sale->total_amount;
                    $receiveAmount = $sale->customerOwed()->exists() && $sale->customerOwed->receive_amount  ? $sale->customerOwed->receive_amount : 0;
                    $customerOwed = $sale->customerOwed()->exists() && $sale->customerOwed->owed_amount ? $sale->customerOwed->owed_amount : ($amount - $receiveAmount);
                    $totalOweds += $customerOwed;
                }
            }
            $total = $totalOweds;
            return view('backends.customer_oweds.invoice_owed', [
                'total' => $total,
            ]);
            $dateSale = date('Y-m-d');
            $pdfName = "total-report-{$dateSale}" . ".pdf";
            $pdfSale = PDF::loadView('backends.customer_oweds.invoice_owed.', ['sale' => $sale]);
            $pdfSale->setPaper('a4');
            return $pdfSale->download($pdfName);

        } catch (ValidationException $e) {
            return exceptionError($e, 'customer_owed.index');
        }
    }
}
