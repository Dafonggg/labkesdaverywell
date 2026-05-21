<?php

namespace App\Http\Requests\Mobile;

use Illuminate\Foundation\Http\FormRequest;

class UploadSampleImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'sample_id' => ['required', 'uuid', 'exists:samples,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'image.required' => 'File gambar wajib diupload',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus JPG atau PNG',
            'image.max' => 'Ukuran gambar maksimal 5MB',
            'sample_id.required' => 'ID sample wajib diisi',
            'sample_id.exists' => 'Sample tidak ditemukan',
        ];
    }
}
