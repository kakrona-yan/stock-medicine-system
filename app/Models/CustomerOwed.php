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
        'status_pay',
        'date_pay',
        'amount_pay',
        'discount_type',
        'discount_amount'

    ];
    
    protected $dates = [
        'receive_date',
        'date_pay'
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
        $price = $this->exists() ? $this->{$column} : 0;
        return currencyFormat($price);
    }

    // status pay
    const STATUS_NOT_PAY = 0;
    const STATUS_SOME_PAY = 1;
    const STATUS_ALL_PAY = 2;

    const STATUS_PAY_TEXT = [
        '0' => 'មិនទាន់សង',
        '1' => 'សងបានខ្លះ',
        '2' => 'សងហើយ',
    ];

    const STATUS_PAY_TEXT_FORM = [
        '1' => 'សងបានខ្លះ',
        '2' => 'សងហើយ',
    ];

    const STATUS_PAY_ALL_TEXT = [
        '2' => 'សងហើយ',
    ];

    const DICOUNT_TYPE_TEXT = [
        '0' => 'ជាភាគរយ(%)',
        '1' => 'ជាប្រាក់'
    ];

    public function statusPay()
    {
        $statusPay = $this->status_pay;
        $statusText = '';
        $color = '#e74a3b';
        if (is_null($statusPay) && empty($statusPay)) return [
            'statusText' => self::STATUS_PAY_TEXT[0],
            'color' => $color
        ];
        switch ($statusPay) {
            case 0:
                $statusText = self::STATUS_PAY_TEXT[$statusPay];
                $color = '#e74a3b';
                break;
            case 1:
                $statusText = self::STATUS_PAY_TEXT[$statusPay];
                $color = '#00a3df';
                break;
            case 2:
                $statusText = self::STATUS_PAY_TEXT[$statusPay];
                $color = '#1cc88a';
                break;
        }
        return [
            'statusText' => $statusText, 
            'color' => $color
        ];
    }
}
