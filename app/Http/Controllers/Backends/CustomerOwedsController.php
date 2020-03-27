<?php

namespace App\Http\Controllers\Backends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerOwed;
use App\Models\Sale;
use App\Http\Constants\DeleteStatus;

class CustomerOwedsController extends Controller
{
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
            $customers  = $this->customer->where('is_delete', '<>', DeleteStatus::DELETED)
                ->orderBy('created_at', 'DESC');
            $limit = config('pagination.limit');
            // Check flash danger
            flashDanger($customers->count(), __('flash.empty_data'));
            $customers = $customers->paginate($limit);
            return view('backends.customer_oweds.index', [
                'request' => $request,
                'customers' =>  $customers,
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
            return view('backends.customer_oweds.edit', [
                'sale' => $sale,
                'request' => $request,
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
                    } else {
                        $this->customerOwed->create($saleRequest);
                    }
                }
                return \Redirect::route('customer_owed.index')
                    ->with('warning',__('flash.update'));
            }
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
