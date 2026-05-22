import { useQuery } from '@tanstack/react-query';
import { getPetugasLapangan } from '@/services/user.service';

export const usePetugasLapangan = () => {
  return useQuery({
    queryKey: ['users', 'petugas-lapangan'],
    queryFn: getPetugasLapangan,
    staleTime: 5 * 60 * 1000, // cache 5 minutes — petugas list rarely changes
  });
};
