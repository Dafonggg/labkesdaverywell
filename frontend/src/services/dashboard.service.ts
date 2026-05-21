import { apiClient } from './axios';

export interface DashboardSummary {
  permohonan: number;
  pending_sampling: number;
  lab_analysis_queue: number;
  pending_qc: number;
  laporan_final: number;
  total_payments: number;
}

export const getDashboardSummary = async (): Promise<{ data: DashboardSummary }> => {
  const response = await apiClient.get('/dashboard/summary');
  return response.data;
};
