<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Constants\DeleteStatus;
use App\Http\Constants\UserRole;

class Customer extends BaseModel
{

    protected $table = 'customers';
    
    protected $fillable = [
        'name',
        'gender',
        'dob',
        'email',
        'phone1',
        'phone2',
        'address',
        'thumbnail',
        'is_active',
        'is_delete',
    ];

    public function filter($request)
    {
        $customer = $this->where('is_delete', '<>', DeleteStatus::DELETED)
                    ->orderBy('created_at', 'DESC');
        // filter by name
        if ($request->exists('name') && !empty($request->name)) {
            $customer->where('name', 'like', '%' . $request->name . '%');
        }
        // filter by gender
        if ($request->exists('gender') && !empty($request->gender)) {
            $customer->where('gender', $request->gender);
        }
        // filter by phone number
        if ($request->exists('phone_number') && !empty($request->phone_number)) {
            $customer->where('phone1', $request->phone_number)
                    ->orWhere('phone2', $request->phone_number);
        }
        // filter by email
        if ($request->exists('email') && !empty($request->email)) {
            $customer->where('email', $request->email);
        }
        $limit = config('pagination.limit');
        // Check flash danger
        flashDanger($customer->count(), __('flash.empty_data'));
        return $customer->paginate($limit);
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

    public function getCustomer()
    {
        return $this->where('is_delete', '<>', DeleteStatus::DELETED)
            ->pluck('name', 'id')
            ->all();
    }
}
