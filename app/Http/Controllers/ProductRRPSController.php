<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Constants\DeleteStatus;
use App\Models\Category;

class ProductRRPSController extends Controller
{

    public function __construct(
        Product $product,
        Category $category
    ){
        $this->product = $product;
        $this->category = $category;
    }

    public function pageProduct(Request $request)
    {
        try {
            $products = $this->product->where('is_delete', '<>', DeleteStatus::DELETED);
            // Check flash danger
            flashDanger($products->count(), __('flash.empty_data'));
            $limit = config('pagination.limit');
            if ($request->exists('limit') && !is_null($request->limit)) {
                $limit = $request->limit;
            }
            if ($request->exists('title') && !is_null($request->title)) {
                $products->where('title', $request->title);
            }
            if ($request->exists('category_id') && !is_null($request->category_id)) {
                $products->where('category_id', $request->category_id);
            }
            $products = $products->paginate($limit);
            $categories = $this->category->getCategoryNameByProducts();
            return view('frontends.products.index', [
                'request' => $request,
                'products' => $products,
                'categories' => $categories
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'frontends.products.index');
        }
    }
}
