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
        $sales = $this->where('is_delete', '<>', DeleteStatus::DELETED)
            ->orderBy('id', 'DESC');
        // login of staff
        if(\Auth::user()->isRoleStaff()) {
            $staffId = \Auth::user()->staff ? \Auth::user()->staff->id : \Auth::id();
            $sales->where('staff_id', $staffId)
                ->whereYear('sale_date', date('Y'))
                ->whereMonth('sale_date', date('m'));
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
