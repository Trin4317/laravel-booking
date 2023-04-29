<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'             => 'required',
            'city_id'          => 'required|exists:cities,id',
            'address_street'   => 'required',
            'address_postcode' => '',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required'           => 'A name for the property is required',
            'city_id.required'        => 'A city is required to add this property',
            'city_id.exists'          => 'City is not found in database',
            'address_street.required' => 'An address is required to add this property',
        ];
    }
}
