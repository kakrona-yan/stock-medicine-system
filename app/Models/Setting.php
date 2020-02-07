<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'logo',
        'copy_right', 
        'sns_gmail',
        'sns_facebook',
        'sns_instagram',
        'sns_twitter',
        'phone',
        'map',
        'is_active',
        'is_delete'
    ];
}
