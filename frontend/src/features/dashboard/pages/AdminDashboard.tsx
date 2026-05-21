import React from 'react';
import KPICards from '../../../components/KPICards';
import WorkflowDistribution from '../../../components/WorkflowDistribution';
import WeeklyThroughput from '../../../components/WeeklyThroughput';
import QuickActions from '../../../components/QuickActions';
import UrgentAlerts from '../../../components/UrgentAlerts';
import { Download, Plus, Sparkles } from 'lucide-react';
import { toast } from 'sonner';
import { useAuthStore, getRoleDisplayName } from '@/stores/auth';
import { useDashboardSummary } from '@/hooks/useDashboard';
import { useNavigate } from 'react-router-dom';

const AdminDashboard: React.FC = () => {
  const { user } = useAuthStore();
  const navigate = useNavigate();
  const { data: summaryResponse, isLoading } = useDashboardSummary();
  const summary = summaryResponse?.data;

  const handleExportReport = () => {
    toast.success('Laporan Ringkasan Administrasi berhasil diekspor!');
  };

  const handleNewApplication = () => {
    navigate('/permohonan');
  };

  return (
    <div className="space-y-6">
      {/* Main Title Banner & Actions */}
      <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-outline-variant pb-4">
        <div>
          <div className="flex items-center gap-2">
            <h1 className="font-headline-lg text-lg md:text-xl font-extrabold text-on-surface tracking-tight leading-tight">
              Ringkasan Operasional & Administrasi
            </h1>
            <span className="flex items-center gap-1 bg-primary/10 text-primary font-label-md text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
              <Sparkles size={10} />
              ISO 17025
            </span>
          </div>
          <p className="font-body-md text-xs text-on-surface-variant mt-1 font-medium">
            Selamat datang kembali, <span className="font-bold text-primary">{user?.name}</span>
            {user?.role && <span className="text-on-surface-variant"> ({getRoleDisplayName(user.role)})</span>}. Status real-time operasional laboratorium Purwakarta.
          </p>
        </div>
        
        <div className="flex items-center gap-3 w-full sm:w-auto">
          <button 
            onClick={handleExportReport}
            className="flex-1 sm:flex-initial flex items-center justify-center gap-2 bg-white border border-outline-variant text-on-surface px-4 py-2.5 rounded-lg hover:bg-surface-container transition-all font-label-md text-xs font-semibold cursor-pointer soft-shadow hover:scale-[1.02] active:scale-[0.98]"
          >
            <Download size={15} />
            Ekspor Laporan
          </button>
          
          <button 
            onClick={handleNewApplication}
            className="flex-1 sm:flex-initial flex items-center justify-center gap-2 bg-primary text-on-primary px-5 py-2.5 rounded-lg hover-lift hover:bg-primary-container transition-all font-label-md text-xs font-semibold cursor-pointer soft-shadow"
          >
            <Plus size={15} />
            Permohonan Baru
          </button>
        </div>
      </div>

      {/* Grid Layout (Bento Grid) */}
      <div className="bento-grid">
        
        {/* 1. KPIs Row — Connected to API */}
        <KPICards summary={summary} isLoading={isLoading} />

        {/* 2. Main Analytics Content Area */}
        <div className="col-span-12 lg:col-span-8 flex flex-col gap-6">
          {/* Workflow Distribution Chart Card */}
          <WorkflowDistribution />

          {/* Weekly Throughput Bar Chart Card */}
          <WeeklyThroughput />
        </div>

        {/* 3. Operational Action Sidebar Area */}
        <div className="col-span-12 lg:col-span-4 flex flex-col gap-6">
          {/* Quick Actions Panel */}
          <QuickActions />

          {/* Urgent Alerts System */}
          <UrgentAlerts />
        </div>

      </div>
    </div>
  );
};

export default AdminDashboard;
