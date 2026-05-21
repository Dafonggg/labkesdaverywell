import { apiClient } from './axios';

export interface JadwalPayload {
  permohonan_id: string;
  petugas_lapangan_id: string;
  tanggal_sampling: string;
  lokasi: string;
}

export interface JadwalData {
  id: string;
  permohonan_id: string;
  petugas_lapangan_id: string;
  tanggal_sampling: string;
  lokasi: string;
  status: string;
  catatan: string | null;
  permohonan?: Record<string, unknown>;
  petugas?: Record<string, unknown>;
  created_at: string;
  updated_at: string;
}

export const createJadwalSampling = async (payload: JadwalPayload): Promise<{ data: JadwalData }> => {
  const response = await apiClient.post('/jadwal-sampling', payload);
  return response.data;
};

export const getJadwalSampling = async (params?: { page?: number; per_page?: number }): Promise<{ data: JadwalData[]; meta?: Record<string, unknown> }> => {
  const response = await apiClient.get('/jadwal-sampling', { params });
  return response.data;
};
