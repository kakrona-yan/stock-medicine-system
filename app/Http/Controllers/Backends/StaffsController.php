<?php

namespace App\Http\Controllers\Backends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\User;
use App\Http\Constants\UserRole;
use App\Models\GroupStaff;
use App\Http\Constants\DeleteStatus;
use App\Models\ StaffGPSMap;

class StaffsController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $staffs  = $this->staff->filter($request);
            $genders = UserRole::USER_GANDER_TEXT_EN;
            $groupStaffs  = $this->groupStaff->filter($request);

            return view('backends.staffs.index', [
                'request' => $request,
                'staffs' =>  $staffs,
                'genders' => $genders,
                'groupStaffs' => $groupStaffs
            ]);
        }catch (\ValidationException $e) {
            return exceptionError($e, 'backends.staffs.index');
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
            $groupStaffNames = $this->groupStaff->getGroupStaffName();
            return view('backends.staffs.create', [
                'request' => $request,
                'groupStaffNames' => $groupStaffNames
            ]);
        }catch (\ValidationException $e) {
            return exceptionError($e, 'backends.staffs.create');
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
            $email = $request->email;
            $ruleEmail = '';
            if ($email && !empty($email)) {
                $ruleEmail = 'nullable|email|unique:staffs|unique:users,email';
            }
            $rules = [
                'name' => 'required',
                'email' => $ruleEmail,
                'phone1' => 'required',
                'address' => 'required',
                'thumbnail'         => 'nullable|mimes:jpeg,jpg,png|max:10240',
                'password' => [
                    'required',
                    'max:30',
                    'min:6',
                    'regex:/^[!-~]+$/'
                ],
            ];
            // Set field of Validattion
            $validator = \Validator::make([
                'name' => $request->name,
                'email' => $request->email,
                'phone1' => $request->phone1,
                'phone2' => $request->phone2,
                'address' => $request->address,
                'thumbnail' => $request->thumbnail,
                'password' => $request->password,
            ], $rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $staffRequest = $request->all();
                $user = [];
                // insert user
                $user['name'] = ucfirst($request->name);
                $user['role'] = 2;
                $user['email_verified_at'] = now();
                $user['email'] = $request->email;
                $user['password'] =  bcrypt($staffRequest['password']);
                $user['thumbnail'] = $request->thumbnail ? uploadFile($request->thumbnail, config('upload.user')) : '';
                $user = $this->user->create($user);
                if($user) {
                    if ($request->exists('thumbnail') && !empty($staffRequest['thumbnail'])) {
                        $staffRequest['thumbnail'] = uploadFile($staffRequest['thumbnail'], config('upload.staff'));
                    }
                    $staffRequest['user_id'] = $user->id;
                    $this->staff->create($staffRequest);
                }
                
                return \Redirect::route('staff.index')
                    ->with('success',__('flash.store'));
            }
        }catch (\ValidationException $e) {
            return exceptionError($e, 'backends.staffs.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $id)
    {
        try {
            $staff = $this->staff->available($id);
            if (!$staff->exists()) {
                return abort(404);
            }
            $gpsStaffs = StaffGPSMap::where('staff_id', $staff->id)->whereDate('start_date_place', date('Y-m-d'))->get();
            return view('backends.staffs.show', [
                'staff' => $staff,
                'gpsStaffs' => $gpsStaffs
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.staffs.show');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, int $id)
    {
        try {
            $staff = $this->staff->available($id);
            if (!$staff->exists()) {
                return abort(404);
            }
            $groupStaffNames = $this->groupStaff->getGroupStaffName();
            return view('backends.staffs.edit', [
                'request' => $request,
                'staff' => $staff,
                'groupStaffNames' => $groupStaffNames
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.staffs.edit');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            // Rules of field
            $email = $request->email;
            $ruleEmail = '';
            $staff = $this->staff->available($id);
            if ($email && !empty($email)) {
                $userId = $staff->user ? $staff->user->id : $id;
                $ruleEmail = 'nullable|email|unique:staffs,email,' . $id .'|unique:users,email,' . $userId;
            }
            $rules = [
                'name' => 'required',
                'email' => $ruleEmail,
                'phone1' => 'required',
                'address' => 'required',
                'thumbnail'         => 'nullable|mimes:jpeg,jpg,png|max:10240',
                'password' => [
                    'required',
                    'max:30',
                    'min:6',
                    'regex:/^[!-~]+$/'
                ],
            ];
            // Set field of Validattion
            $validator = \Validator::make([
                'name' => $request->name,
                'email' => $request->email,
                'phone1' => $request->phone1,
                'phone2' => $request->phone2,
                'address' => $request->address,
                'thumbnail' => $request->thumbnail,
                'password' => $request->password,
            ], $rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $staffRequest = $request->all();
                if (!$staff) {
                    return response()->view('errors.404', [], 404);
                }
                if (!empty($request->thumbnail)) {
                    $staffRequest['thumbnail'] = uploadFile($request->thumbnail, config('upload.staff'));
                }
                $staff->update($staffRequest);
                // update user
                if($staff) {
                    $user = [];
                    if ($request->password && !empty($request->password)) {
                        $user['password'] =  bcrypt($request->password);
                    }
                    if (!empty($request->thumbnail)) {
                        $user['thumbnail'] = uploadFile($request->thumbnail, config('upload.user'));
                    } 
                    $user['name'] = ucfirst($request->name);
                    $user['role'] = 2;
                    $user['email'] = $request->email;
                    $staff->user()->update($user);
                }
                return \Redirect::route('staff.index')
                    ->with('warning',__('flash.update'));
            }

        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.staffs.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            $id = $request->staff_id;
            $staff = $this->staff->available($id);
            if (!$staff) {
                return response()->view('errors.404', [], 404);
            }
            $staff->user->remove();
            $staff->remove();
            return redirect()->route('staff.index')
                ->with('danger',__('flash.destroy'));
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.staffs.index');
        }
    }
}
