<?php

namespace App\Http\Controllers\Backends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\User;
use Auth;
use App\Http\Requests\CustomerCreateRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Http\Constants\UserRole;
use App\Models\CustomerType;
use App\Models\Sale;
use App\Http\Constants\DeleteStatus;

class CustomersController extends Controller
{
    public function __construct(
        User $user,
        Customer $customer,
        CustomerType $customerType
    ) {
        $this->user = $user;
        $this->customer = $customer;
        $this->customerType = $customerType;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $customers  = $this->customer->filter($request);
            $customerTypes = $this->customerType->filter($request);
            $genders = UserRole::USER_GANDER_TEXT_EN;
            return view('backends.customers.index', [
                'request' => $request,
                'customers' =>  $customers,
                'genders' => $genders,
                'customerTypes' => $customerTypes
            ]);
        }catch (\ValidationException $e) {
            return exceptionError($e, 'customers.index');
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
            $customerTypes = $this->customerType->getCustomerTypeName();
            return view('backends.customers.create', [
                'request' => $request,
                'customerTypes' => $customerTypes
            ]);
        }catch (\ValidationException $e) {
            return exceptionError($e, 'customers.create');
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
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $id)
    {
        try {
            $customer = $this->customer->available($id);
            if (!$customer) {
                return response()->view('errors.404', [], 404);
            }
            $saleCustomers = Sale::where('is_delete', '<>', DeleteStatus::DELETED)
                ->where('customer_id', $id)
                ->orderBy('id', 'DESC')
                ->get();
            return view('backends.customers.show', [
                'customer' => $customer,
                'saleCustomers' => $saleCustomers
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'customers.show');
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
            $customerName = $this->customerType->getCustomerTypeName();
            $customer = $this->customer->available($id);
            if (!$customer) {
                return response()->view('errors.404', [], 404);
            }
            return view('backends.customers.edit', [
                'request' => $request,
                'customerName' => $customerName,
                'customer' => $customer,
                
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'customers.edit');
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
                'name' => 'required|unique:customers,name, ' . $id,
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
                $customer = $this->customer->available($id);
                if (!$customer) {
                    return response()->view('errors.404', [], 404);
                }
                if (!empty($request->thumbnail)) {
                    $customerRequest['thumbnail'] = uploadFile($request->thumbnail, config('upload.customer'));
                }
                $customer->update($customerRequest);
                return \Redirect::route('customer.index')
                    ->with('warning',__('flash.update'));
            }
        } catch (\ValidationException $e) {
            return exceptionError($e, 'customers.index');
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
            $id = $request->customer_id;
            $customer = $this->customer->available($id);
            if (!$customer) {
                return response()->view('errors.404', [], 404);
            }
            $customer->remove();
            return redirect()->route('customer.index')
                ->with('danger',__('flash.destroy'));
        } catch (\ValidationException $e) {
            return exceptionError($e, 'customers.index');
        }
    }
}