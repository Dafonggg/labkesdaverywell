import React, { useState } from 'react';
import { QrCode, Tag, AlertCircle, Plus, Check, MapPin, FlaskConical } from 'lucide-react';
import { toast } from 'sonner';
import { useRegistrasiList, useRegisterSample } from '@/hooks/useRegistrasi';
import { usePermohonanList } from '@/hooks/usePermohonan';
import dayjs from 'dayjs';

// ─── Predefined Parameter Options ────────────────────────────────────────────

interface ParamGroup {
  onSite: string[];
  laboratorium: string[];
}

const parameterOptions: Record<string, ParamGroup> = {
  'Air': {
    onSite: ['Suhu', 'pH', 'DO', 'TDS', 'Conductivity', 'Salinitas', 'Kekeruhan', 'Warna', 'Bau'],
    laboratorium: ['BOD', 'COD', 'Nitrat', 'Nitrit', 'Amonia', 'Fosfat', 'Besi (Fe)', 'Mangan (Mn)', 'Timbal (Pb)', 'Merkuri (Hg)', 'Total Coliform', 'E.coli'],
  },
  'Air Limbah': {
    onSite: ['pH', 'Suhu', 'Debit aliran', 'Warna', 'Bau', 'DO'],
    laboratorium: ['BOD', 'COD', 'TSS', 'Minyak & Lemak', 'Amonia', 'Sulfida', 'Detergen', 'Logam berat', 'Total Coliform'],
  },
  'Makanan & Minuman': {
    onSite: ['Suhu makanan', 'Kondisi fisik', 'Warna', 'Bau', 'pH', 'Rapid test formalin', 'Rapid test boraks'],
    laboratorium: ['Formalin', 'Boraks', 'Pewarna sintetis', 'Pengawet', 'Total Plate Count', 'Salmonella', 'E.coli', 'Staphylococcus', 'Kapang & Jamur', 'Kadar air', 'Protein', 'Lemak'],
  },
  'Udara': {
    onSite: ['Suhu udara', 'Kelembaban', 'PM2.5', 'PM10', 'CO', 'SO2', 'NO2', 'Kebisingan', 'Intensitas cahaya'],
    laboratorium: ['Logam berat udara', 'VOC', 'Formaldehida', 'Asbes', 'Silika', 'Total bakteri udara', 'Jamur udara', 'Partikulat detail'],
  },
  'Tanah': {
    onSite: ['pH tanah', 'Suhu tanah', 'Kelembaban tanah', 'Warna tanah', 'Tekstur tanah', 'Conductivity'],
    laboratorium: ['Nitrogen (N)', 'Fosfor (P)', 'Kalium (K)', 'C-Organik', 'Bahan organik', 'KTK', 'Timbal (Pb)', 'Merkuri (Hg)', 'Kadmium (Cd)', 'Arsenik (As)', 'Total mikroba tanah'],
  },
  'Swab Lingkungan': {
    onSite: ['Suhu ruangan', 'Kelembaban ruangan', 'Kondisi kebersihan', 'ATP test'],
    laboratorium: ['Total bakteri', 'E.coli', 'Coliform', 'Jamur', 'Kapang', 'Staphylococcus', 'Angka kuman'],
  },
};

