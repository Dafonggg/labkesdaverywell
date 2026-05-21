import React from 'react';
import { Clock, CheckCircle2, ShieldAlert } from 'lucide-react';
import { toast } from 'sonner';
import { usePaymentList } from '@/hooks/usePayment';
import dayjs from 'dayjs';

const Pembayaran: React.FC = () => {
  const { data: response, isLoading } = usePaymentList();
  const payments = response?.data || [];

  // Calculate summary stats from real data
  const totalLunas = payments.filter(p => p.status === 'paid').reduce((sum, p) => sum + p.jumlah, 0);
  const totalPending = payments.filter(p => p.status === 'pending').reduce((sum, p) => sum + p.jumlah, 0);
  const totalOverdue = payments.filter(p => p.status === 'overdue').reduce((sum, p) => sum + p.jumlah, 0);

  const formatCurrency = (val: number) => `Rp ${val.toLocaleString('id-ID')}`;

  const STATUS_MAP: Record<string, { label: string; style: string }> = {
    paid: { label: 'Lunas', style: 'bg-status-success/10 text-status-success' },
    pending: { label: 'Belum Bayar', style: 'bg-status-warning/10 text-status-warning' },
    overdue: { label: 'Jatuh Tempo', style: 'bg-status-danger/10 text-status-danger' },
  };

  return (
    <div className="space-y-6">
      <div className="border-b border-outline-variant pb-4">
        <h1 className="font-headline-lg text-lg md:text-xl font-extrabold text-on-surface">
          Keuangan & Verifikasi Pembayaran
        </h1>
        <p className="font-body-md text-xs text-on-surface-variant mt-1 font-medium">
          Pantau billing invoice, verifikasi bukti pembayaran, dan kelola administrasi keuangan pengujian.
        </p>
      </div>

      {/* KPI summaries */}
      <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div className="bg-white p-5 rounded-xl border border-outline-variant soft-shadow hover-lift flex items-center gap-4">
          <div className="w-12 h-12 bg-status-success/10 text-status-success rounded-xl flex items-center justify-center">
            <CheckCircle2 size={24} />
          </div>
          <div>
            <div className="text-[10px] text-on-surface-variant font-bold uppercase tracking-wider">Total Pendapatan</div>
            <div className="font-bold text-sm text-on-surface mt-0.5">{isLoading ? '...' : formatCurrency(totalLunas)}</div>
          </div>
        </div>
        <div className="bg-white p-5 rounded-xl border border-outline-variant soft-shadow hover-lift flex items-center gap-4">
          <div className="w-12 h-12 bg-status-warning/10 text-status-warning rounded-xl flex items-center justify-center">
            <Clock size={24} />
          </div>
          <div>
            <div className="text-[10px] text-on-surface-variant font-bold uppercase tracking-wider">Piutang Pending</div>
            <div className="font-bold text-sm text-on-surface mt-0.5">{isLoading ? '...' : formatCurrency(totalPending)}</div>
          </div>
        </div>
        <div className="bg-white p-5 rounded-xl border border-outline-variant soft-shadow hover-lift flex items-center gap-4">
          <div className="w-12 h-12 bg-status-danger/10 text-status-danger rounded-xl flex items-center justify-center">
            <ShieldAlert size={24} />
          </div>
          <div>
            <div className="text-[10px] text-on-surface-variant font-bold uppercase tracking-wider">Terlambat Bayar</div>
            <div className="font-bold text-sm text-on-surface mt-0.5">{isLoading ? '...' : formatCurrency(totalOverdue)}</div>
          </div>
        </div>
      </div>

      {/* Invoice list table */}
      <div className="bg-white rounded-xl border border-outline-variant overflow-hidden soft-shadow">
        <div className="p-4 border-b border-outline-variant bg-surface-container-low flex justify-between items-center">
          <h3 className="font-headline-md text-xs font-bold text-on-surface uppercase tracking-wider">
            Daftar Invoice Penagihan
          </h3>
          <button 
            onClick={() => toast.success('Membuka buat invoice...')}
            className="bg-primary text-on-primary font-label-md text-[10px] font-bold px-3 py-1.5 rounded hover:bg-primary-container transition-all cursor-pointer soft-shadow"
          >
            Buat Invoice Baru
          </button>
        </div>
        <div className="overflow-x-auto">
          <table className="w-full text-left">
            <thead>
              <tr className="bg-surface-container border-b border-outline-variant text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">
                <th className="p-4">No. Invoice</th>
                <th className="p-4">Metode Bayar</th>
                <th className="p-4">Total Biaya</th>
                <th className="p-4">Tanggal</th>
                <th className="p-4">Status</th>
                <th className="p-4 text-center">Aksi</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-outline-variant text-xs text-on-surface">
              {isLoading ? (
                Array.from({ length: 4 }).map((_, idx) => (
                  <tr key={idx} className="animate-pulse">
                    <td className="p-4"><div className="h-3 bg-gray-200 rounded w-24" /></td>
                    <td className="p-4"><div className="h-3 bg-gray-200 rounded w-20" /></td>
                    <td className="p-4"><div className="h-3 bg-gray-200 rounded w-24" /></td>
                    <td className="p-4"><div className="h-3 bg-gray-200 rounded w-20" /></td>
                    <td className="p-4"><div className="h-3 bg-gray-200 rounded w-16" /></td>
                    <td className="p-4"><div className="h-3 bg-gray-200 rounded w-16 mx-auto" /></td>
                  </tr>
                ))
              ) : payments.length === 0 ? (
                <tr>
                  <td colSpan={6} className="p-12 text-center text-on-surface-variant font-semibold">
                    Belum ada data pembayaran.
                  </td>
                </tr>
              ) : (
                payments.map((inv) => {
                  const statusInfo = STATUS_MAP[inv.status] || { label: inv.status, style: 'bg-gray-200 text-gray-700' };
                  return (
                    <tr key={inv.id} className="hover:bg-surface-container-low transition-all">
                      <td className="p-4 font-bold text-primary">{inv.id.substring(0, 12)}</td>
                      <td className="p-4 font-semibold capitalize">{inv.metode_pembayaran}</td>
                      <td className="p-4 font-bold">{formatCurrency(inv.jumlah)}</td>
                      <td className="p-4 text-on-surface-variant">{dayjs(inv.created_at).format('YYYY-MM-DD')}</td>
                      <td className="p-4">
                        <span className={`px-2.5 py-0.5 rounded-full font-bold text-[10px] ${statusInfo.style}`}>
                          {statusInfo.label}
                        </span>
                      </td>
                      <td className="p-4 text-center">
                        <button 
                          onClick={() => toast.info(`Detail pembayaran ${inv.id.substring(0, 8)}`)}
                          className="px-2.5 py-1.5 bg-white border border-outline-variant rounded font-semibold text-[10px] hover:bg-surface-container text-on-surface transition-all cursor-pointer"
                        >
                          Kuitansi / Detail
                        </button>
                      </td>
                    </tr>
                  );
                })
              )}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
};

export default Pembayaran;
