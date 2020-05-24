<?php

namespace App\Http\Controllers\Backends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\User;
use App\Http\Constants\UserRole;
use App\Models\GroupStaff;
use App\Models\Product;
use App\Http\Constants\DeleteStatus;

class GroupStaffsController extends Controller
{
    public function __construct(
        User $user,
        Staff $staff,
        GroupStaff $groupStaff,
        Product $product
    ) {
        $this->user = $user;
        $this->staff = $staff;
        $this->groupStaff = $groupStaff;
        $this->product = $product;
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
            return responseSuccess(
                \Session::flash('success', __('flash.store')),
                    ['danger', 'danger']
                );

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
                'name' => 'required|unique:group_staffs,name, ' . $request->group_staff_id,
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

            return responseSuccess(
                \Session::flash('success', __('flash.store')),
                    ['danger', 'danger']
                );

        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.staffs.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function groupCreate(Request $request, $id)
    {
        try {
            $staffs = $this->staff->orderBy('created_at', 'DESC')
                ->whereIn('group_staff_id', [0, $id])
                ->get();
            $products = $this->product->orderBy('created_at', 'DESC')->get();
            $groupStaffNames = $this->groupStaff->getGroupStaffName();
            $groupStaff = $this->groupStaff->available($id);
            return view('backends.staffs.group_create', [
                'request' => $request,
                'staffs' =>  $staffs,
                'groupStaffNames' => $groupStaffNames,
                'products' => $products,
                'id' => $id,
                'groupStaff' => $groupStaff
            ]);
            
        }catch (\ValidationException $e) {
            return exceptionError($e, 'backends.staffs.group_create');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function  groupCreateProduct(Request $request)
    {
        try {
            $products = $this->product->orderBy('created_at', 'DESC')->get();
            $groupStaffNames = $this->groupStaff->getGroupStaffName();
            return view('backends.staffs.group_product_create', [
                'request' => $request,
                'groupStaffNames' => $groupStaffNames,
                'products' => $products,
            ]);
            
        }catch (\ValidationException $e) {
            return exceptionError($e, 'backends.staffs.group_product_create');
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function groupUpdateByStaffIds(Request $request)
    {
        try {
            $update = $request->update;
            switch ($update) {
                case 1:
                    $staffs = $request->staff;
                    $this->staff->whereIn("id", $staffs)
                        ->update([
                            "group_staff_id" => $request->group_staff_id
                        ]);
                    break;
                case 2:
                    $products = $request->product;
                    foreach ($products as $product) {
                        $id = isset($product["id"]) ? $product["id"] : 0;
                        $findProduct = $this->product->find($id);
                        if($findProduct) {
                            $groupStaffId = implode(',',$product['group_staff_id']);
                            $findProduct->update([
                                'group_staff_id' => $groupStaffId
                            ]);
                        }
                    }
                    break;
                
            }
           
            return \Redirect::route('staff.index', $request->group_staff_id)
                    ->with('success',__('flash.store'));
            
        }catch (\ValidationException $e) {
            return exceptionError($e, 'backends.settings.group_create');
        }
    }

}
