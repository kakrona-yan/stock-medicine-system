<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ProductCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $priceDiscount = $request->get('price_discount');
        $productFree = $request->get('product_free');
        return [
            'title' => 'required|max:200|unique:products,title',
            'category_id' => 'required',
            'price' => 'required|numeric',
            'price_discount' => $priceDiscount && !empty($priceDiscount)? 'numeric' : '',
            'in_store' => 'required|numeric',
            'product_free' => $productFree && !empty($productFree) ? 'numeric' : '',
            'thumbnail' => 'image|mimes:jpeg,png,jpg,gif,svg|max:10240'
        ];
    }

    /**
     * display message with kind of validation
     *
     * @return {string}
     */
    public function messages()
    {
        return [
            'title.required' => __('validation.required'),
            'title.max' => __('validation.max'),
            'title.unique' => __('validation.unique'),
            'category_id.required' => __('validation.required'),
            'price.required' => __('validation.required'),
            'price.numeric' => __('validation.numeric'),
            'price_discount.numeric' => __('validation.numeric'),
            'product_free.numeric' => __('validation.numeric'),
            'in_store.required' => __('validation.numeric'),
            'in_store.numeric' => __('validation.numeric')
        ];
    }
}
