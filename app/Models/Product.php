<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Constants\DeleteStatus;
use App\Models\GroupStaff;
class Product extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'group_staff_id',
        'title',
        'slug',
        'product_code', 
        'product_import',
        'price',
        'price_discount',
        'product_free',
        'thumbnail',
        'promotion_banner',
        'description',
        'in_store',
        'out_store',
        'is_active',
        'is_delete',
        'terms',
        'expird_date',
        'amount_in_box',
        'note'
    ];

    protected $dates = [
        'expird_date',
    ];

    public function productImages()
    {
        return $this->hasMany('App\Models\ProductImage', 'product_id', 'id');
    }
    
    /**
     * The product that belong to the category
     * From table user
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function groupStaff()
    {
        return $this->belongsTo('App\Models\GroupStaff', 'group_staff_id');
    }
    
    public function filter($request)
    {
        $products = $this->where('is_delete', '<>', DeleteStatus::DELETED)
            ->orderBy('title', 'ASC');
        // Check flash danger
        flashDanger($products->count(), __('flash.empty_data'));
        $limit = config('pagination.limit');
        if ($request->exists('limit') && !is_null($request->limit)) {
            $limit = $request->limit;
        }
        if ($request->exists('title') && !empty($request->title)) {
            $products->where('title', 'like', '%' . $request->title . '%');
        }
        if ($request->exists('terms') && !empty($request->terms)) {
            $products->where('terms', 'like', '%' . $request->terms . '%');
        }
        return $products->paginate($limit, ['*'], 'product-page');
    }

    public function getProduct($request)
    {
        $products = $this->where('is_delete', '<>', DeleteStatus::DELETED)->where('is_active', 1);
        if (!empty($request->category)) {
            $products->where('category_id', $request->category);
        }
        return $products->orderBy('id', 'DESC')->paginate(config('pagination.product_limit'));
    }

    public function getProductName()
    {
        return $this->where('is_delete', '<>', DeleteStatus::DELETED)
            ->pluck('title', 'id')
            ->all();
    }

    public function explodeGroupStaffID()
    {
        $str = $this->group_staff_id;
        return explode(',', $str);
    }
    
    // Text group staff
 
    public function listGroupStafff()
    {
        $groupStaffIds = $this->explodeGroupStaffID();
        return GroupStaff::whereIn("id", $groupStaffIds)
            ->get();
    }
}
