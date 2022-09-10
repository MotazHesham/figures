<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class OrderRequest extends FormRequest
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
            'client_name'=> [
                'required',
                'max:255'
            ],
            'phone_number'=> [
                'required',
                'regex:/(01)[0-9]{9}/',
                'size:11'
            ],
            'phone_number2'=> [
                'nullable',
                'regex:/(01)[0-9]{9}/',
                'size:11'
            ],
            'shipping_country'=> [
                'required',
                'integer'
            ],
            'shipping_address'=> [
                'nullable'
            ],
            'deposit'=> [
                'required',
                'max:255'
            ],
            'deposit_amount'=> [
                'required'
            ],
            'free_shipping_reason'=> [
                'nullable'
            ],
            'total_cost_by_seller'=> [
                'required'
            ],
            'quntity'=> [
                'required',
                'integer'
            ],
            'photos.*'=> [
                'nullable',
                'mimes:jpeg,png,jpg,ico',
                'max:2048'
            ],
            'pdf'=> [
                'nullable',
                'mimes:pdf',
                'max:2048'
            ],
        ];
    }
}
