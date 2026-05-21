import React from 'react';
import { useNavigate } from 'react-router-dom';
import { PlusCircle, QrCode, FileSpreadsheet, Search } from 'lucide-react';

interface QuickActionProps {
  label: string;
  subText: string;
  icon: React.ComponentType<{ className?: string; size?: number }>;
  onClick: () => void;
}

const QuickActionItem: React.FC<QuickActionProps> = ({ label, subText, icon: Icon, onClick }) => {
  return (
    <button
      onClick={onClick}
      className="flex flex-col items-center justify-center p-5 rounded-xl bg-surface-container-low hover:bg-primary/10 border border-transparent hover:border-primary/20 transition-all duration-300 text-primary cursor-pointer group soft-shadow hover:scale-[1.02] active:scale-[0.98]"
    >
      <div className="w-12 h-12 rounded-full bg-white group-hover:bg-primary/20 flex items-center justify-center mb-3 soft-shadow transition-colors">
        <Icon size={24} className="text-primary group-hover:scale-110 transition-transform" />
      </div>
      <span className="font-label-md text-sm font-semibold text-on-surface group-hover:text-primary transition-colors">
        {label}
      </span>
      <span className="text-[10px] text-on-surface-variant/70 mt-1 font-medium">
        {subText}
      </span>
    </button>
  );
};

const QuickActions: React.FC = () => {
  const navigate = useNavigate();

  const actions = [
    {
      label: 'Permohonan Baru',
      subText: 'Buat Pengajuan',
      icon: PlusCircle,
      onClick: () => navigate('/permohonan'),
    },
    {
      label: 'Registrasi Sampel',
      subText: 'Pindai & Catat',
      icon: QrCode,
      onClick: () => navigate('/registrasi'),
    },
    {
      label: 'Buat LHU',
      subText: 'Ekspor Hasil Uji',
      icon: FileSpreadsheet,
      onClick: () => navigate('/arsip'),
    },
    {
      label: 'Cari Data',
      subText: 'Pencarian Audit',
      icon: Search,
      onClick: () => navigate('/monitoring'),
    },
  ];

  return (
    <div className="bg-surface rounded-xl p-6 soft-shadow border border-outline-variant/30">
      <h3 className="font-headline-sm text-lg font-bold text-on-surface mb-5">Aksi Cepat</h3>
      <div className="grid grid-cols-2 gap-4">
        {actions.map((act, idx) => (
          <QuickActionItem key={idx} {...act} />
        ))}
      </div>
    </div>
  );
};

export default QuickActions;
