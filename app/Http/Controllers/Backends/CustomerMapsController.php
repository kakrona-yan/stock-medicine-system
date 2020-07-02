<?php

namespace App\Http\Controllers\Backends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\StaffGPSMap;

class CustomerMapsController extends Controller
{
    public function __construct(
        Customer $customer,
        StaffGPSMap $staffGPSMap
    ){
        $this->customer = $customer;
        $this->staffGPSMap = $staffGPSMap;
    }

    public function index(Request $request)
    {
        $customerMaps = [];
        try {
            $customerMaps = $this->customer->where('is_delete', '<>', 0)
                ->where('is_active', 1) // is_delete = 1 and is_active = 1
                ->with(['customerType', 'sales.staff'])
                
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

    public function saveGPSMap(Request $request)
    {
      
       try {
            $staffId = \Auth::user()->staff ? \Auth::user()->staff->id : \Auth::id();
            $staffGPSMap = $this->staffGPSMap->where("staff_id", $staffId)
                ->where("latitude", $request->latitude)
                ->where("longitude", $request->longitude)
                ->orderBy('id', 'desc')->first();
            if($staffGPSMap){
                if($staffGPSMap->end_date_place && $staffGPSMap->latitude != $request->latitude){
                    $staffGPSMap->update([
                        'end_date_place' => date('Y-m-d h:i:s')
                    ]);
                }
            } else {
                $this->staffGPSMap->create([
                    'staff_id' => $staffId,
                    'latitude' => $request->latitude,
                    'longitude' =>$request->longitude,
                    'start_date_place' => date('Y-m-d h:i:s')
                ]);
            }
            
            return responseSuccess(
                \Session::flash('warning', __('flash.update')),
                    ['danger', 'danger']
                );
        } catch (Exception $e) {
            return response()
            ->json([
                'code'  => 422,
                'status'  => 'fail'
            ]);
        }
    }

    public function staffGPSMap(Request $request)
    {
        $staffMaps = [];
        try {
            $staffMaps = $this->staffGPSMap->get();
            foreach ($staffMaps as $staffMap) {
                $staffMap["name"] = $staffMap->staff->name;
                $staffMap["thumbnail"] = $staffMap->staff->getStaffImagePath($staffMap->staff->thumbnail);
            }
            $latitude = $staffMaps->count()  ? $staffMaps->average('latitude') : 11.5629411;
            $longitude = $staffMaps->count() ? $staffMaps->average('longitude') : 104.9060205;
        
            return view('backends.customers.map.staff_map', [
                'request' => $request,
                'staffMaps' =>  $staffMaps,
                'latitude' => $latitude,
                'longitude' => $longitude
            ]);
        }catch (\ValidationException $e) {
            return exceptionError($e, 'customers.map.index');
        }
    }
}
