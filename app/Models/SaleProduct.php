<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleProduct extends BaseModel
{
    protected $primaryKey = 'id';

    protected $fillable = [
        'sale_id',
        'product_id',
        'rate',
        'quantity',
        'product_free',
        'amount',
        'discount',
        'discount_type',
        'is_active',
        'is_delete'   
    ];

    public function sale()
    {
        return $this->belongsTo('App\Models\Sale', 'sale_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }
}
