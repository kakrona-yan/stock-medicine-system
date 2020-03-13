<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Constants\DeleteStatus;

class CustomerType extends BaseModel
{
    protected $table = 'customer_types';

    protected $fillable = [
        'name',
        'is_active',
        'is_delete'
    ];

    public function filter($request)
    {
        $customerType = $this->where('is_delete', '<>', DeleteStatus::DELETED)
            ->orderBy('created_at', 'DESC');
        // filter by name
        if ($request->exists('name') && !empty($request->name)) {
            $customerType->where('name', 'like', '%' . $request->name . '%');
        }
        $limit = config('pagination.limit');
        // Check flash danger
        flashDanger($customerType->count(), __('flash.empty_data'));
        return $customerType->paginate($limit);
    }

    public function customer()
    {
        return $this->hasOne('App\Models\Customer', 'customer_type_id', 'id');
    }

    public function getCustomerTypeName()
    {
        return $this->where('is_delete', '<>', DeleteStatus::DELETED)
            ->pluck('name', 'id')
            ->all();
    }
    
}
