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
            $customerOweds  = $this->customerOwed->filter($request);
            return view('backends.customer_oweds.index', [
                'request' => $request,
                'customerOweds' =>  $customerOweds,
            ]);
        }catch (\ValidationException $e) {
            return exceptionError($e, 'customer_owed.index');
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
            $customers = $this->customer->where('is_delete', '<>', DeleteStatus::DELETED)
                ->orderBy('id', 'DESC')
                ->get();

            return view('backends.customer_oweds.create', [
                'request' => $request,
                'customers' => $customers
            ]);
        }catch (\ValidationException $e) {
            return exceptionError($e, 'customer_oweds.create');
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
                'name' => 'required|unique:customers,name',
                'customer_type_id' => 'required',
                'phone1' => 'required',
                'address' => 'required',
                'thumbnail'         => 'nullable|mimes:jpeg,jpg,png|max:10240',
            ];
            // Set field of Validattion
            $validator = \Validator::make([
                'name' => $request->name,
                'customer_type_id' => $request->customer_type_id,
                'phone1' => $request->phone1,
                'address' => $request->address,
                'thumbnail' => $request->thumbnail,
            ], $rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $customerRequest = $request->all();
                if ($request->exists('thumbnail') && !empty($customerRequest['thumbnail'])) {
                    $customerRequest['thumbnail'] = uploadFile($customerRequest['thumbnail'], config('upload.customer'));
                }
                $this->customer->create($customerRequest);
                return \Redirect::route('customer.index')
                    ->with('success',__('flash.store'));
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
