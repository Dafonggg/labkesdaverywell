import { apiClient } from './axios';

export interface ParameterUjiData {
  id: string;
  nama_parameter: string;
  satuan: string;
  metode_pengujian: string;
  baku_mutu: string | null;
  kategori: string;
  created_at: string;
}

export interface HasilUjiPayload {
  sample_id: string;
  parameter_uji_id: string;
  nilai_hasil: number;
  metode_pengujian?: string;
  catatan?: string;
}

export interface HasilUjiData {
  id: string;
  sample_id: string;
  parameter_uji_id: string;
  nilai_hasil: number;
  metode_pengujian: string;
  status: string;
  catatan: string | null;
  sample?: Record<string, unknown>;
  parameter?: ParameterUjiData;
  created_at: string;
  updated_at: string;
}

export const getParameterUji = async (params?: { kategori?: string }): Promise<{ data: ParameterUjiData[] }> => {
  const response = await apiClient.get('/parameter-uji', { params });
  return response.data;
};

export const submitHasilUji = async (payload: HasilUjiPayload): Promise<{ data: HasilUjiData }> => {
  const response = await apiClient.post('/hasil-uji', payload);
  return response.data;
};

export const getPendingQc = async (): Promise<{ data: HasilUjiData[] }> => {
  const response = await apiClient.get('/hasil-uji/pending-qc');
  return response.data;
};
