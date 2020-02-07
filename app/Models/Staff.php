<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Constants\UserRole;
use App\Http\Constants\DeleteStatus;

class Staff extends BaseModel
{
    protected $table = 'staffs';
    
    protected $fillable = [
        'user_id',
        'firstname',
        'lastname',
        'email',
        'phone1',
        'phone2',
        'address',
        'thumbnail',
        'is_active',
        'is_delete',
        'password'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function filter($request)
    {
        $suppliers = $this->where('is_delete', '<>', DeleteStatus::DELETED)
                    ->orderBy('created_at', 'DESC');
        // filter by first name
        if ($request->exists('firstname') && !empty($request->firstname)) {
            $suppliers->where('firstname', 'like', '%' . $request->firstname . '%');
        }
        // filter by last name
        if ($request->exists('lastname') && !empty($request->lastname)) {
            $suppliers->where('lastname', 'like', '%' . $request->lastname . '%');
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
        return $suppliers->paginate($limit);
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
        return ucfirst($this->lastname) . ' ' . ucfirst($this->firstname);
    }

    public function getStaffName()
    {
        return $this->where('is_delete', '<>', DeleteStatus::DELETED)
            ->select(['id', 'firstname', 'lastname'])
            ->get();
    }
    
}
