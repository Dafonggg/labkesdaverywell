import React, { useState } from 'react';
import { AlertCircle, Clock, WifiOff, ShieldAlert, X } from 'lucide-react';
import { toast } from 'sonner';

interface AlertItem {
  id: number;
  title: string;
  subText: string;
  time: string;
  type: 'danger' | 'warning';
  icon: React.ComponentType<{ className?: string; size?: number }>;
  borderColor: string;
  textColor: string;
}

const UrgentAlerts: React.FC = () => {
  const [alerts, setAlerts] = useState<AlertItem[]>([
    {
      id: 1,
      title: 'Waktu Simpan Habis',
      subText: 'Sampel #BOD-8821 perlu segera diproses. Batas waktu simpan maksimal (48 jam) hampir tercapai.',
      time: '2 jam lalu',
      type: 'danger',
      icon: Clock,
      borderColor: 'border-l-status-danger',
      textColor: 'text-status-danger',
    },
    {
      id: 2,
      title: 'Gagal Sinkronisasi',
      subText: 'Pengguna Aplikasi Lapangan "Tim Alpha" gagal menyinkronkan 5 titik sampling karena koneksi terputus.',
      time: '4 jam lalu',
      type: 'warning',
      icon: WifiOff,
      borderColor: 'border-l-status-warning',
      textColor: 'text-status-warning',
    },
    {
      id: 3,
      title: 'Melebihi Baku Mutu',
      subText: 'Permohonan #APP-2023-1102 memiliki 3 parameter yang melebihi batas yang diperbolehkan pada QC awal.',
      time: '1 hari lalu',
      type: 'danger',
      icon: ShieldAlert,
      borderColor: 'border-l-status-danger',
      textColor: 'text-status-danger',
    },
  ]);

  const handleDismiss = (id: number, e: React.MouseEvent) => {
    e.stopPropagation();
    setAlerts(prev => prev.filter(alert => alert.id !== id));
  };

  const handleAlertClick = (alertTitle: string) => {
    toast.info(`Membuka detail peringatan: "${alertTitle}"`);
  };

  return (
    <div className="bg-surface rounded-xl p-0 soft-shadow border border-outline-variant/30 overflow-hidden flex flex-col flex-1">
      {/* Alert Header */}
      <div className="bg-surface-container px-6 py-4 border-b border-outline-variant/30 flex justify-between items-center shrink-0">
        <h3 className="font-headline-sm text-base md:text-lg font-bold text-on-surface flex items-center gap-2">
          <AlertCircle className="text-status-danger" size={20} />
          Peringatan Mendesak
        </h3>
        {alerts.length > 0 && (
          <span className="bg-status-danger text-on-error font-label-sm text-xs font-semibold px-2 py-0.5 rounded-full ring-2 ring-white/10 animate-bounce">
            {alerts.length}
          </span>
        )}
      </div>

      {/* Alert List */}
      <div className="divide-y divide-outline-variant/20 flex-1 overflow-y-auto max-h-[360px]">
        {alerts.length > 0 ? (
          alerts.map((alert) => {
            const Icon = alert.icon;
            return (
              <div
                key={alert.id}
                onClick={() => handleAlertClick(alert.title)}
                className={`p-4 hover:bg-surface-container-low transition-colors cursor-pointer border-l-4 ${alert.borderColor} flex justify-between items-start gap-3 group relative`}
              >
                <div className="flex-1">
                  <div className="flex justify-between items-start mb-1 flex-wrap gap-1">
                    <span className={`font-label-sm text-xs font-bold flex items-center gap-1.5 ${alert.textColor}`}>
                      <Icon size={14} />
                      {alert.title}
                    </span>
                    <span className="font-body-sm text-[10px] text-on-surface-variant font-medium">
                      {alert.time}
                    </span>
                  </div>
                  <p className="font-body-sm text-xs text-on-surface leading-relaxed pr-6 mt-1 font-medium">
                    {alert.subText}
                  </p>
                </div>

                {/* Dismiss Button */}
                <button
                  onClick={(e) => handleDismiss(alert.id, e)}
                  className="text-on-surface-variant/40 hover:text-on-surface-variant hover:bg-surface-variant/40 p-1 rounded-md transition-all scale-90 md:scale-100 shrink-0"
                  title="Tutup peringatan"
                >
                  <X size={14} />
                </button>
              </div>
            );
          })
        ) : (
          <div className="flex flex-col items-center justify-center py-12 text-on-surface-variant">
            <span className="material-symbols-outlined text-status-success text-4xl mb-2">check_circle</span>
            <p className="text-sm font-semibold">Semua sistem beroperasi normal.</p>
            <p className="text-xs mt-1">Tidak ada peringatan mendesak.</p>
          </div>
        )}
      </div>

      {/* Footer */}
      <div className="p-3 text-center border-t border-outline-variant/30 shrink-0 bg-surface">
        <button
          onClick={() => toast.info('Membuka riwayat semua notifikasi...')}
          className="text-primary hover:text-primary-container font-label-sm text-xs font-semibold hover:underline cursor-pointer"
        >
          Lihat Semua Notifikasi
        </button>
      </div>
    </div>
  );
};

export default UrgentAlerts;
