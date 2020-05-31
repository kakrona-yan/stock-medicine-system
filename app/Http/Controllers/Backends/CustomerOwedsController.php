<?php

namespace App\Http\Controllers\Backends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerOwed;
use App\Models\Sale;
use App\Http\Constants\DeleteStatus;
use App\Models\CustomerOwedHistory;

class CustomerOwedsController extends Controller
{
    const STATUS_NOT_PAY = 0;
    const STATUS_SOME_PAY = 1;
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
            // list all
            $customers  = $this->customer->where('is_delete', '<>', DeleteStatus::DELETED)
                ->whereHas('sales')
                ->orderBy('created_at', 'DESC');
            $limit = 30;
            if ($request->exists('quotaion_no') && !empty($request->quotaion_no)) {
                $quotationNo = $request->quotaion_no;
                $customers->whereHas('sales', function($sales) use($quotationNo){
                    $sales->where('quotaion_no', 'like', '%' . $quotationNo . '%');
                });
            }
            
            // Check flash danger
            flashDanger($customers->count(), __('flash.empty_data'));
            $customers = $customers->paginate($limit, ['*'], 'customers_page');
            $notPay = self::STATUS_NOT_PAY;
            $somePay = self::STATUS_SOME_PAY;
            $allPay = self::STATUS_ALL_PAY;
            // customer not yet pay
            $customerNotPays  = $this->customer->where('is_delete', '<>', DeleteStatus::DELETED)
                ->orderBy('created_at', 'DESC');
            if ($request->exists('quotaion_no') && !empty($request->quotaion_no)) {
                $quotationNo = $request->quotaion_no;
                $customerNotPays->whereHas('sales', function($sales) use($quotationNo){
                    $sales->where('quotaion_no', 'like', '%' . $quotationNo . '%');
                });
            }
            // Check flash danger
            flashDanger($customerNotPays->count(), __('flash.empty_data'));
            $customerNotPays = $customerNotPays->paginate($limit, ['*'], 'pay_no_page');
            
            // customer of sale
            $sales = $this->sale->where('is_delete', '<>', 0)
                ->orderBy('created_at', 'DESC')
                ->with('customerOwed');
                
            if ($request->exists('quotaion_no') && !empty($request->quotaion_no)) {
                $quotationNo = $request->quotaion_no;
                $sales = $sales->where('quotaion_no', 'like', '%' . $quotationNo . '%');
            }
            if ($request->exists('customer_name') && !empty($request->customer_name)) {
                $customerName = $request->customer_name;
                $sales->whereHas('customer', function($customer) use ($customerName){
                    $customer->where('name', 'like', '%' . $customerName . '%');
                });
            }
            if ($request->exists('staff_name') && !empty($request->staff_name)) {
                $staffName = $request->staff_name;
                $sales->whereHas('staff', function($staff) use ($staffName){
                    $staff->where('name', 'like', '%' . $staffName . '%');
                });
            }
            // Check flash danger
            flashDanger($sales->count(), __('flash.empty_data'));
            $sales = $sales->get();

            $statusPays = CustomerOwed::STATUS_PAY_TEXT_FORM;
            return view('backends.customer_oweds.index', [
                'request' => $request,
                'customers' =>  $customers,
                'customerNotPays' => $customerNotPays,
                'sales' => $sales,
                'statusPays' => $statusPays
            ]);
        }catch (\ValidationException $e) {
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
                if($sale) {
                    $customerOwed = $this->customerOwed->where('sale_id', $saleRequest["sale_id"])
                        ->where('customer_id', $saleRequest["customer_id"])
                        ->first();
                    if($customerOwed) {
                        $customerOwed->update($saleRequest);
                        // store customer owed history
                        $customerOwedHistory = CustomerOwedHistory::where('customer_owed_id', $customerOwed->id)
                            ->first();
                        if($customerOwedHistory){
                            $customerOwedHistory->update([
                                'customer_owed_id' => $customerOwed->id,
                                'receive_amount' => $request->amount_pay,
                                'recipient' => \Auth::user()->name
                            ]);
                        } else{
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
                    ->with('warning',__('flash.update'));
            }
        }catch (\ValidationException $e) {
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
            if($sale) {
                $customerOwed = $this->customerOwed->where('sale_id', $saleRequest["sale_id"])
                    ->where('customer_id', $saleRequest["customer_id"])
                    ->first();

                $saleRequest['amount'] = $sale->total_amount;
                $saleRequest['receive_amount']= $sale->total_amount;
                $saleRequest['owed_amount']= 0;
                $saleRequest['receive_date']= date('Y-m-d h:i:s');
                $saleRequest['status_pay']= 2;
                $saleRequest['date_pay'] = date('Y-m-d h:i:s');
                if($customerOwed) {
                    $customerOwed->update($saleRequest);
                    // store customer owed history
                    $customerOwedHistory = CustomerOwedHistory::where('customer_owed_id', $customerOwed->id)
                    ->first();
                    if($customerOwedHistory){
                        $customerOwedHistory->update([
                            'customer_owed_id' => $customerOwed->id,
                            'receive_amount' => $request->amount_pay,
                            'recipient' => \Auth::user()->name
                        ]);
                    } else{
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
                ->with('warning',__('flash.update'));
           
        }catch (\ValidationException $e) {
            return exceptionError($e, 'customers.index');
        }
    }

    public function updateSetDateModal(Request $request)
    {
        try {
            $saleRequest = $request->all();
            $sale = $this->sale->available($saleRequest["sale_id"]);
            if($sale) {
                $customerOwed = $this->customerOwed->where('sale_id', $saleRequest["sale_id"])
                    ->where('customer_id', $saleRequest["customer_id"])
                    ->first();
                if($customerOwed) {
                    $customerOwed->update($saleRequest);
                } else {
                    $this->customerOwed->create($saleRequest);
                }
            }
            return \Redirect::route('customer_owed.index', ['pay_model' => 1])
                ->with('warning',__('flash.update'));
           
        }catch (\ValidationException $e) {
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
}
