<?php

namespace App\Http\Controllers\Backends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\User;
use App\Http\Constants\UserRole;
use App\Models\GroupStaff;

class GroupStaffsController extends Controller
{
    public function __construct(
        User $user,
        Staff $staff,
        GroupStaff $groupStaff
    ) {
        $this->user = $user;
        $this->staff = $staff;
        $this->groupStaff = $groupStaff;
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
                'name' => 'required|unique:group_staffs,name',
            ];
            // Set field of Validattion
            $validator = \Validator::make([
                'name' => $request->name,
            ], $rules);
            if ($validator->fails()) {
                return response()->json(["errors" => $validator->errors()], 422);
            } else {
                $groupStaffs = $request->all();
                $this->groupStaff->create($groupStaffs);
            }
            return \Redirect::route('staff.index')
                ->with('success', __('flash.store'));

        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.staffs.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            // Rules of field
            $rules = [
                'name' => 'required|unique:customer_types,name, ' . $request->group_staff_id,
            ];
            // Set field of Validattion
            $validator = \Validator::make([
                'name' => $request->name,
            ], $rules);
            if ($validator->fails()) {
                return response()->json(["errors" => $validator->errors()], 422);
            } else {
                $groupStaffs = $request->all();
                $groupStaff = $this->groupStaff->available($request->group_staff_id);
                if($groupStaff) {
                    $groupStaff->update($groupStaffs);
                } 
            }

            return \Redirect::route('customer_type.index')
                ->with('warning', __('flash.update'));

        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.customer_types.index');
        }
    }

}