const matchKeyToParamName = (key: string, paramName: string): boolean => {
  const cleanKey = key.replace(/_/g, '').toLowerCase();
  const cleanParam = paramName.replace(/[\s\(\-\.\/]/g, '').toLowerCase();
  
  if (cleanKey === cleanParam) return true;
  if (cleanKey === 'rapidformalin' && cleanParam === 'rapidtestformalin') return true;
  if (cleanKey === 'rapidboraks' && cleanParam === 'rapidtestboraks') return true;
  if (cleanKey === 'pm25' && cleanParam === 'pm2.5') return true;
  if (cleanKey === 'pm10' && cleanParam === 'pm10') return true;
  
  return false;
};

// ─── Main Component ──────────────────────────────────────────────────────────

const Registrasi: React.FC = () => {
  const [sampleName, setSampleName] = useState('');
  const [sampleSource, setSampleSource] = useState('');
  const [category, setCategory] = useState('Air');
  const [selectedParams, setSelectedParams] = useState<string[]>([]);

  const { data: response, isLoading } = useRegistrasiList();
  const { data: permohonanResponse } = usePermohonanList({ unregistered: 1, per_page: 100 });
  const registerMutation = useRegisterSample();

  const registeredSamples = response?.data || [];
  const permohonanList = permohonanResponse?.data || [];

  const currentParamGroup = parameterOptions[category] || parameterOptions['Air'];
  const allParams = [...currentParamGroup.onSite, ...currentParamGroup.laboratorium];

  const handleParamToggle = (param: string) => {
    setSelectedParams((prev: string[]) => 
      prev.includes(param) ? prev.filter((p: string) => p !== param) : [...prev, param]
    );
  };

  const handleSelectAll = (group: 'onSite' | 'laboratorium' | 'all') => {
    if (group === 'all') {
      setSelectedParams(allParams);
    } else {
      const groupParams = currentParamGroup[group];
      const allGroupSelected = groupParams.every(p => selectedParams.includes(p));
      if (allGroupSelected) {
        setSelectedParams(prev => prev.filter(p => !groupParams.includes(p)));
      } else {
        setSelectedParams(prev => [...new Set([...prev, ...groupParams])]);
      }
    }
  };

  const handleRegister = (e: React.FormEvent) => {
    e.preventDefault();
    if (!sampleName) {
      toast.error('Silakan isi Nama Sampel.');
      return;
    }
    if (!sampleSource) {
      toast.error('Silakan pilih Permohonan.');
      return;
    }
    if (selectedParams.length === 0) {
      toast.error('Pilih minimal satu parameter pengujian.');
      return;
    }

    const payload = {
      permohonan_id: sampleSource,
      jenis_sample: category,
      nama_sample: sampleName,
      parameters: selectedParams,
    };

    registerMutation.mutate(
      payload,
      {
        onSuccess: () => {
          setSampleName('');
          setSampleSource('');
          setSelectedParams([]);
        },
      }
    );
  };

  const renderParamSection = (title: string, icon: React.ReactNode, params: string[], groupKey: 'onSite' | 'laboratorium') => {
    const allSelected = params.every(p => selectedParams.includes(p));
    const someSelected = params.some(p => selectedParams.includes(p));
    return (
      <div className="space-y-2">
        <div className="flex items-center justify-between">
          <div className="flex items-center gap-1.5">
            {icon}
            <span className="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">{title}</span>
            <span className="text-[9px] font-semibold text-on-surface-variant/60 bg-surface-container px-1.5 py-0.5 rounded-full">
              {params.filter(p => selectedParams.includes(p)).length}/{params.length}
            </span>
          </div>
          <button
            type="button"
            onClick={() => handleSelectAll(groupKey)}
            className={`text-[10px] font-bold px-2 py-0.5 rounded-full transition-all cursor-pointer ${
              allSelected 
                ? 'bg-primary/10 text-primary' 
                : someSelected 
                ? 'bg-status-warning/10 text-status-warning' 
                : 'bg-surface-container text-on-surface-variant hover:bg-primary/5 hover:text-primary'
            }`}
          >
            {allSelected ? 'Hapus Semua' : 'Pilih Semua'}
          </button>
        </div>
        <div className="grid grid-cols-2 sm:grid-cols-3 gap-2">
          {params.map((param) => {
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
              <label className="block font-label-sm text-[11px] font-bold text-on-surface-variant uppercase">Pilih Permohonan *</label>
              <select
                value={sampleSource}
                onChange={(e) => {
                  const val = e.target.value;
                  setSampleSource(val);
                  const selectedPermohonan = permohonanList.find(p => p.id === val);
                  if (selectedPermohonan) {
                    const matchedCategory = Object.keys(parameterOptions).find(
                      cat => cat.toLowerCase() === selectedPermohonan.jenis_sample.toLowerCase()
                    );
                    
                    const cat = matchedCategory || 'Air';
                    setCategory(cat);
                    
                    // Look for synced on-site sampling data
                    const activeReg = selectedPermohonan.registrasi_sample?.[0];
                    const activeSample = activeReg?.samples?.[0] || activeReg?.sample;
                    
                    let autoParams: string[] = [];
                    if (activeSample) {
                      // 1. Prefill sample name with the custom location/name inputted from field
                      if (activeSample.lokasi_pengambilan) {
                        setSampleName(activeSample.lokasi_pengambilan);
                      }
                      
                      // 2. Parse on-site parameters from catatan payload
                      if (activeSample.catatan) {
                        let parsed: Record<string, any> = {};
                        try {
                          parsed = typeof activeSample.catatan === 'string' 
                            ? JSON.parse(activeSample.catatan) 
                            : activeSample.catatan;
                        } catch (err) {
                          console.error('Error parsing sample catatan:', err);
                        }
                        
                        const currentParamGroup = parameterOptions[cat] || parameterOptions['Air'];
                        
                        // Check which keys in parsed JSON map to our onSite parameter options
                        Object.entries(parsed).forEach(([key, val]) => {
                          // Skip metadata keys
                          if (key === 'kode_sample' || key === 'catatan_petugas') return;
                          
                          if (val !== null && val !== undefined && val !== '') {
                            // Find matching parameter name
                            const matchedParam = currentParamGroup.onSite.find(param => 
                              matchKeyToParamName(key, param)
                            );
                            if (matchedParam) {
                              autoParams.push(matchedParam);
                            }
                          }
                        });
                      }
                    } else {
                      setSampleName('');
                    }
                    
                    setSelectedParams(autoParams);
                  } else {
                    setSampleName('');
                    setSelectedParams([]);
                  }
                }}
                className="w-full px-3 py-2.5 rounded-lg border border-outline-variant bg-surface-container-low text-xs text-on-surface outline-none focus:border-primary focus:bg-white transition-all cursor-pointer font-medium"
              >
                <option value="">-- Pilih Permohonan --</option>
                {permohonanList.map((p) => (
                  <option key={p.id} value={p.id}>
                    {p.nomor_permohonan} - {p.nama_pemohon} ({p.jenis_sample})
                  </option>
                ))}
              </select>
              {sampleSource && (() => {
                const selectedPermohonan = permohonanList.find(p => p.id === sampleSource);
                const activeReg = selectedPermohonan?.registrasi_sample?.[0];
                const activeSample = activeReg?.samples?.[0] || activeReg?.sample;
                if (activeSample) {
                  return (
                    <div className="p-2.5 bg-status-success/10 border border-status-success/20 rounded-lg flex items-center gap-2 text-[11px] text-status-success font-semibold animate-pulse mt-2">
                      <Check size={14} className="shrink-0" />
                      <span>Data sampling lapangan terdeteksi! Parameter On-Site telah otomatis dipilih.</span>
                    </div>
                  );
                }
                return null;
              })()}
              <p className="text-[10px] text-on-surface-variant">Pilih permohonan untuk menghubungkan sampel & menyamakan kategori</p>
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
                <option value="Air">Air (Sanitasi / Minum)</option>
                <option value="Air Limbah">Air Limbah Industri / Domestik</option>
                <option value="Makanan & Minuman">Makanan & Minuman</option>
                <option value="Udara">Kualitas Udara</option>
                <option value="Tanah">Tanah & Sedimen</option>
                <option value="Swab Lingkungan">Swab Lingkungan</option>
              </select>
            </div>
            
            <div className="bg-primary/5 p-3 rounded-lg border border-primary/10 flex items-start gap-2 text-[11px] text-primary leading-normal">
              <AlertCircle size={16} className="shrink-0 mt-0.5" />
              <span>Sistem otomatis mencocokkan baku mutu standar nasional untuk parameter yang dipilih.</span>
            </div>
          </div>

          {/* Parameter Selector — Grouped by On Site / Laboratorium */}
          <div className="space-y-4">
            <div className="flex items-center justify-between">
              <label className="block font-label-sm text-[11px] font-bold text-on-surface-variant uppercase">Pilih Parameter Pengujian Uji *</label>
              <span className="text-[10px] font-bold text-primary bg-primary/8 px-2.5 py-1 rounded-full">
                {selectedParams.length} / {allParams.length} dipilih
              </span>
            </div>

            <div className="bg-surface-container-low p-4 rounded-xl border border-outline-variant space-y-5">
              {renderParamSection(
                'Parameter On Site (Lapangan)',
                <MapPin size={13} className="text-status-success" />,
                currentParamGroup.onSite,
                'onSite'
              )}

              <div className="border-t border-outline-variant" />

              {renderParamSection(
                'Parameter Uji Laboratorium',
                <FlaskConical size={13} className="text-primary" />,
                currentParamGroup.laboratorium,
                'laboratorium'
              )}
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
