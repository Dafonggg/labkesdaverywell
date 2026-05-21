import { apiClient } from './axios';

export interface DraftLaporanData {
  id: string;
  nomor_laporan: string;
  permohonan_id: string;
  status: string;
  catatan: string | null;
  permohonan?: Record<string, unknown>;
  created_at: string;
  updated_at: string;
}

export interface LaporanFinalData {
  id: string;
  draft_laporan_id: string;
  nomor_laporan: string;
  file_path: string | null;
  hash_sha256: string | null;
  status: string;
  created_at: string;
}

export const generateLaporan = async (payload?: { permohonan_id?: string }): Promise<{ data: DraftLaporanData }> => {
  const response = await apiClient.post('/laporan/generate', payload);
  return response.data;
};

export const getDrafts = async (params?: { page?: number; per_page?: number }): Promise<{ data: DraftLaporanData[]; meta?: Record<string, unknown> }> => {
  const response = await apiClient.get('/laporan/draft', { params });
  return response.data;
};

export const downloadLaporan = async (id: string): Promise<Blob> => {
  const response = await apiClient.get(`/laporan/${id}/download`, {
    responseType: 'blob',
  });
  return response.data;
};

export const getLaporanFinal = async (params?: { page?: number; per_page?: number }): Promise<{ data: LaporanFinalData[]; meta?: Record<string, unknown> }> => {
  const response = await apiClient.get('/laporan/final', { params });
  return response.data;
};
