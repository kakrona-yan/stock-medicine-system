<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Constants\UserRole;
use App\Http\Constants\DeleteStatus;
use Illuminate\Support\Facades\Storage;

class Staff extends BaseModel
{
    protected $table = 'staffs';
    
    protected $fillable = [
        'user_id',
        'group_staff_id',
        'name',
        'email',
        'phone1',
        'phone2',
        'address',
        'thumbnail',
        'is_active',
        'is_delete',
        'password',
        'line_id',
        'work_status'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function sales()
    {
        return $this->hasMany('App\Models\Sale', 'staff_id', 'id');
    }

    public function staffGPSMaps()
    {
        return $this->hasMany('App\Models\StaffGPSMap', 'staff_id', 'id');
    }

    public function sale()
    {
        return $this->hasOne('App\Models\Sale', 'staff_id', 'id');
    }

    public function groupStaff()
    {
        return $this->belongsTo('App\Models\GroupStaff', 'group_staff_id');
    }

    public function filter($request)
    {
        $suppliers = $this->where('is_delete', '<>', DeleteStatus::DELETED)
                    ->orderBy('created_at', 'DESC');
        // filter by first name
        if ($request->exists('name') && !empty($request->name)) {
            $suppliers->where('name', 'like', '%' . $request->name . '%');
        }
        // filter by last gender
        if ($request->exists('gender') && !empty($request->gender)) {
            $gender = UserRole::USER_GANDER_TEXT_EN[$request->gender];
            $suppliers->where('gender', $gender);
        }
        // filter by last phone
        if ($request->exists('phone_number') && !empty($request->phone_number)) {
            $suppliers->where('phone1', $request->phone_number)
                    ->orWhere('phone2', $request->phone_number);
        }
        // filter by last email
        if ($request->exists('email') && !empty($request->email)) {
            $suppliers->where('email', $request->email);
        }
        $limit = config('pagination.limit');
        // Check flash danger
        flashDanger($suppliers->count(), __('flash.empty_data'));
        return $suppliers->paginate($limit, ['*'], 'staff-page');
    }

    public function getGender()
    {
        $gender = $this->gender;
        $genderText = '';
        if (is_null($gender) && empty($gender)) return;
        switch ($gender) {
            case "male":
                $genderText = UserRole::USER_GANDER_TEXT_EN[1];
                break;
            case "female":
                $genderText = UserRole::USER_GANDER_TEXT_EN[2];
                break;
        }
        return $genderText;
    }

    public function getFullnameAttribute() {
        return ucfirst($this->name);
    }

    public function getStaffName()
    {
        return $this->where('is_delete', '<>', DeleteStatus::DELETED)
            ->orderBy('id', 'DESC')
            ->select(['id', 'name'])
            ->get();
    }

    /**
     * getQuestionImagePath
     * @param $path
     */
    public function getStaffImagePath($path)
    {
        if (Storage::disk(config('upload.staff'))->exists($path)) {
            return Storage::disk(config('upload.staff'))->url($path);
        }
    }
    
}
