<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Constants\DeleteStatus;
use App\Http\Constants\CategoryType;
use App\Models\Product;

class Category extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug', 
        'parent_id',
        'category_type',
        'is_active',
        'is_delete'
    ];

    public static function boot()
    {
        parent::boot();
    }

    public function childs()
    {
        return $this->hasMany('App\Models\Category', 'parent_id');
    }
    
    public function parent()
    {
        return $this->belongsTo('App\Models\Category', 'parent_id');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product', 'category_id');
    }
    
    public function news()
    {
        return $this->hasMany('App\Models\News', 'category_id');
    }

    public function getCategoryName()
    {
        return $this->pluck('name','id')
            ->all();
    }

    public function getCategoryNameByProducts()
    {
        return $this->where('category_type', CategoryType::CATEGORY_TYPE_PRODUCT)
            ->orderBy('name', 'ASC')
            ->pluck('name', 'id')
            ->all();
    }

    public function getCategoryNameByNews()
    {
        return $this->where('category_type', CategoryType::CATEGORY_TYPE_NEWS)
            ->pluck('name', 'id')
            ->all();
    }

    public function filter($request)
    {
        $categories = $this->where('is_delete', '<>', DeleteStatus::DELETED)
            ->orderBy('name', 'ASC');
        // Check flash danger
        flashDanger($categories->count(), __('flash.empty_data'));
        $limit = config('pagination.limit');
        if ($request->exists('limit') && !is_null($request->limit)) {
            $limit = $request->limit;
        }
        return $categories->paginate($limit);
    }

    public function CategoryType()
    {
        $categoryType = $this->category_type;
        $categoryTypeText = '';
        if (is_null($categoryType) && empty($categoryType)) return;
        switch ($categoryType) {
            case 0:
                $categoryTypeText = CategoryType::CATEGORY_TYPE_TEXT[$categoryType];
                break;
            case 1:
                $categoryTypeText = CategoryType::CATEGORY_TYPE_TEXT[$categoryType];
                break;
        }
        return $categoryTypeText;
    }

    public function getCategories() {
        return $this->withCount(['products' => function ($query) {
                $query->where('is_active', 1);
            }])
            ->where('category_type', CategoryType::CATEGORY_TYPE_PRODUCT)
            ->get();
    }

    public function getNewsCategories()
    {
        return $this->withCount(['news' => function ($query) {
                $query->where('is_active', 1);
            }])
            ->where('category_type', CategoryType::CATEGORY_TYPE_NEWS)
            ->get();
    }
    
}
