<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UserUpdateRequest extends FormRequest
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
        $password = $request->get('password');
        $password = $password ?  ['max:30', 'min:6','regex:/^[!-~]+$/'] : '';
        return [
            'name' => 'required|max:40',
            // 'email' => 'required|email|max:255|unique:users,email,' .$this->id,
            'password' => $password,
            'role' => 'required',
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
            'name.required' => __('validation.required'),
            'name.max' => __('validation.max'),
            // 'email.required' => __('validation.required'),
            // 'email.unique' => __('validation.unique'),
            // 'email.email' => __('validation.email'),
            'password.required' => __('validation.required'),
            'password.max' => __('validation.max'),
            'password.min' => __('validation.min'),
            'role.required' => __('validation.required'),
            'thumbnail.image' => __('validation.image')
        ];
    }
}
