<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Constants\DeleteStatus;
use App\Http\Constants\UserRole;
use Illuminate\Support\Facades\Storage;

class Customer extends BaseModel
{

    protected $table = 'customers';
    
    protected $fillable = [
        'customer_type_id',
        'name',
        'gender',
        'dob',
        'email',
        'phone1',
        'phone2',
        'address',
        'map_address',
        'thumbnail',
        'is_active',
        'is_delete',
        'longitude',
        'latitude'
    ];

    public function sales()
    {
        return $this->hasMany('App\Models\Sale', 'customer_id', 'id');
    }

    public function sale()
    {
        return $this->hasOne('App\Models\Sale', 'customer_id', 'id');
    }

    public function customerOweds()
    {
        return $this->hasMany('App\Models\CustomerOwed', 'customer_id', 'id');
    }
    
    public function staffGPSMaps()
    {
        return $this->hasMany('App\Models\StaffGPSMap', 'customer_id', 'id');
    }

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
            $customer->where('phone1', 'like', '%' . $request->phone_number . '%')
                    ->orWhere('phone2', 'like', '%' . $request->phone_number . '%');
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
            ->orderBy('id', 'DESC')   
            ->pluck('name', 'id')
            ->all();
    }

    public function customerType()
    {
        return $this->belongsTo('App\Models\CustomerType', 'customer_type_id');
    }

    public function customerFullName()
    {
        return $this->customerType()->exists() ? $this->customerType->name ." ". $this->name : $this->name;
    }

    /**
     * getQuestionImagePath
     * @param $path
     */
    public function getCustomerImagePath($path)
    {
        if (Storage::disk(config('upload.customer'))->exists($path)) {
            return Storage::disk(config('upload.customer'))->url($path);
        }
    }

    public function countCheckInByStaff()
    {
        if(\Auth::user()->isRoleAdmin() || \Auth::user()->isRoleEditor() || \Auth::user()->isRoleView()) return true;
        return $this->staffGPSMaps()->exists() && $this->staffGPSMaps()->whereDate('start_date_place', date('Y-m-d'))->first() ? true : false;
    }

}
