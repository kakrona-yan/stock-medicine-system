<?php

namespace App\Http\Controllers\Backends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\User;
use App\Http\Constants\UserRole;
use App\Http\Constants\DeleteStatus;

class SettingsController extends Controller
{
    public function __construct(
        User $user,
        Staff $staff
    ) {
        $this->user = $user;
        $this->staff = $staff;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            return view('backends.settings.index', [
                'request' => $request,
            ]);
        }catch (\ValidationException $e) {
            return exceptionError($e, 'backends.settings.index');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function customerOwnerByStaff(Request $request)
    {
        try {
            $staffs  = $this->staff->orderBy('name', 'DESC');
            if ($request->exists('name') && !empty($request->name)) {
                $customerName = $request->name;
                $staffs->whereHas('sales.customer', function($customer) use ($customerName){
                    $customer->where('name', 'like', '%' . $customerName . '%');
                });
            }
            $limit = config('pagination.limit');
            // Check flash danger
            flashDanger($staffs->count(), __('flash.empty_data'));
            $staffs =  $staffs->paginate($limit, ['*'], 'staff-page');

            return view('backends.settings.staff_to_customer', [
                'request' => $request,
                'staffs' =>  $staffs
            ]);
        }catch (\ValidationException $e) {
            return exceptionError($e, 'backends.settings.staff_to_customer');
        }
    }
}
