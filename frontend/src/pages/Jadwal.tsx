import React, { useState } from 'react';
import { Calendar, User, MapPin, Plus, X, Clock } from 'lucide-react';
import { useJadwalList, useCreateJadwal } from '@/hooks/useJadwal';
import { usePermohonanList } from '@/hooks/usePermohonan';
import { usePetugasLapangan } from '@/hooks/useUser';
import type { JadwalPayload } from '@/services/jadwal.service';
import type { PermohonanData } from '@/services/permohonan.service';
import dayjs from 'dayjs';

const STATUS_STYLES: Record<string, string> = {
  scheduled: 'bg-status-info/10 text-status-info',
  in_progress: 'bg-status-warning/10 text-status-warning',
  completed: 'bg-status-success/10 text-status-success',
  draft: 'bg-gray-400/10 text-gray-700',
};

const STATUS_LABELS: Record<string, string> = {
  scheduled: 'Ditetapkan',
  in_progress: 'Sedang Sampling',
  completed: 'Selesai',
  draft: 'Draft',
};

const EMPTY_FORM: JadwalPayload = {
  permohonan_id: '',
  petugas_lapangan_id: '',
  tanggal_sampling: '',
  lokasi: '',
};

const Jadwal: React.FC = () => {
  const { data: jadwalResponse, isLoading } = useJadwalList();
  const createMutation = useCreateJadwal();
  const jadwalList = jadwalResponse?.data || [];

  // Fetch paid permohonan for dropdown
  const { data: permohonanResponse, isLoading: loadingPermohonan } = usePermohonanList({ status: 'paid', per_page: 100 });
  const paidPermohonan: PermohonanData[] = permohonanResponse?.data || [];

  // Fetch petugas lapangan for dropdown
  const { data: petugasResponse, isLoading: loadingPetugas } = usePetugasLapangan();
  const petugasList = petugasResponse?.data || [];

  const [showModal, setShowModal] = useState(false);
  const [form, setForm] = useState<JadwalPayload>(EMPTY_FORM);

  // When permohonan is selected → auto-fill lokasi from alamat
  const handlePermohonanChange = (id: string) => {
    const selected = paidPermohonan.find((p) => p.id === id);
    setForm((prev) => ({
      ...prev,
      permohonan_id: id,
      // Auto-fill: combine nama pemohon/instansi + alamat for lokasi
      lokasi: selected
        ? `${selected.nama_instansi || selected.nama_pemohon} — ${selected.alamat}`
        : prev.lokasi,
    }));
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    createMutation.mutate(form, {
      onSuccess: () => {
        setShowModal(false);
        setForm(EMPTY_FORM);
      },
    });
  };

  // Petugas summary derived from real jadwal data
  const petugasSummary = jadwalList.reduce<Record<string, { name: string; count: number }>>((acc, j) => {
    const p = j.petugas as Record<string, string> | undefined;
    if (p?.id) {
      acc[p.id] = { name: p.name || 'Petugas', count: (acc[p.id]?.count || 0) + 1 };
    }
    return acc;
  }, {});

  const selectedPermohonan = paidPermohonan.find((p) => p.id === form.permohonan_id);

  return (
    <div className="space-y-6">
      <div className="flex justify-between items-center border-b border-outline-variant pb-4">
        <div>
          <h1 className="font-headline-lg text-lg md:text-xl font-extrabold text-on-surface">
            Jadwal &amp; Penugasan Petugas Lapangan
          </h1>
          <p className="font-body-md text-xs text-on-surface-variant mt-1 font-medium">
            Atur rute kunjungan lapangan dan tugaskan petugas sampling lingkungan ISO 17025.
          </p>
        </div>
        <button
          onClick={() => setShowModal(true)}
          className="flex items-center gap-2 bg-primary text-on-primary px-5 py-2.5 rounded-lg hover:bg-primary-container transition-all font-label-md text-xs font-semibold cursor-pointer soft-shadow"
        >
          <Plus size={14} />
          Tugaskan Petugas Baru
        </button>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {/* Jadwal List (Col 8) */}
        <div className="bg-white p-6 rounded-xl border border-outline-variant soft-shadow lg:col-span-8 space-y-4">
          <h3 className="font-headline-md text-sm font-bold text-on-surface border-b border-outline-variant pb-3 flex items-center gap-2">
            <Calendar size={18} className="text-primary" />
            Agenda Kunjungan Lapangan
          </h3>

          <div className="space-y-3">
            {isLoading ? (
              Array.from({ length: 3 }).map((_, idx) => (
                <div key={idx} className="p-4 rounded-xl border border-outline-variant bg-surface-container-low animate-pulse">
                  <div className="h-3 bg-gray-200 rounded w-20 mb-2" />
                  <div className="h-4 bg-gray-200 rounded w-48 mb-2" />
                  <div className="h-3 bg-gray-200 rounded w-36" />
                </div>
              ))
            ) : jadwalList.length === 0 ? (
              <div className="p-12 text-center text-on-surface-variant text-xs font-semibold">
                Belum ada jadwal sampling. Klik "Tugaskan Petugas Baru" untuk membuat jadwal.
              </div>
            ) : (
              jadwalList.map((sch) => (
                <div
                  key={sch.id}
                  className="p-4 rounded-xl border border-outline-variant bg-surface-container-low hover:bg-surface-container transition-all flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4"
                >
                  <div className="space-y-1">
                    <div className="flex items-center gap-2">
                      <span className="font-bold text-xs text-primary font-mono">{sch.id.substring(0, 8)}</span>
                      <span className="text-[10px] bg-white border border-outline-variant px-2 py-0.5 rounded-md font-bold text-on-surface-variant">
                        {dayjs(sch.tanggal_sampling).format('DD MMM YYYY')}
                      </span>
                    </div>
                    <div className="flex flex-wrap gap-x-4 gap-y-1 text-[11px] text-on-surface-variant font-medium">
                      <span className="flex items-center gap-1">
                        <User size={13} />
                        {(sch.petugas as Record<string, string>)?.name || '-'}
                      </span>
                      <span className="flex items-center gap-1">
                        <MapPin size={13} />
                        {sch.lokasi}
                      </span>
                    </div>
                  </div>
                  <span className={`px-2.5 py-1 rounded-full font-bold text-[10px] shrink-0 ${STATUS_STYLES[sch.status] || 'bg-gray-200 text-gray-700'}`}>
                    {STATUS_LABELS[sch.status] || sch.status}
                  </span>
                </div>
              ))
            )}
          </div>
        </div>

        {/* Ringkasan Penugasan (Col 4) */}
        <div className="bg-white p-6 rounded-xl border border-outline-variant soft-shadow lg:col-span-4 space-y-4">
          <h3 className="font-headline-md text-sm font-bold text-on-surface border-b border-outline-variant pb-3 uppercase tracking-wider text-xs">
            Ringkasan Penugasan
          </h3>

          {isLoading ? (
            <div className="space-y-3">
              {[1, 2, 3].map(i => <div key={i} className="h-8 bg-gray-100 rounded animate-pulse" />)}
            </div>
          ) : jadwalList.length === 0 ? (
            <div className="text-center py-8 text-on-surface-variant text-xs">
              <Clock size={24} className="mx-auto mb-2 opacity-40" />
              Belum ada penugasan
            </div>
          ) : (
            <div className="space-y-3">
              <div className="flex justify-between text-[10px] font-bold text-on-surface-variant uppercase border-b border-outline-variant pb-2">
                <span>Total Jadwal</span>
                <span className="text-primary">{jadwalList.length}</span>
              </div>
              {[
                { label: 'Ditetapkan', key: 'scheduled', color: 'text-status-info' },
                { label: 'Sedang Berjalan', key: 'in_progress', color: 'text-status-warning' },
                { label: 'Selesai', key: 'completed', color: 'text-status-success' },
              ].map(({ label, key, color }) => (
                <div key={key} className="flex justify-between items-center py-2 border-b border-outline-variant last:border-0">
                  <span className="font-semibold text-xs text-on-surface">{label}</span>
                  <span className={`text-[10px] font-bold ${color}`}>
                    {jadwalList.filter(j => j.status === key).length} jadwal
                  </span>
                </div>
              ))}
              {Object.entries(petugasSummary).length > 0 && (
                <>
                  <div className="text-[10px] font-bold text-on-surface-variant uppercase pt-2">Petugas Aktif</div>
                  {Object.entries(petugasSummary).map(([id, p]) => (
                    <div key={id} className="flex justify-between items-center py-1.5">
                      <span className="font-semibold text-xs text-on-surface">{p.name}</span>
                      <span className="text-[10px] font-bold text-primary">{p.count} tugas</span>
                    </div>
                  ))}
                </>
              )}
            </div>
          )}
        </div>
      </div>

      {/* Create Jadwal Modal */}
      {showModal && (
        <div className="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl soft-shadow border border-outline-variant w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div className="flex justify-between items-center p-6 border-b border-outline-variant sticky top-0 bg-white z-10">
              <h3 className="font-headline-md text-sm font-bold text-on-surface">Buat Jadwal Sampling Baru</h3>
              <button onClick={() => { setShowModal(false); setForm(EMPTY_FORM); }} className="p-1.5 hover:bg-surface-container rounded-lg cursor-pointer">
                <X size={18} />
              </button>
            </div>
            <form onSubmit={handleSubmit} className="p-6 space-y-4">

              {/* Permohonan Dropdown */}
              <div className="space-y-1.5">
                <label className="block font-label-sm text-[11px] font-bold text-on-surface-variant uppercase">
                  Permohonan (Status: Lunas) *
                </label>
                {loadingPermohonan ? (
                  <div className="h-10 bg-gray-100 rounded-lg animate-pulse" />
                ) : paidPermohonan.length === 0 ? (
                  <div className="w-full px-3 py-2.5 rounded-lg border border-status-warning/40 bg-status-warning/5 text-xs text-status-warning font-semibold">
                    ⚠️ Belum ada permohonan dengan status Lunas. Selesaikan pembayaran terlebih dahulu.
                  </div>
                ) : (
                  <select
                    required
                    value={form.permohonan_id}
                    onChange={(e) => handlePermohonanChange(e.target.value)}
                    className="w-full px-3 py-2.5 rounded-lg border border-outline-variant bg-surface-container-low text-xs outline-none focus:border-primary transition-all"
                  >
                    <option value="">-- Pilih Permohonan --</option>
                    {paidPermohonan.map((p) => (
                      <option key={p.id} value={p.id}>
                        {p.nomor_permohonan} — {p.nama_instansi || p.nama_pemohon} ({p.jenis_sample})
                      </option>
                    ))}
                  </select>
                )}

                {/* Auto-filled permohonan info card */}
                {selectedPermohonan && (
                  <div className="bg-primary/5 border border-primary/20 rounded-lg p-3 space-y-1 text-[10px]">
                    <div className="flex gap-4">
                      <div>
                        <span className="text-on-surface-variant font-bold uppercase block">Pemohon</span>
                        <span className="font-semibold text-on-surface">{selectedPermohonan.nama_pemohon}</span>
                      </div>
                      <div>
                        <span className="text-on-surface-variant font-bold uppercase block">Jenis Sampel</span>
                        <span className="font-semibold text-on-surface">{selectedPermohonan.jenis_sample}</span>
                      </div>
                    </div>
                    {selectedPermohonan.latitude && selectedPermohonan.longitude && (
                      <div className="flex items-center gap-1 text-primary font-semibold">
                        <MapPin size={11} />
                        Koordinat GPS tersedia: {selectedPermohonan.latitude.toFixed(4)}, {selectedPermohonan.longitude.toFixed(4)}
                      </div>
                    )}
                  </div>
                )}
              </div>

              {/* Petugas Lapangan Dropdown */}
              <div className="space-y-1.5">
                <label className="block font-label-sm text-[11px] font-bold text-on-surface-variant uppercase">
                  Petugas Lapangan *
                </label>
                {loadingPetugas ? (
                  <div className="h-10 bg-gray-100 rounded-lg animate-pulse" />
                ) : petugasList.length === 0 ? (
                  <div className="w-full px-3 py-2.5 rounded-lg border border-status-danger/40 bg-status-danger/5 text-xs text-status-danger font-semibold">
                    ⚠️ Belum ada akun petugas lapangan. Tambahkan user dengan role petugas_lapangan.
                  </div>
                ) : (
                  <select
                    required
                    value={form.petugas_lapangan_id}
                    onChange={(e) => setForm({ ...form, petugas_lapangan_id: e.target.value })}
                    className="w-full px-3 py-2.5 rounded-lg border border-outline-variant bg-surface-container-low text-xs outline-none focus:border-primary transition-all"
                  >
                    <option value="">-- Pilih Petugas --</option>
                    {petugasList.map((u) => (
                      <option key={u.id} value={u.id}>
                        {u.name} — {u.email}
                      </option>
                    ))}
                  </select>
                )}
              </div>

              {/* Tanggal Sampling */}
              <div className="space-y-1.5">
                <label className="block font-label-sm text-[11px] font-bold text-on-surface-variant uppercase">Tanggal Sampling *</label>
                <input
                  type="date"
                  required
                  value={form.tanggal_sampling}
                  onChange={(e) => setForm({ ...form, tanggal_sampling: e.target.value })}
                  min={dayjs().format('YYYY-MM-DD')}
                  className="w-full px-3 py-2.5 rounded-lg border border-outline-variant bg-surface-container-low text-xs outline-none focus:border-primary transition-all"
                />
              </div>

              {/* Lokasi — auto-filled from permohonan, editable */}
              <div className="space-y-1.5">
                <label className="block font-label-sm text-[11px] font-bold text-on-surface-variant uppercase flex items-center gap-1">
                  <MapPin size={12} className="text-primary" />
                  Lokasi Sampling *
                  {selectedPermohonan && (
                    <span className="text-[9px] font-normal text-primary bg-primary/10 px-1.5 py-0.5 rounded ml-1">
                      Auto-filled dari permohonan
                    </span>
                  )}
                </label>
                <input
                  type="text"
                  required
                  value={form.lokasi}
                  onChange={(e) => setForm({ ...form, lokasi: e.target.value })}
                  placeholder="Lokasi akan terisi otomatis saat memilih permohonan..."
                  className="w-full px-3 py-2.5 rounded-lg border border-outline-variant bg-surface-container-low text-xs outline-none focus:border-primary transition-all"
                />
                <p className="text-[10px] text-on-surface-variant">
                  Bisa diedit manual jika lokasi sampling berbeda dari alamat permohonan.
                </p>
              </div>

              <div className="bg-status-info/5 border border-status-info/20 rounded-lg p-3 text-[10px] text-status-info font-medium">
                ⚡ Jadwal akan dikirim otomatis ke aplikasi mobile petugas lapangan yang ditugaskan.
              </div>

              <div className="flex gap-3 pt-2">
                <button
                  type="button"
                  onClick={() => { setShowModal(false); setForm(EMPTY_FORM); }}
                  className="flex-1 py-2.5 border border-outline-variant text-on-surface rounded-lg font-semibold text-xs hover:bg-surface-container transition-all cursor-pointer"
                >
                  Batal
                </button>
                <button
                  type="submit"
                  disabled={createMutation.isPending || paidPermohonan.length === 0}
                  className="flex-1 py-2.5 bg-primary text-on-primary rounded-lg font-bold text-xs hover:bg-primary-container transition-all cursor-pointer soft-shadow disabled:opacity-80"
                >
                  {createMutation.isPending ? 'Menyimpan...' : 'Buat Jadwal'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};

export default Jadwal;
