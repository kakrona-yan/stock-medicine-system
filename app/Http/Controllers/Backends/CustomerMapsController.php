<?php

namespace App\Http\Controllers\Backends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerMapsController extends Controller
{
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function index(Request $request)
    {
        $customerMaps = [];
        try {
            $customerMaps = $this->customer->with("sales.staff")->where('is_delete', '<>', 0)
                ->where('is_active', 1) // is_delete = 1 and is_active = 1
                ->get();
            foreach ($customerMaps as $customerMap) {
                $customerMap->thumbnail = $this->customer->getCustomerImagePath($customerMap->thumbnail);
            }
            $latitude = $customerMaps->count() && (request()->filled('category') || request()->filled('search')) ? $customerMaps->average('latitude') : 11.5629411;
            $longitude = $customerMaps->count() && (request()->filled('category') || request()->filled('search')) ? $customerMaps->average('longitude') : 104.9060205;
        
            return view('backends.customers.map.index', [
                'request' => $request,
                'customerMaps' =>  $customerMaps,
                'latitude' => $latitude,
                'longitude' => $longitude
            ]);
        }catch (\ValidationException $e) {
            return exceptionError($e, 'customers.map.index');
        }
    }
}
