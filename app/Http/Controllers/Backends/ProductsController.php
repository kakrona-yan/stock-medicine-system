<?php

namespace App\Http\Controllers\Backends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;

class ProductsController extends Controller
{
    public function __construct(
        Category $category,
        Product $product,
        ProductImage $productImage
    ){
        $this->category = $category;
        $this->product = $product;
        $this->productImage = $productImage;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $products = $this->product->filter($request);
            $categories = $this->category->getCategoryNameByProducts();
            return view('backends.products.index', [
                'request' => $request,
                'products' => $products,
                'categories' => $categories
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.products.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $categories = $this->category->getCategoryNameByProducts();
            return view('backends.products.create', [
                'request' => $request,
                'categories' => $categories
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.products.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductCreateRequest $request)
    {
        try {
            $product = $request->all();
            $product['slug'] = strSlug($product['title']);
            $product['thumbnail'] = isset($product['thumbnail']) ? uploadFile($product['thumbnail'], config('upload.product')) : '';
            // $product['promotion_banner'] = isset($product['promotion_banner']) ? uploadFile($product['promotion_banner'], config('upload.promotion_banner')) : '';
            $productStore = $this->product->create($product);
           
            if ($productStore) {
                // store table product_images
                if (isset($product['product_gallery']) && !empty($product['product_gallery'])) {
                    foreach ($product['product_gallery'] as $productGallery) {
                        if (isBase64($productGallery)) {
                            if (checkImageSize($productGallery)) {
                                return redirect()->route('product.index')
                                    ->with('maxSizeImage', __('validation.max'));
                            }
                            $this->productImage->create([
                                'product_id' => $productStore->id,
                                'thumbnail' => uploadImage($productGallery, config('upload.product_gallery'))
                            ]);
                        }
                    }
                }
            }
            return \Redirect::route('product.index')
                ->with('success', __('flash.store'));
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.products.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        try {
            $product = $this->product->available($id);
            if (!$product) {
                return response()->view('errors.404', [], 404);
            }
            return view('backends.products.show', [
                'product' => $product,
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.products.show');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, int $id)
    {
        try {
            $product = $this->product->available($id);
            $categories = $this->category->getCategoryNameByProducts();
            if (!$product) {
                return response()->view('errors.404', [], 404);
            }
            return view('backends.products.edit', [
                'product' => $product,
                'request' =>  $request,
                'categories'  => $categories
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.products.edit');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductUpdateRequest $request, $id)
    {
        try {
            $requestProduct = $request->all();
            $product = $this->product->available($id);
            if (!$product) {
                return response()->view('errors.404', [], 404);
            }
            $requestProduct['slug'] = strSlug($requestProduct['title']);
            // check empty thumbnail
            if (!empty($request->thumbnail)) {
                $requestProduct['thumbnail'] = uploadFile($requestProduct['thumbnail'], config('upload.product'));
            } else {
                unset($requestProduct['thumbnail']);
            }
            // check empty promotion banner
            if (!empty($request->promotion_banner)) {
                $requestProduct['promotion_banner'] = uploadFile($request->promotion_banner, config('upload.promotion_banner'));
            } else {
                unset($requestProduct['promotion_banner']);
            }
            $product->update($requestProduct);
            if ($product) {
                // update and delete table product_gallery
                if (isset($requestProduct['product_gallery_id'])) {
                    $productIds = preg_split("/,/", $requestProduct['product_gallery_id']);
                    foreach ($productIds as $key => $productId) {
                        $productImage = $this->productImage->where('product_id', $product->id)
                            ->where('id', $productId)
                            ->first();
                        if ($productImage) {
                            $productImage->delete();
                            deleteFile($productImage->thumbnail, config('upload.product_gallery'));
                        }
                    }
                }
                // add new table product_gallery
                if (isset($requestProduct['product_gallery']) && !empty($requestProduct['product_gallery'])) {
                    foreach ($requestProduct['product_gallery'] as $productGallery) {
                        if (isBase64($productGallery)) {
                            if (checkImageSize($productGallery)) {
                                return redirect()->route('product.monthly')
                                    ->with('maxSizeImage', __('validation.max'));
                            }
                            $this->productImage->create([
                                'product_id' => $product->id,
                                'thumbnail' => uploadImage($productGallery, config('upload.product_gallery'))
                            ]);
                        }
                    }
                }
            }
            
            return \Redirect::route('product.index')
                ->with('warning', __('flash.update'));
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.products.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            $id = $request->product_id;
            $product = $this->product->available($id);
            if (!$product) {
                return response()->view('errors.404', [], 404);
            }
            $product->remove();
            return redirect()->route('product.index')
                ->with('danger', __('flash.destroy'));
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.products.index');
        }
    }
}
