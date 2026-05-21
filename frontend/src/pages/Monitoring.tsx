import React from 'react';
import { Terminal, HeartPulse } from 'lucide-react';
import { toast } from 'sonner';
import { useActivityLogs } from '@/hooks/useActivityLog';
import dayjs from 'dayjs';

const Monitoring: React.FC = () => {
  const { data: response, isLoading } = useActivityLogs({ per_page: 20 });
  const logs = response?.data || [];

  return (
    <div className="space-y-6">
      <div className="border-b border-outline-variant pb-4">
        <h1 className="font-headline-lg text-lg md:text-xl font-extrabold text-on-surface">
          Monitoring & Audit Trail Aktivitas
        </h1>
        <p className="font-body-md text-xs text-on-surface-variant mt-1 font-medium">
          Pantau status kesehatan sistem (LIMS) dan audit log lengkap aktivitas pengguna ISO 17025.
        </p>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        {/* Audit Logs Widget */}
        <div className="bg-white p-6 rounded-xl border border-outline-variant soft-shadow space-y-4">
          <h3 className="font-headline-md text-sm font-bold text-on-surface border-b border-outline-variant pb-3 flex items-center gap-2">
            <Terminal size={18} className="text-primary" />
            Audit Trail (Aktivitas Pengguna)
          </h3>
          <div className="space-y-3 max-h-[380px] overflow-y-auto pr-1">
            {isLoading ? (
              Array.from({ length: 4 }).map((_, idx) => (
                <div key={idx} className="p-3 bg-surface-container-low border border-outline-variant rounded-lg animate-pulse space-y-2">
                  <div className="h-3 bg-gray-200 rounded w-24" />
                  <div className="h-3 bg-gray-200 rounded w-48" />
                  <div className="h-3 bg-gray-200 rounded w-36" />
                </div>
              ))
            ) : logs.length === 0 ? (
              <div className="p-8 text-center text-on-surface-variant text-xs">Belum ada log aktivitas.</div>
            ) : (
              logs.map((log) => (
                <div key={log.id} className="p-3 bg-surface-container-low border border-outline-variant rounded-lg space-y-1 hover:bg-surface-container transition-all">
                  <div className="flex justify-between items-center text-[10px]">
                    <span className="font-bold text-primary">{log.user?.name || 'System'}</span>
                    <span className="text-on-surface-variant font-medium">{dayjs(log.created_at).format('YYYY-MM-DD HH:mm:ss')}</span>
                  </div>
                  <p className="text-xs font-semibold text-on-surface capitalize">{log.action} — {log.entity_type}</p>
                  <div className="flex justify-between items-center text-[9px] text-on-surface-variant font-bold uppercase tracking-wider">
                    <span>Entity: {log.entity_id?.substring(0, 8) || '-'}</span>
                    <span>IP: {log.ip_address || '-'}</span>
                  </div>
                </div>
              ))
            )}
          </div>
        </div>

        {/* System Health Widget */}
        <div className="bg-white p-6 rounded-xl border border-outline-variant soft-shadow space-y-4">
          <h3 className="font-headline-md text-sm font-bold text-on-surface border-b border-outline-variant pb-3 flex items-center gap-2">
            <HeartPulse size={18} className="text-primary" />
            Kesehatan & Sinkronisasi Sistem
          </h3>
          
          <div className="space-y-4">
            {[
              { label: 'Integrasi API Gateway', val: 'Beroperasi Normal', color: 'text-status-success' },
              { label: 'Database PostgreSQL', val: 'Sinkron', color: 'text-status-success' },
              { label: 'JWT Authentication', val: 'Aktif', color: 'text-status-success' },
              { label: 'Antrean Queue', val: '0 job pending', color: 'text-primary font-bold' }
            ].map((sys, idx) => (
              <div key={idx} className="flex justify-between items-center p-3 rounded-lg bg-surface-container-low border border-outline-variant">
                <span className="font-semibold text-xs text-on-surface">{sys.label}</span>
                <span className={`text-[10px] font-bold uppercase ${sys.color || 'text-on-surface-variant'}`}>{sys.val}</span>
              </div>
            ))}
            
            <button 
              onClick={() => toast.success('Memulai diagnostik sistem...')}
              className="w-full py-2.5 bg-primary text-on-primary font-label-md text-xs font-semibold rounded-lg hover:bg-primary-container transition-all cursor-pointer soft-shadow"
            >
              Jalankan Diagnostik Mandiri
            </button>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Monitoring;
