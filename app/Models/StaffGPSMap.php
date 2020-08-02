<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffGPSMap extends Model
{
    
    protected $table = 'staff_gps_maps';

    protected $fillable = [
        'customer_id',
        'staff_id',
        'latitude',
        'longitude',
        'start_date_place',
        'end_date_place',
        'created_at',
        'updated_at',
        'staff_latitude',
        'staff_longitude'
    ];
    
    protected $dates = [
        'start_date_place',
        'end_date_place'
    ];

    public function staff()
    {
        return $this->belongsTo('App\Models\Staff', 'staff_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }
}
