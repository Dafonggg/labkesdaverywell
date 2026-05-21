import React from 'react';
import { History, CheckCircle, XCircle } from 'lucide-react';
import { useQcHistory } from '@/hooks/useQc';
import dayjs from 'dayjs';

const QcHistory: React.FC = () => {
  const { data: response, isLoading } = useQcHistory();
  const historyItems = response?.data || [];

  return (
    <div className="space-y-6">
      <div className="border-b border-outline-variant pb-4">
        <h1 className="font-headline-lg text-lg md:text-xl font-extrabold text-on-surface flex items-center gap-2">
          <History className="text-primary" />
          Riwayat Verifikasi QC
        </h1>
        <p className="font-body-md text-xs text-on-surface-variant mt-1 font-medium">
          Catatan seluruh aktivitas verifikasi mutu laboratorium ISO 17025.
        </p>
      </div>

      <div className="bg-white rounded-xl border border-outline-variant overflow-hidden soft-shadow">
        <div className="overflow-x-auto">
          <table className="w-full text-left">
            <thead>
              <tr className="bg-surface-container border-b border-outline-variant text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">
                <th className="p-4">ID</th>
                <th className="p-4">Status</th>
                <th className="p-4">Catatan</th>
                <th className="p-4">Tanggal Verifikasi</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-outline-variant text-xs text-on-surface font-medium">
              {isLoading ? (
                Array.from({ length: 5 }).map((_, idx) => (
                  <tr key={idx} className="animate-pulse">
                    <td className="p-4"><div className="h-3 bg-gray-200 rounded w-24" /></td>
                    <td className="p-4"><div className="h-3 bg-gray-200 rounded w-20" /></td>
                    <td className="p-4"><div className="h-3 bg-gray-200 rounded w-36" /></td>
                    <td className="p-4"><div className="h-3 bg-gray-200 rounded w-28" /></td>
                  </tr>
                ))
              ) : historyItems.length === 0 ? (
                <tr>
                  <td colSpan={4} className="p-12 text-center text-on-surface-variant font-semibold">
                    Belum ada riwayat verifikasi QC.
                  </td>
                </tr>
              ) : (
                historyItems.map((item) => (
                  <tr key={item.id} className="hover:bg-surface-container-low transition-all">
                    <td className="p-4 font-bold text-primary">{item.id.substring(0, 12)}</td>
                    <td className="p-4">
                      <span className={`inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full font-bold text-[10px] ${
                        item.status === 'approved' 
                          ? 'bg-status-success/10 text-status-success' 
                          : 'bg-status-danger/10 text-status-danger'
                      }`}>
                        {item.status === 'approved' ? <CheckCircle size={12} /> : <XCircle size={12} />}
                        {item.status === 'approved' ? 'Disetujui' : 'Ditolak'}
                      </span>
                    </td>
                    <td className="p-4 text-on-surface-variant">{item.catatan || '-'}</td>
                    <td className="p-4 text-on-surface-variant">{dayjs(item.verified_at || item.created_at).format('YYYY-MM-DD HH:mm')}</td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
};

export default QcHistory;
