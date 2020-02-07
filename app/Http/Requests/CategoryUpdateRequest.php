<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name' => 'required|max:100|unique:categories,name,'.$this->id,
            'category_type' => 'required',
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
            'name.required' => __('validation.required'),
            'name.max' => __('validation.max'),
            'name.unique' => __('validation.unique'),
            'category_type.required' => __('validation.required')
        ];
    }
}
