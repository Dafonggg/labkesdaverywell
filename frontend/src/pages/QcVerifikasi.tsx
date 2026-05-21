import React from 'react';
import { AlertTriangle, Check, X, RefreshCcw } from 'lucide-react';
import { usePendingQc } from '@/hooks/usePengujian';
import { useApproveQc, useRejectQc } from '@/hooks/useQc';

const QcVerifikasi: React.FC = () => {
  const { data: response, isLoading } = usePendingQc();
  const approveMutation = useApproveQc();
  const rejectMutation = useRejectQc();

  const pendingItems = response?.data || [];

  const handleApprove = (hasilUjiId: string) => {
    approveMutation.mutate({ hasil_uji_id: hasilUjiId, catatan: 'Disetujui' });
  };

  const handleReject = (hasilUjiId: string) => {
    rejectMutation.mutate({ hasil_uji_id: hasilUjiId, catatan: 'Nilai tidak valid' });
  };

  return (
    <div className="space-y-6">
      <div className="border-b border-outline-variant pb-4">
        <h1 className="font-headline-lg text-lg md:text-xl font-extrabold text-on-surface">
          Verifikasi Mutu & QC Laboratorium
        </h1>
        <p className="font-body-md text-xs text-on-surface-variant mt-1 font-medium">
          Tinjau hasil pengujian laboratorium yang melampaui baku mutu. Lakukan verifikasi untuk rilis LHP.
        </p>
      </div>

      <div className="space-y-4">
        {isLoading ? (
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            {[1, 2].map((idx) => (
              <div key={idx} className="bg-white p-6 rounded-xl border border-outline-variant soft-shadow animate-pulse space-y-4">
                <div className="h-4 bg-gray-200 rounded w-32" />
                <div className="h-3 bg-gray-200 rounded w-48" />
                <div className="h-20 bg-gray-200 rounded" />
                <div className="h-8 bg-gray-200 rounded" />
              </div>
            ))}
          </div>
        ) : pendingItems.length > 0 ? (
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            {pendingItems.map((item) => (
              <div key={item.id} className="bg-white p-6 rounded-xl border border-outline-variant soft-shadow space-y-4 hover-lift">
                <div className="flex justify-between items-start border-b border-outline-variant pb-3">
                  <div>
                    <span className="font-bold text-xs text-primary">{item.id.substring(0, 12)}</span>
                    <h4 className="font-bold text-xs text-on-surface mt-0.5">
                      {(item.parameter as Record<string, string>)?.nama_parameter || 'Parameter Uji'}
                    </h4>
                  </div>
                  <span className="bg-status-danger/10 text-status-danger px-2.5 py-0.5 rounded font-bold text-[10px] uppercase flex items-center gap-1">
                    <AlertTriangle size={12} />
                    Perlu Verifikasi
                  </span>
                </div>

                <div className="space-y-3.5 text-xs text-on-surface">
                  <div className="bg-status-danger/5 p-3 rounded-lg border border-status-danger/10 space-y-1">
                    <span className="block font-bold text-status-danger uppercase text-[10px] tracking-wider">Hasil Uji</span>
                    <div className="flex justify-between items-center text-[11px] font-bold text-on-surface mt-1.5">
                      <span>Nilai: <span className="text-status-danger">{item.nilai_hasil}</span></span>
                      <span className="text-on-surface-variant">Metode: {item.metode_pengujian}</span>
                    </div>
                  </div>

                  {item.catatan && (
                    <div className="space-y-1 border-t border-outline-variant pt-3.5">
                      <span className="block text-on-surface-variant font-bold text-[10px] uppercase">Catatan</span>
                      <p className="text-[11px] text-on-surface-variant leading-relaxed italic">"{item.catatan}"</p>
                    </div>
                  )}
                </div>

                {/* Action Buttons */}
                <div className="flex items-center gap-2 border-t border-outline-variant pt-4">
                  <button
                    onClick={() => handleReject(item.id)}
                    disabled={rejectMutation.isPending}
                    className="flex-1 py-2 bg-white border border-outline-variant text-on-surface font-semibold text-[10px] hover:bg-surface-container rounded-lg transition-all cursor-pointer soft-shadow flex items-center justify-center gap-1"
                  >
                    <RefreshCcw size={12} />
                    Uji Ulang (Retest)
                  </button>
                  <button
                    onClick={() => handleReject(item.id)}
                    disabled={rejectMutation.isPending}
                    className="py-2 px-3 bg-status-danger/10 text-status-danger font-semibold text-[10px] hover:bg-status-danger/20 rounded-lg transition-all cursor-pointer flex items-center justify-center gap-1"
                  >
                    <X size={12} />
                    Tolak
                  </button>
                  <button
                    onClick={() => handleApprove(item.id)}
                    disabled={approveMutation.isPending}
                    className="flex-1 py-2 bg-primary text-on-primary font-bold text-[10px] hover:bg-primary-container rounded-lg transition-all cursor-pointer soft-shadow flex items-center justify-center gap-1"
                  >
                    <Check size={12} />
                    Verifikasi & Setujui
                  </button>
                </div>
              </div>
            ))}
          </div>
        ) : (
          <div className="bg-white p-12 text-center rounded-xl border border-outline-variant soft-shadow text-on-surface-variant text-xs font-semibold">
            Semua parameter uji lab berada dalam batas aman. Antrean QC kosong.
          </div>
        )}
      </div>
    </div>
  );
};

export default QcVerifikasi;
