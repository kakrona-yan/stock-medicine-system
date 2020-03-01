<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Constants\DeleteStatus;

class Sale extends BaseModel
{
    protected $fillable = [
        'staff_id',
        'customer_id',
        'quotaion_no',
        'money_change',
        'total_quantity',
        'total_discount',
        'total_amount',
        'sale_date',
        'note',
        'is_active',
        'is_delete'   
    ];
    
    protected $dates = [
        'sale_date',
    ];

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }

    public function staff()
    {
        return $this->belongsTo('App\Models\Staff', 'staff_id');
    }

    public function productSales()
    {
        return $this->hasMany('App\Models\SaleProduct', 'sale_id', 'id');
    }

    public function filter($request)
    {
        $sales = $this->where('is_delete', '<>', DeleteStatus::DELETED)->orderBy('id', 'DESC');
        // login of staff
        if(\Auth::user()->isRoleStaff()) {
            $staffId = \Auth::user()->staff ? \Auth::user()->staff->id : \Auth::id();
            $sales->where('staff_id', $staffId)
                ->whereYear('sale_date', date('Y'))
                ->whereMonth('sale_date', date('m'));
        }
        if ($request->exists('quotaion_no') && !empty($request->quotaion_no)) {
            $sales->where('quotaion_no', 'like', '%' . $request->quotaion_no . '%');
        }
        if ($request->exists('customer_name') && !empty($request->customer_name)) {
            $customerName = $request->customer_name;
            $sales->whereHas('customer', function($customer) use ($customerName){
                $customer->where('name', 'like', '%' . $customerName . '%');
            });
        }
        if ($request->exists('staff_name') && !empty($request->staff_name)) {
            $staffName = $request->staff_name;
            $sales->whereHas('staff', function($staff) use ($staffName){
                $staff->where('name', 'like', '%' . $staffName . '%');
            });
        }
        if ($request->exists('sale_date') && !empty($request->sale_date)) {
            $sales->whereDate('sale_date', date('Y-m-d', strtotime($request->sale_date)));
        }
        // Check flash danger
        flashDanger($sales->count(), __('flash.empty_data'));
        $limit = config('pagination.limit');
        if ($request->exists('limit') && !is_null($request->limit)) {
            $limit = $request->limit;
        }

        return $sales->paginate($limit);
    }
}
