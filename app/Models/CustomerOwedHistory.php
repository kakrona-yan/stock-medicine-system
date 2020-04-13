<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerOwedHistory extends Model
{
    protected $table = 'customer_owed_histories';
    protected $fillable = [
        'customer_owed_id',
        'receive_amount',
        'recipient'
    ];
}
