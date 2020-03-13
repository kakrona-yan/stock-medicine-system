<?php

namespace App\Http\Controllers\Backends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerType;

class CustomerTypesController extends Controller
{
    public function __construct(CustomerType $customer_type)
    {
        $this->customer_type = $customer_type;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $customerTypes = $this->customer_type->filter($request);
            return view('backends.customer_types.index', [
                'request' => $request,
                'customerTypes' => $customerTypes
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.customer_types.index');
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
            return view('backends.customer_types.create', [
                'request' => $request,
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.customer_types.create');
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
                'name' => 'required|unique:customer_types,name',
            ];
            // Set field of Validattion
            $validator = \Validator::make([
                'name' => $request->name,
            ], $rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $customerType = $request->all();
                $this->customer_type->create($customerType);
            }
            return \Redirect::route('customer_type.index')
                ->with('success', __('flash.store'));

        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.customer_types.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        try {
            $customerType = $this->customer_type->available($id);
            if (!$customerType) {
                return response()->view('errors.404', [], 404);
            }
            return view('backends.customer_types.show', [
                'customerType' => $customerType,
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.customer_types.show');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        try {
            $customerType = $this->customer_type->available($id);
            if (!$customerType) {
                return response()->view('errors.404', [], 404);
            }
            return view('backends.customer_types.edit', [
                'customerType' => $customerType
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.customer_types.edit');
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
                'name' => 'required|unique:customer_types,name, ' . $id,
            ];
            // Set field of Validattion
            $validator = \Validator::make([
                'name' => $request->name,
            ], $rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $customerTypeRequest = $request->all();
                $customerType = $this->customer_type->available($id);
                if (!$customerType) {
                    return response()->view('errors.404', [], 404);
                }
                $customerType->update($customerTypeRequest);
            }

            return \Redirect::route('customer_type.index')
                ->with('warning', __('flash.update'));

        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.customer_types.index');
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
            $id = $request->customer_type_id;
            $customerType = $this->customer_type->available($id);
            if (!$customerType) {
                return response()->view('errors.404', [], 404);
            }
            $customerType->remove();
            return redirect()->route('customer_type.index')
                ->with('danger', __('flash.destroy'));
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.customer_types.index');
        }
    }
}
