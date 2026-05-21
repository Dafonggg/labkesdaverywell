<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    /**
     * Get all payments with optional filters and pagination.
     */
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $query = Payment::with(['permohonan', 'pencatat'])
            ->orderByDesc('created_at');

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }
    /**
     * Create a payment record with DB transaction.
     */
    public function create(array $data): Payment
    {
        return DB::transaction(function () use ($data) {
            $payment = Payment::create([
                'permohonan_id' => $data['permohonan_id'],
                'metode_pembayaran' => $data['metode_pembayaran'],
                'jumlah' => $data['jumlah'],
                'status' => PaymentStatus::PAID->value,
                'tanggal_bayar' => now(),
                'dicatat_oleh' => Auth::id(),
            ]);

            // Update the total_biaya on the permohonan
            $permohonan = $payment->permohonan;
            $permohonan->update([
                'total_biaya' => $permohonan->payments()->sum('jumlah'),
            ]);

            $payment->load('pencatat');

            return $payment;
        });
    }
}
