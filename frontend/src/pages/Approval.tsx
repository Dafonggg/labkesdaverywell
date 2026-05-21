import React from 'react';
import { Signature, Check } from 'lucide-react';
import { useDrafts } from '@/hooks/useLaporan';
import { useApproveFinal } from '@/hooks/useApproval';

const Approval: React.FC = () => {
  const { data: response, isLoading } = useDrafts();
  const approveMutation = useApproveFinal();

  // Filter drafts that are ready for approval
  const pendingApprovals = (response?.data || []).filter(d => d.status === 'pending_approval' || d.status === 'review');

  const handleApprove = (laporanId: string) => {
    approveMutation.mutate({ laporan_id: laporanId, catatan: 'Disetujui' });
  };

  return (
    <div className="space-y-6">
      <div className="border-b border-outline-variant pb-4">
        <h1 className="font-headline-lg text-lg md:text-xl font-extrabold text-on-surface">
          Otorisasi & Tanda Tangan Kepala UPTD
        </h1>
        <p className="font-body-md text-xs text-on-surface-variant mt-1 font-medium">
          Tinjau draf Laporan Hasil Pengujian (LHP) final dan bubuhkan tanda tangan elektronik terverifikasi keamanan.
        </p>
      </div>

      <div className="space-y-4">
        {isLoading ? (
          <div className="bg-white p-6 rounded-xl border border-outline-variant soft-shadow animate-pulse space-y-4">
            <div className="h-4 bg-gray-200 rounded w-32" />
            <div className="h-20 bg-gray-200 rounded" />
          </div>
        ) : pendingApprovals.length > 0 ? (
          pendingApprovals.map((app) => (
            <div key={app.id} className="bg-white p-6 rounded-xl border border-outline-variant soft-shadow grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
              {/* Document Info (Col 7) */}
              <div className="lg:col-span-7 space-y-4">
                <div className="flex items-center gap-2">
                  <span className="font-bold text-xs text-primary">{app.nomor_laporan || app.id.substring(0, 12)}</span>
                  <span className="bg-primary/10 text-primary px-2.5 py-0.5 rounded font-bold text-[9px] uppercase">
                    SIAP OTORISASI
                  </span>
                </div>
                <h3 className="font-headline-lg text-sm font-extrabold text-on-surface leading-tight">
                  Laporan Hasil Pengujian
                </h3>

                <div className="grid grid-cols-2 gap-2 text-[10px] text-on-surface-variant font-bold uppercase tracking-wider border-t border-outline-variant pt-3.5">
                  <div>
                    <span className="block text-gray-400">Status</span>
                    <span className="font-semibold text-on-surface capitalize">{app.status}</span>
                  </div>
                  <div>
                    <span className="block text-gray-400">Catatan</span>
                    <span className="font-semibold text-on-surface">{app.catatan || '-'}</span>
                  </div>
                </div>
              </div>

              {/* Digital Signature Card Box (Col 5) */}
              <div className="lg:col-span-5 bg-surface-container-low border border-outline-variant rounded-xl p-4 text-center space-y-3.5 hover-lift">
                <div className="flex items-center justify-center gap-1.5 text-xs font-bold text-primary border-b border-outline-variant pb-2">
                  <Signature size={15} />
                  <span>Lembar Tanda Tangan Elektronik</span>
                </div>
                
                <div className="p-4 bg-white border border-outline-variant border-dashed rounded-lg text-[10px] text-on-surface-variant font-medium flex flex-col items-center justify-center min-h-[90px]">
                  <span className="text-primary font-bold mb-1">[TANDA TANGAN DIGITAL TERSEGEL]</span>
                  <span className="text-[9px] text-gray-400 font-bold uppercase tracking-widest">Menunggu Otorisasi</span>
                </div>

                <div className="flex gap-2">
                  <button 
                    onClick={() => {/* reject logic */}}
                    className="flex-1 py-2 bg-white border border-outline-variant text-status-danger hover:bg-status-danger/5 font-semibold text-[10px] rounded transition-all cursor-pointer soft-shadow"
                  >
                    Tolak / Revisi
                  </button>
                  <button 
                    onClick={() => handleApprove(app.id)}
                    disabled={approveMutation.isPending}
                    className="flex-1 py-2 bg-primary text-on-primary hover:bg-primary-container font-bold text-[10px] rounded transition-all cursor-pointer flex items-center justify-center gap-1 soft-shadow disabled:opacity-80"
                  >
                    <Check size={12} />
                    {approveMutation.isPending ? 'Proses...' : 'Approve & Segel LHP'}
                  </button>
                </div>
              </div>
            </div>
          ))
        ) : (
          <div className="bg-white p-12 text-center rounded-xl border border-outline-variant soft-shadow text-on-surface-variant text-xs font-semibold">
            Semua draf laporan telah ditandatangani. Antrean persetujuan Kepala UPTD kosong.
          </div>
        )}
      </div>
    </div>
  );
};

export default Approval;
