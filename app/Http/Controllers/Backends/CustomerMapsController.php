<?php

namespace App\Http\Controllers\Backends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\StaffGPSMap;
use App\Models\Staff;
use App\Models\User;
use App\Http\Constants\UserRole;
use App\Models\GroupStaff;
use Session;

class CustomerMapsController extends Controller
{
    public function __construct(
        Customer $customer,
        StaffGPSMap $staffGPSMap,
        Staff $staff,
        GroupStaff $groupStaff
    ){
        $this->customer = $customer;
        $this->staffGPSMap = $staffGPSMap;
        $this->staff = $staff;
        $this->groupStaff = $groupStaff;
    }

    public function index(Request $request)
    {
        $customerMaps = [];
        try {
            $customerMaps = $this->customer->where('is_delete', '<>', 0)
                ->where('is_active', 1) // is_delete = 1 and is_active = 1
                ->with(['customerType', 'sales.staff'])
                ->orderBy('id', 'DESC');
            if ($request->exists('customer_id') && $request->exists('latitude') && $request->exists('longitude')) {
                $customerMaps->where('id', $request->customer_id)
                ->where('latitude', $request->latitude)
                ->where('longitude', $request->longitude);
            }
            $customerMaps = $customerMaps->get();
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

    public function saveStaffGPS(Request $request) {
        try {
            session(['staff_latitude' => $request->staff_latitude]);
            session(['staff_longitude' => $request->staff_longitude]);
            
            return response()
            ->json([
                'status' => 'success',
                'code' => 200,
                'message' => "សូមបន្តcheckin លើតំបន់របស់អតិថិជន"
            ]);
        } catch (Exception $e) {
            return response()
            ->json([
                'code'  => 422,
                'status'  => 'fail'
            ]);
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
                if($staffId ==  $staffGPSMap->staff_id && $staffGPSMap->latitude != $request->latitude){
                    $staffGPSMap->update([
                        'end_date_place' => date('Y-m-d h:i:s')
                    ]);
                }
            } else {
                $this->staffGPSMap->create([
                    'staff_id' => $staffId,
                    'customer_id' => $request->customer_id,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'staff_latitude' => session('staff_latitude'),
                    'staff_longitude' => session('staff_longitude'),
                    'start_date_place' => date('Y-m-d h:i:s')
                ]);
            }
            session()->forget(['staff_latitude', 'staff_longitude']);
            return response()
            ->json([
                'status' => 'success',
                'code' => 200,
                'customer_id' => $request->customer_id,
                'message' => "checkin បានជោគជ័យ"
            ]);
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
            $staffMaps = $this->staffGPSMap->with("customer")->whereDate('start_date_place', date('Y-m-d'));
            if ($request->exists('staff_id') && $request->exists('latitude') && $request->exists('longitude')) {
                $staffMaps->where('staff_id', $request->staff_id)
                ->where('staff_latitude', $request->latitude)
                ->where('staff_longitude', $request->longitude);
            }
            $staffMaps = $staffMaps->get();
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function staffCheckinList(Request $request)
    {
        try {
            $staffs  = $this->staff->filter($request);
            $genders = UserRole::USER_GANDER_TEXT_EN;
            $groupStaffs  = $this->groupStaff->filter($request);

            return view('backends.staffs.checkin', [
                'request' => $request,
                'staffs' =>  $staffs,
                'genders' => $genders,
                'groupStaffs' => $groupStaffs
            ]);
        }catch (\ValidationException $e) {
            return exceptionError($e, 'backends.staffs.index');
        }
    }
}
