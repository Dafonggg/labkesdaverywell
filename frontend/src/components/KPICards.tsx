import React from 'react';
import { FileText, Dna, FlaskConical, ClipboardCheck, ArrowUpRight, ArrowRight, AlertTriangle } from 'lucide-react';
import type { DashboardSummary } from '@/services/dashboard.service';

interface KPICardProps {
  title: string;
  value: string;
  trend: string;
  trendType: 'success' | 'flat' | 'warning' | 'danger';
  icon: React.ComponentType<{ className?: string; size?: number }>;
  iconBg: string;
  iconColor: string;
  borderColor?: string;
}

const KPICard: React.FC<KPICardProps> = ({
  title,
  value,
  trend,
  trendType,
  icon: Icon,
  iconBg,
  iconColor,
  borderColor,
}) => {
  const getTrendStyles = () => {
    switch (trendType) {
      case 'success':
        return 'text-status-success bg-status-success/10';
      case 'warning':
        return 'text-status-warning bg-status-warning/10';
      case 'danger':
        return 'text-status-danger bg-status-danger/10';
      default:
        return 'text-on-surface-variant bg-surface-variant/50';
    }
  };

  const getTrendIcon = () => {
    switch (trendType) {
      case 'success':
        return <ArrowUpRight size={14} />;
      case 'warning':
        return <ArrowUpRight size={14} />;
      case 'danger':
        return <AlertTriangle size={14} />;
      default:
        return <ArrowRight size={14} />;
    }
  };

  return (
    <div
      className={`bg-surface rounded-xl p-5 soft-shadow border border-outline-variant/30 flex flex-col justify-between hover-lift cursor-pointer ${
        borderColor ? `border-l-4 ${borderColor}` : ''
      }`}
    >
      <div className="flex justify-between items-start mb-4">
        <div className={`w-10 h-10 rounded-full ${iconBg} flex items-center justify-center`}>
          <Icon className={iconColor} size={20} />
        </div>
        <span className={`inline-flex items-center gap-1 font-label-sm text-xs px-2 py-1 rounded-full ${getTrendStyles()}`}>
          {getTrendIcon()} {trend}
        </span>
      </div>
      <div>
        <p className="font-body-sm text-xs text-on-surface-variant mb-1 font-medium">{title}</p>
        <h3 className="font-headline-lg text-xl md:text-2xl font-bold text-on-surface leading-none mt-1">
          {value}
        </h3>
      </div>
    </div>
  );
};

// Skeleton loader for KPI cards
const KPICardSkeleton: React.FC = () => (
  <div className="bg-surface rounded-xl p-5 soft-shadow border border-outline-variant/30 flex flex-col justify-between animate-pulse">
    <div className="flex justify-between items-start mb-4">
      <div className="w-10 h-10 rounded-full bg-gray-200" />
      <div className="w-16 h-6 rounded-full bg-gray-200" />
    </div>
    <div>
      <div className="w-24 h-3 rounded bg-gray-200 mb-2" />
      <div className="w-16 h-7 rounded bg-gray-200" />
    </div>
  </div>
);

interface KPICardsProps {
  summary?: DashboardSummary;
  isLoading?: boolean;
}

const KPICards: React.FC<KPICardsProps> = ({ summary, isLoading }) => {
  if (isLoading) {
    return (
      <div className="col-span-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        {[...Array(4)].map((_, idx) => (
          <KPICardSkeleton key={idx} />
        ))}
      </div>
    );
  }

  const cards = [
    {
      title: 'Total Permohonan',
      value: summary?.permohonan?.toLocaleString('id-ID') || '0',
      trend: 'Aktif',
      trendType: 'success' as const,
      icon: FileText,
      iconBg: 'bg-primary/10',
      iconColor: 'text-primary',
    },
    {
      title: 'Pending Sampling',
      value: summary?.pending_sampling?.toString() || '0',
      trend: 'Menunggu',
      trendType: 'flat' as const,
      icon: Dna,
      iconBg: 'bg-status-warning/10',
      iconColor: 'text-status-warning',
    },
    {
      title: 'Antrean Lab',
      value: summary?.lab_analysis_queue?.toString() || '0',
      trend: 'Proses',
      trendType: 'warning' as const,
      icon: FlaskConical,
      iconBg: 'bg-status-info/10',
      iconColor: 'text-status-info',
    },
    {
      title: 'Menunggu QC',
      value: summary?.pending_qc?.toString() || '0',
      trend: summary?.pending_qc && summary.pending_qc > 0 ? 'Perlu Aksi' : 'Aman',
      trendType: summary?.pending_qc && summary.pending_qc > 0 ? 'danger' as const : 'success' as const,
      icon: ClipboardCheck,
      iconBg: summary?.pending_qc && summary.pending_qc > 0 ? 'bg-status-danger/10' : 'bg-status-success/10',
      iconColor: summary?.pending_qc && summary.pending_qc > 0 ? 'text-status-danger' : 'text-status-success',
      borderColor: summary?.pending_qc && summary.pending_qc > 0 ? 'border-l-status-danger' : undefined,
    },
  ];

  return (
    <div className="col-span-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
      {cards.map((card, idx) => (
        <KPICard key={idx} {...card} />
      ))}
    </div>
  );
};

export default KPICards;
