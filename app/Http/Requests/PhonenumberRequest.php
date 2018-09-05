<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhonenumberRequest extends FormRequest
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
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'phonenumber.phone' => 'Phonenumber is not valid.'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phonenumber' => 'phone:AUTO,PH|required|unique:phonenumbers,value,' . $this->id
        ];
    }
}
