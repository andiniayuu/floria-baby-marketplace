<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'label' => 'nullable|string|max:255',
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'district' => 'nullable|string|max:255',
            'subdistrict' => 'nullable|string|max:255',
            'postal_code' => 'required|string|regex:/^[0-9]{5}$/',
            'full_address' => 'required|string|min:10',
            'notes' => 'nullable|string|max:500',
            'is_primary' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'recipient_name.required' => 'Nama penerima wajib diisi',
            'phone.required' => 'Nomor telepon wajib diisi',
            'phone.regex' => 'Format nomor telepon tidak valid',
            'phone.min' => 'Nomor telepon minimal 10 digit',
            'province.required' => 'Provinsi wajib diisi',
            'city.required' => 'Kota/Kabupaten wajib diisi',
            'postal_code.required' => 'Kode pos wajib diisi',
            'postal_code.regex' => 'Kode pos harus 5 digit angka',
            'full_address.required' => 'Alamat lengkap wajib diisi',
            'full_address.min' => 'Alamat lengkap minimal 10 karakter',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_primary' => $this->boolean('is_primary'),
        ]);
    }
}