import React, { useState } from 'react';
import { QrCode, Tag, AlertCircle, Plus, Check } from 'lucide-react';
import { toast } from 'sonner';
import { useRegistrasiList, useRegisterSample } from '@/hooks/useRegistrasi';
import dayjs from 'dayjs';

const Registrasi: React.FC = () => {
  const [sampleName, setSampleName] = useState('');
  const [sampleSource, setSampleSource] = useState('');
  const [category, setCategory] = useState('Air Bersih');
  const [selectedParams, setSelectedParams] = useState<string[]>([]);

  const { data: response, isLoading } = useRegistrasiList();
  const registerMutation = useRegisterSample();

  const registeredSamples = response?.data || [];

  const parameterOptions: Record<string, string[]> = {
    'Air Bersih': ['pH', 'Fe', 'Mn', 'Kesadahan', 'Kekeruhan', 'Suhu'],
    'Air Limbah': ['pH', 'BOD', 'COD', 'TSS', 'Ammonia', 'Minyak & Lemak'],
    'Udara Ambien': ['SO2', 'CO', 'NO2', 'O3', 'PM10', 'PM2.5'],
    'Tanah': ['pH Tanah', 'Kandungan Organik', 'N total', 'P tersedia', 'Logam Berat']
  };

  const handleParamToggle = (param: string) => {
    setSelectedParams((prev: string[]) => 
      prev.includes(param) ? prev.filter((p: string) => p !== param) : [...prev, param]
    );
  };

  const handleRegister = (e: React.FormEvent) => {
    e.preventDefault();
    if (!sampleName || !sampleSource) {
      toast.error('Silakan isi Nama Sampel dan Lokasi Sumber.');
      return;
    }
    if (selectedParams.length === 0) {
      toast.error('Pilih minimal satu parameter pengujian.');
      return;
    }

    registerMutation.mutate(
      {},
      {
        onSuccess: () => {
          setSampleName('');
          setSampleSource('');
          setSelectedParams([]);
        },
      }
    );
  };

  return (
    <div className="space-y-6">
      <div className="border-b border-outline-variant pb-4">
        <h1 className="font-headline-lg text-lg md:text-xl font-extrabold text-on-surface">
          Registrasi & Kodifikasi Sampel
        </h1>
        <p className="font-body-md text-xs text-on-surface-variant mt-1 font-medium">
          Daftarkan sampel fisik dari lapangan, tentukan parameter uji, dan cetak label barcode/QR ISO 17025.
        </p>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {/* Left Column: Form Pendaftaran (Col 7) */}
        <form onSubmit={handleRegister} className="bg-white p-6 rounded-xl border border-outline-variant soft-shadow lg:col-span-7 space-y-5">
          <h3 className="font-headline-md text-sm font-bold text-primary flex items-center gap-1.5 border-b border-outline-variant pb-3 mb-2">
            <Tag size={16} />
            Formulir Pendaftaran Sampel Baru
          </h3>

          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div className="space-y-1.5">
              <label className="block font-label-sm text-[11px] font-bold text-on-surface-variant uppercase">Nama Sampel *</label>
              <input
                type="text"
                placeholder="Contoh: Air Sungai Citarum"
                value={sampleName}
                onChange={(e) => setSampleName(e.target.value)}
                className="w-full px-3 py-2.5 rounded-lg border border-outline-variant bg-surface-container-low text-xs text-on-surface outline-none focus:border-primary focus:bg-white transition-all"
              />
            </div>
            <div className="space-y-1.5">
              <label className="block font-label-sm text-[11px] font-bold text-on-surface-variant uppercase">Lokasi Sumber / Klien *</label>
              <input
                type="text"
                placeholder="Contoh: Jatiluhur Barat"
                value={sampleSource}
                onChange={(e) => setSampleSource(e.target.value)}
                className="w-full px-3 py-2.5 rounded-lg border border-outline-variant bg-surface-container-low text-xs text-on-surface outline-none focus:border-primary focus:bg-white transition-all"
              />
            </div>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div className="space-y-1.5">
              <label className="block font-label-sm text-[11px] font-bold text-on-surface-variant uppercase">Jenis / Kategori Sampel</label>
              <select
                value={category}
                onChange={(e) => {
                  setCategory(e.target.value);
                  setSelectedParams([]);
                }}
                className="w-full px-3 py-2.5 rounded-lg border border-outline-variant bg-surface-container-low text-xs text-on-surface outline-none focus:border-primary focus:bg-white transition-all"
              >
                <option value="Air Bersih">Air Bersih (Sanitasi / Minum)</option>
                <option value="Air Limbah">Air Limbah Industri / Domestik</option>
                <option value="Udara Ambien">Kualitas Udara Ambien</option>
                <option value="Tanah">Tanah & Sedimen</option>
              </select>
            </div>
            
            <div className="bg-primary/5 p-3 rounded-lg border border-primary/10 flex items-start gap-2 text-[11px] text-primary leading-normal">
              <AlertCircle size={16} className="shrink-0 mt-0.5" />
              <span>Sistem otomatis mencocokkan baku mutu standar nasional untuk parameter yang dipilih.</span>
            </div>
          </div>

          {/* Parameter Selector */}
          <div className="space-y-2">
            <label className="block font-label-sm text-[11px] font-bold text-on-surface-variant uppercase">Pilih Parameter Pengujian Uji *</label>
            <div className="grid grid-cols-2 sm:grid-cols-3 gap-2 bg-surface-container-low p-4 rounded-xl border border-outline-variant">
              {parameterOptions[category as keyof typeof parameterOptions].map((param) => {
                const isSelected = selectedParams.includes(param);
                return (
                  <button
                    key={param}
                    type="button"
                    onClick={() => handleParamToggle(param)}
                    className={`p-2.5 rounded-lg border text-left text-xs transition-all flex items-center justify-between cursor-pointer ${
                      isSelected
                        ? 'bg-primary/10 border-primary text-primary font-bold'
                        : 'bg-white border-outline-variant text-on-surface hover:border-primary/50'
                    }`}
                  >
                    <span>{param}</span>
                    {isSelected && <Check size={14} />}
                  </button>
                );
              })}
            </div>
          </div>

          <button
            type="submit"
            disabled={registerMutation.isPending}
            className="w-full flex items-center justify-center gap-2 bg-primary text-on-primary font-label-md text-xs font-semibold py-3 rounded-xl hover-lift hover:bg-primary-container transition-all soft-shadow cursor-pointer disabled:opacity-80"
          >
            <Plus size={15} />
            {registerMutation.isPending ? 'Mendaftarkan...' : 'Daftarkan Sampel & Cetak Barcode Label'}
          </button>
        </form>

        {/* Right Column: Recent Registered Samples (Col 5) */}
        <div className="lg:col-span-5 space-y-6">
          {/* Label Printing Box */}
          <div className="bg-white p-6 rounded-xl border border-outline-variant soft-shadow text-center space-y-4 hover-lift">
            <h4 className="font-headline-sm text-xs font-bold text-on-surface-variant flex items-center justify-center gap-1.5 border-b border-outline-variant pb-3 uppercase tracking-wider">
              <QrCode size={16} className="text-primary" />
              Preview Label Sampel Aktif
            </h4>
            
            {registeredSamples.length > 0 ? (
              <div className="flex flex-col items-center p-4 bg-surface-container border border-outline-variant border-dashed rounded-xl max-w-[280px] mx-auto">
                <div className="bg-white p-2.5 rounded-lg soft-shadow border border-outline-variant mb-2">
                  <div className="w-24 h-24 bg-gray-100 flex items-center justify-center text-on-surface-variant text-[10px] font-bold">
                    [QR CODE]
                  </div>
                </div>
                <div className="text-center">
                  <div className="font-bold text-sm text-primary">{registeredSamples[0].kode_sample || registeredSamples[0].nomor_registrasi}</div>
                  <div className="text-[10px] text-on-surface-variant font-medium">{dayjs(registeredSamples[0].created_at).format('YYYY-MM-DD HH:mm')}</div>
                </div>
              </div>
            ) : (
              <div className="py-8 text-on-surface-variant text-xs">
                {isLoading ? 'Memuat...' : 'Belum ada sampel terdaftar'}
              </div>
            )}
            
            <button 
              onClick={() => toast.success('Membuka dialog cetak label...')}
              className="w-full py-2 bg-white border border-outline-variant rounded-lg font-label-md text-xs font-semibold text-on-surface hover:bg-surface-container transition-all cursor-pointer soft-shadow"
            >
              Cetak Barcode Label Uji Ulang
            </button>
          </div>

          {/* List of Recently Registered */}
          <div className="bg-white p-6 rounded-xl border border-outline-variant soft-shadow space-y-4">
            <h4 className="font-headline-sm text-xs font-bold text-on-surface-variant uppercase tracking-wider border-b border-outline-variant pb-3">
              Sampel Terdaftar
            </h4>
            <div className="divide-y divide-outline-variant max-h-[220px] overflow-y-auto pr-1">
              {isLoading ? (
                Array.from({ length: 3 }).map((_, idx) => (
                  <div key={idx} className="py-3 animate-pulse">
                    <div className="h-3 bg-gray-200 rounded w-20 mb-1" />
                    <div className="h-3 bg-gray-200 rounded w-32" />
                  </div>
                ))
              ) : registeredSamples.length === 0 ? (
                <div className="py-4 text-xs text-on-surface-variant text-center">Belum ada data</div>
              ) : (
                registeredSamples.map((sample) => (
                  <div key={sample.id} className="py-3 flex justify-between items-start gap-4 hover:bg-surface-container-low transition-all px-2 rounded-lg">
                    <div>
                      <div className="font-bold text-xs text-primary">{sample.kode_sample || sample.nomor_registrasi}</div>
                      <div className="font-semibold text-xs text-on-surface mt-0.5">{sample.status}</div>
                    </div>
                    <div className="text-right shrink-0">
                      <span className="text-[10px] text-on-surface-variant block font-medium">
                        {dayjs(sample.created_at).format('HH:mm')}
                      </span>
                    </div>
                  </div>
                ))
              )}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Registrasi;
