import React from 'react';
import { Calendar, User, MapPin } from 'lucide-react';
import { toast } from 'sonner';
import { useJadwalList } from '@/hooks/useJadwal';
import dayjs from 'dayjs';

const STATUS_STYLES: Record<string, string> = {
  scheduled: 'bg-status-info/10 text-status-info',
  in_progress: 'bg-status-warning/10 text-status-warning animate-pulse',
  completed: 'bg-status-success/10 text-status-success',
  draft: 'bg-gray-400/10 text-gray-700',
};

const STATUS_LABELS: Record<string, string> = {
  scheduled: 'Ditetapkan',
  in_progress: 'Sedang Sampling',
  completed: 'Selesai',
  draft: 'Draft',
};

const Jadwal: React.FC = () => {
  const { data: response, isLoading } = useJadwalList();
  const jadwalList = response?.data || [];

  return (
    <div className="space-y-6">
      <div className="flex justify-between items-center border-b border-outline-variant pb-4">
        <div>
          <h1 className="font-headline-lg text-lg md:text-xl font-extrabold text-on-surface">
            Jadwal & Penugasan Petugas Lapangan
          </h1>
          <p className="font-body-md text-xs text-on-surface-variant mt-1 font-medium">
            Atur rute kunjungan lapangan dan tugaskan petugas sampling lingkungan ISO 17025.
          </p>
        </div>
        <button 
          onClick={() => toast.success('Membuka penugasan petugas...')}
          className="bg-primary text-on-primary px-5 py-2.5 rounded-lg hover:bg-primary-container transition-all font-label-md text-xs font-semibold cursor-pointer soft-shadow"
        >
          Tugaskan Petugas Baru
        </button>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {/* Calendar View (Col 8) */}
        <div className="bg-white p-6 rounded-xl border border-outline-variant soft-shadow lg:col-span-8 space-y-4">
          <h3 className="font-headline-md text-sm font-bold text-on-surface border-b border-outline-variant pb-3 flex items-center gap-2">
            <Calendar size={18} className="text-primary" />
            Agenda Kunjungan Lapangan
          </h3>
          
          <div className="space-y-3">
            {isLoading ? (
              Array.from({ length: 3 }).map((_, idx) => (
                <div key={idx} className="p-4 rounded-xl border border-outline-variant bg-surface-container-low animate-pulse">
                  <div className="h-3 bg-gray-200 rounded w-20 mb-2" />
                  <div className="h-4 bg-gray-200 rounded w-48 mb-2" />
                  <div className="h-3 bg-gray-200 rounded w-36" />
                </div>
              ))
            ) : jadwalList.length === 0 ? (
              <div className="p-12 text-center text-on-surface-variant text-xs font-semibold">
                Belum ada jadwal sampling.
              </div>
            ) : (
              jadwalList.map((sch) => (
                <div 
                  key={sch.id} 
                  className="p-4 rounded-xl border border-outline-variant bg-surface-container-low hover:bg-surface-container transition-all flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4"
                >
                  <div className="space-y-1">
                    <div className="flex items-center gap-2">
                      <span className="font-bold text-xs text-primary">{sch.id.substring(0, 8)}</span>
                      <span className="text-[10px] bg-white border border-outline-variant px-2 py-0.5 rounded-md font-bold text-on-surface-variant">
                        {dayjs(sch.tanggal_sampling).format('YYYY-MM-DD')}
                      </span>
                    </div>
                    <div className="flex flex-wrap gap-x-4 gap-y-1 text-[11px] text-on-surface-variant font-medium">
                      <span className="flex items-center gap-1">
                        <User size={13} />
                        Petugas: {(sch.petugas as Record<string, string>)?.name || '-'}
                      </span>
                      <span className="flex items-center gap-1">
                        <MapPin size={13} />
                        Lokasi: {sch.lokasi}
                      </span>
                    </div>
                  </div>

                  <div className="shrink-0">
                    <span className={`px-2.5 py-1 rounded-full font-bold text-[10px] ${STATUS_STYLES[sch.status] || 'bg-gray-200 text-gray-700'}`}>
                      {STATUS_LABELS[sch.status] || sch.status}
                    </span>
                  </div>
                </div>
              ))
            )}
          </div>
        </div>

        {/* Samplers Queue Widget (Col 4) */}
        <div className="bg-white p-6 rounded-xl border border-outline-variant soft-shadow lg:col-span-4 space-y-4">
          <h3 className="font-headline-md text-sm font-bold text-on-surface border-b border-outline-variant pb-3 uppercase tracking-wider text-xs">
            Ketersediaan Petugas Lapangan
          </h3>
          <div className="space-y-3">
            {[
              { name: 'Rian Hidayat', active: 'Sedang Lapangan', color: 'text-status-warning' },
              { name: 'Adi Saputra', active: 'Tersedia', color: 'text-status-success' },
              { name: 'Tomi Wijaya', active: 'Tersedia', color: 'text-status-success' },
              { name: 'Yusuf Maulana', active: 'Off Duty', color: 'text-gray-400' }
            ].map((p, idx) => (
              <div key={idx} className="flex justify-between items-center py-2 border-b border-outline-variant last:border-0">
                <span className="font-semibold text-xs text-on-surface">{p.name}</span>
                <span className={`text-[10px] font-bold ${p.color}`}>{p.active}</span>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
};

export default Jadwal;
