<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class StoreStaffRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->user_type == 'admin' || in_array('14', json_decode(Auth::user()->staff->role->permissions));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=> [
                'required',
                'max:50',
                'min:3'
            ],
            'email'=> [
                'required',
                'min:3',
                'max:50',
                'email',
                'unique:users,email'
            ],
            'password'=> [
                'required',
                'min:6',
                'confirmed'
            ],
            'phone' => [
                'required',
                'regex:/(01)[0-9]{9}/',
                'size:11'
            ],
            'address' => [
                'required'
            ],
            'role_id' => [
                'required'
            ]
        ];
    }
}
