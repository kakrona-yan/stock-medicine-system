<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use App\Http\Constants\DeleteStatus;

abstract class BaseModel extends Model
{
    /**
     * constructor
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
    /**
     * get formatted date
     * @return string
     */
    public function getFormattedDate($column, $format = 'Y-m-d')
    {
        if (isset($this->{$column}) && strtotime($this->{$column}) < 0) {
            return ' - ';
        }
        return ($this->{$column}) ? $this->{$column}->format($format) : ' - ';
    }

    /**
     * get formatted Time
     * @return string
     */
    public function getFormattedTime($column, $format = 'H:i:s')
    {
        if (isset($this->{$column}) && strtotime($this->{$column}) < 0) {
            return ' - ';
        }
        return ($this->{$column}) ? $this->{$column}->format($format) : ' - ';
    }

    public function scopeAvailable($query, $id)
    {
        return $query->where('id',  $id)
            ->where('is_delete', '<>', DeleteStatus::DELETED)
            ->first();
    }

    public function scopeRemove()
    {
        return $this->update([
            'is_delete' => DeleteStatus::DELETED
        ]);
    }

    /**
     * Auto increment uninque id string
     * @param $model
     * @return string
     * */
    public function scopeIncrementStringUniqueInvoiceCode($model, $num = 1)
    {
        $lastest =  $model->select('id')
            ->orderBy('id', 'DESC')->first();
        $digits = $lastest ? $lastest->id + $num : $num;
        // return date('d/m') ."/A".$digits;
        // return date('d/m') ."/B".$digits;
        return date('d/m') ."/C".$digits;
    }
}
