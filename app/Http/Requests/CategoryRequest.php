<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->user_type == 'admin' || in_array('2', json_decode(Auth::user()->staff->role->permissions));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'max:50',
            ],
            'banner' => [
                'nullable',
                'mimes:jpeg,png,jpg'
            ],
            'icon' => [
                'nullable',
                'mimes:jpeg,png,jpg,ico',
                'max:2048'
            ],
            'slug' => [
                'nullable',
                'max:255'
            ],
            'meta_title' => [
                'nullable',
                'max:255'
            ],
            'meta_description' => [
                'nullable',
            ],
        ];
    }
}
