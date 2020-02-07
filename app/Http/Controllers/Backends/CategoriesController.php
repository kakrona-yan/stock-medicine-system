<?php

namespace App\Http\Controllers\Backends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Constants\CategoryType;
use App\Http\Requests\CategoryCreateRequest;
use App\Http\Requests\CategoryUpdateRequest;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    public function __construct(Category $category)
    {
        $this->category = $category;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $categories = $this->category->filter($request);
            $categoryType = CategoryType::CATEGORY_TYPE_TEXT;
            $categoryNames = $this->category->getCategoryName();
            return view('backends.categories.index', [
                'request' => $request,
                'categories' => $categories,
                'categoryType' => $categoryType,
                'categoryNames' => $categoryNames
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.categories.index');
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
            $categoryType = CategoryType::CATEGORY_TYPE_TEXT;
            $categoryNames = $this->category->getCategoryName();
            return view('backends.categories.create', [
                'request' => $request,
                'categoryType' => $categoryType,
                'categoryNames' => $categoryNames
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.categories.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryCreateRequest $request)
    {
        try {
            $category = $request->all();
            $category['slug'] = Str::slug($category['name'], '-');
            $this->category->create($category);
            return \Redirect::route('category.index')
                ->with('success', __('flash.store'));
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.categories.index');
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
            $category = $this->category->available($id);
            if (!$category) {
                return response()->view('errors.404', [], 404);
            }
            return view('backends.categories.show', [
                'category' => $category,
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.categories.show');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        try {
            $category = $this->category->available($id);
            if (!$category) {
                return response()->view('errors.404', [], 404);
            }
            $categoryType = CategoryType::CATEGORY_TYPE_TEXT;
            $categoryNames = $this->category->getCategoryName();
            return view('backends.categories.edit', [
                'category' => $category,
                'categoryType' => $categoryType,
                'categoryNames' => $categoryNames

            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.categories.edit');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryUpdateRequest $request, $id)
    {
        try {
            $categoryRequest = $request->all();
            $categoryRequest['slug'] = Str::slug($categoryRequest['name'], '-');
            $category = $this->category->available($id);
            if (!$category) {
                return response()->view('errors.404', [], 404);
            }
            $category->update($categoryRequest);

            return \Redirect::route('category.index')
                ->with('warning', __('flash.update'));
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.categories.index');
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
            $id = $request->category_id;
            $category = $this->category->available($id);
            if (!$category) {
                return response()->view('errors.404', [], 404);
            }
            $category->remove();
            return redirect()->route('category.index')
                ->with('danger', __('flash.destroy'));
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.categories.index');
        }
    }
}
