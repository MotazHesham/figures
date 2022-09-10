<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class ReceiptOutgoingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->user_type == 'admin' || in_array('6', json_decode(Auth::user()->staff->role->permissions));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'client_name'=> [
                'nullable',
                'max:255'
            ],
            'phone'=> [
                'nullable',
                'regex:/(01)[0-9]{9}/',
                'size:11'
            ],
            'note' => [
                'nullable',
                'max:1000'
            ],
        ];
    }
}
