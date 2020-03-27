<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerOwed extends BaseModel
{
    protected $fillable = [
        'sale_id',
        'customer_id',
        'amount',
        'receive_amount',
        'owed_amount',
        'receive_date',  
    ];
    
    protected $dates = [
        'receive_date',
    ];

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }

    public function sale()
    {
        return $this->belongsTo('App\Models\Sale', 'sale_id');
    }

    public function filter($request) 
    {
        $customerOweds = $this->orderBy('id', 'DESC');
        $limit = config('pagination.limit');
        // Check flash danger
        flashDanger($customerOweds->count(), __('flash.empty_data'));
        return $customerOweds->paginate($limit);
    }

    public function getPrice($column)
    {
        dd($column);
        $price = $this->exists() ? $this->{$column} : 0;
        return currencyFormat($price);
    }
}
