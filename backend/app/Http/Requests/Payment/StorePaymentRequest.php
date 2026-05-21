<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
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
            'permohonan_id' => ['required', 'uuid', 'exists:permohonan_pengujian,id'],
            'jumlah' => ['required', 'numeric', 'min:0'],
            'metode_pembayaran' => ['required', 'string', 'in:cash,transfer,qris'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'permohonan_id.required' => 'ID permohonan wajib diisi',
            'permohonan_id.exists' => 'Permohonan tidak ditemukan',
            'jumlah.required' => 'Jumlah pembayaran wajib diisi',
            'jumlah.min' => 'Jumlah pembayaran tidak boleh negatif',
            'metode_pembayaran.required' => 'Metode pembayaran wajib diisi',
            'metode_pembayaran.in' => 'Metode pembayaran harus cash, transfer, atau qris',
        ];
    }
}
