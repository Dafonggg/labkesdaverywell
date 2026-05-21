import React from 'react';
import { FileSpreadsheet, Printer, Send } from 'lucide-react';
import { useDrafts, useDownloadLaporan, useGenerateLaporan } from '@/hooks/useLaporan';
import dayjs from 'dayjs';

const Laporan: React.FC = () => {
  const { data: response, isLoading } = useDrafts();
  const downloadMutation = useDownloadLaporan();
  const generateMutation = useGenerateLaporan();

  const drafts = response?.data || [];

  return (
    <div className="space-y-6">
      <div className="border-b border-outline-variant pb-4">
        <h1 className="font-headline-lg text-lg md:text-xl font-extrabold text-on-surface">
          Draft & Kompilasi Laporan Hasil Pengujian (LHP)
        </h1>
        <p className="font-body-md text-xs text-on-surface-variant mt-1 font-medium">
          Susun lembar kerja pengujian laboratorium menjadi dokumen LHP formal institusi pemerintah.
        </p>
      </div>

      <div className="bg-white rounded-xl border border-outline-variant overflow-hidden soft-shadow">
        <div className="p-4 border-b border-outline-variant bg-surface-container-low flex justify-between items-center">
          <h3 className="font-headline-md text-xs font-bold text-on-surface uppercase tracking-wider flex items-center gap-2">
            <FileSpreadsheet size={16} className="text-primary" />
            Antrean Draft Laporan Analis
          </h3>
          <button
            onClick={() => generateMutation.mutate({})}
            disabled={generateMutation.isPending}
            className="bg-primary text-on-primary font-label-md text-[10px] font-bold px-3 py-1.5 rounded hover:bg-primary-container transition-all cursor-pointer soft-shadow disabled:opacity-80"
          >
            {generateMutation.isPending ? 'Membuat...' : '+ Generate Draft'}
          </button>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full text-left">
            <thead>
              <tr className="bg-surface-container border-b border-outline-variant text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">
                <th className="p-4">No. Laporan</th>
                <th className="p-4">Status</th>
                <th className="p-4">Catatan</th>
                <th className="p-4">Tanggal</th>
                <th className="p-4 text-center">Aksi Kompilasi</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-outline-variant text-xs text-on-surface font-medium">
              {isLoading ? (
                Array.from({ length: 3 }).map((_, idx) => (
                  <tr key={idx} className="animate-pulse">
                    <td className="p-4"><div className="h-3 bg-gray-200 rounded w-24" /></td>
                    <td className="p-4"><div className="h-3 bg-gray-200 rounded w-20" /></td>
                    <td className="p-4"><div className="h-3 bg-gray-200 rounded w-32" /></td>
                    <td className="p-4"><div className="h-3 bg-gray-200 rounded w-20" /></td>
                    <td className="p-4"><div className="h-3 bg-gray-200 rounded w-32 mx-auto" /></td>
                  </tr>
                ))
              ) : drafts.length === 0 ? (
                <tr>
                  <td colSpan={5} className="p-12 text-center text-on-surface-variant font-semibold">
                    Belum ada draft laporan.
                  </td>
                </tr>
              ) : (
                drafts.map((draft) => (
                  <tr key={draft.id} className="hover:bg-surface-container-low transition-all">
                    <td className="p-4 font-bold text-primary">{draft.nomor_laporan || draft.id.substring(0, 12)}</td>
                    <td className="p-4">
                      <span className={`px-2.5 py-0.5 rounded-full font-bold text-[10px] ${
                        draft.status === 'draft'
                          ? 'bg-status-info/10 text-status-info'
                          : 'bg-status-warning/10 text-status-warning'
                      }`}>
                        {draft.status === 'draft' ? 'Draft' : draft.status}
                      </span>
                    </td>
                    <td className="p-4 text-on-surface-variant">{draft.catatan || '-'}</td>
                    <td className="p-4 text-on-surface-variant">{dayjs(draft.created_at).format('YYYY-MM-DD')}</td>
                    <td className="p-4">
                      <div className="flex items-center justify-center gap-2">
                        <button 
                          onClick={() => downloadMutation.mutate(draft.id)}
                          disabled={downloadMutation.isPending}
                          className="inline-flex items-center gap-1 bg-white border border-outline-variant text-on-surface px-2.5 py-1.5 rounded hover:bg-surface-container transition-all text-[10px] font-semibold cursor-pointer soft-shadow"
                        >
                          <Printer size={12} />
                          Kompilasi / PDF
                        </button>
                        <button 
                          onClick={() => generateMutation.mutate({ permohonan_id: draft.permohonan_id })}
                          className="inline-flex items-center gap-1 bg-primary text-on-primary px-3 py-1.5 rounded hover:bg-primary-container transition-all text-[10px] font-bold cursor-pointer soft-shadow"
                        >
                          <Send size={11} />
                          Kirim ke Kepala UPTD
                        </button>
                      </div>
                    </td>
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

export default Laporan;
