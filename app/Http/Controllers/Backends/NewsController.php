<?php

namespace App\Http\Controllers\Backends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Category;
class NewsController extends Controller
{
    public function __construct(
        News $news,
        Category $category
    ){
        $this->news = $news;
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
            $news = $this->news->filter($request);
            $categories = $this->category->getCategoryNameByNews();
            return view('backends.news.index', [
                'request' => $request,
                'news' => $news,
                'categories' => $categories
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.news.index');
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
            $categories = $this->category->getCategoryNameByNews();
            return view('backends.news.create', [
                'request' => $request,
                'categories' => $categories
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.news.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // Rules of field
            $rules = [
                'title'        => 'required|string|max:255',
                'category_id'        => 'required',
                'thumbnail'         => 'nullable|mimes:jpeg,jpg,png|max:10240',
            ];
            // Set field of Validattion
            $validator = \Validator::make([
                'title'  => $request->title,
                'thumbnail' => $request->thumbnail,
                'category_id' => $request->category_id
            ], $rules);
            // Check validation
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $news = $request->all();
                $news['permalink'] = strSlug($news['title']);
                $news['thumbnail'] = isset($news['thumbnail']) ? uploadFile($news['thumbnail'], config('upload.news')) : '';
                $news['author'] = \Auth::user()->name;
                $this->news->create($news);
                return \Redirect::route('news.index')
                    ->with('success', __('flash.store'));
            }
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.news.index');
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
            $news = $this->news->available($id);
            if (!$news) {
                return response()->view('errors.404', [], 404);
            }
            return view('backends.news.show', [
                'news' => $news,
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.news.show');
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
            $news = $this->news->available($id);
            $categories = $this->category->getCategoryNameByNews();
            if (!$news) {
                return response()->view('errors.404', [], 404);
            }
            return view('backends.news.edit', [
                'news' => $news,
                'request' =>  $request,
                'categories'  => $categories
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.news.edit');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            // Rules of field
            $rules = [
                'title'        => 'required|string|max:255',
                'category_id'        => 'required',
                'thumbnail'         => 'nullable|mimes:jpeg,jpg,png|max:10240',
            ];
            // Set field of Validattion
            $validator = \Validator::make([
                'title'  => $request->title,
                'thumbnail' => $request->thumbnail,
                'category_id' => $request->category_id
            ], $rules);
            // Check validation
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $requestNews = $request->all();
                $news = $this->news->available($id);
                if (!$news) {
                    return response()->view('errors.404', [], 404);
                }
                $requestNews['permalink'] = strSlug($requestNews['title']);
                // check empty thumbnail
                if (!empty($request->thumbnail)) {
                    $requestNews['thumbnail'] = uploadFile($requestNews['thumbnail'], config('upload.news'));
                } else {
                    unset($requestNews['thumbnail']);
                }
                $requestNews['author'] = \Auth::user()->name;
                $news->update($requestNews);

                return \Redirect::route('news.index')
                    ->with('warning', __('flash.update'));
            }
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.news.index');
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
            $id = $request->news_id;
            $news = $this->news->available($id);
            if (!$news) {
                return response()->view('errors.404', [], 404);
            }
            $news->remove();
            return redirect()->route('news.index')
                ->with('danger', __('flash.destroy'));
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.news.index');
        }
    }
}
