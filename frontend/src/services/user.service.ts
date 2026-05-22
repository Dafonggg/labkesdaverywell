import { apiClient } from './axios';

export interface UserData {
  id: string;
  name: string;
  email: string;
  role?: string;
}

export const getPetugasLapangan = async (): Promise<{ data: UserData[] }> => {
  const response = await apiClient.get('/users/petugas-lapangan');
  return response.data;
};
