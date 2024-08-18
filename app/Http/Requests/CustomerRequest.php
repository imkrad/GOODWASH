<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:150|unique:customers,email,'.$this->id,
            'contact_no' => 'required|numeric|digits:11',
            'barangay_code' => 'required',
            'address' => 'required',
            'date' => 'required',
            'time' => 'required',
            'type' => 'required',
            'longitude' => 'nullable',
            'latitude' => 'nullable',
        ];
    }
}
