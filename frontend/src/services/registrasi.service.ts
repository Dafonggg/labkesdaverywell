import { apiClient } from './axios';

export interface RegistrasiPayload {
  permohonan_id?: string;
  sample_ids?: string[];
}

export interface RegistrasiData {
  id: string;
  nomor_registrasi: string;
  kode_sample: string;
  sample_id: string;
  status: string;
  sample?: SampleData;
  created_at: string;
  updated_at: string;
}

export interface SampleData {
  id: string;
  kode_sample: string;
  jenis_sample: string;
  nama_sample: string;
  lokasi_pengambilan: string;
  status: string;
  created_at: string;
}

export const registerSample = async (payload: RegistrasiPayload): Promise<{ data: RegistrasiData }> => {
  const response = await apiClient.post('/registrasi-sample', payload);
  return response.data;
};

export const getRegistrasiSamples = async (params?: { page?: number; per_page?: number }): Promise<{ data: RegistrasiData[]; meta?: Record<string, unknown> }> => {
  const response = await apiClient.get('/registrasi-sample', { params });
  return response.data;
};
