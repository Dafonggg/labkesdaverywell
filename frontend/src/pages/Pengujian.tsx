import React, { useState } from 'react';
import { FlaskConical, AlertTriangle, CheckCircle, Save } from 'lucide-react';
import { toast } from 'sonner';
import { useParameterUji, useSubmitHasilUji } from '@/hooks/usePengujian';
import { useRegistrasiList } from '@/hooks/useRegistrasi';

interface LocalParam {
  id: string;
  name: string;
  unit: string;
  standardLimit: string;
  enteredValue: string;
}

const Pengujian: React.FC = () => {
  const { data: paramResponse } = useParameterUji();
  const { data: sampleResponse, isLoading: samplesLoading } = useRegistrasiList();
  const submitMutation = useSubmitHasilUji();

  const parameters = paramResponse?.data || [];
  const samples = sampleResponse?.data || [];
  const [selectedSampleIdx, setSelectedSampleIdx] = useState(0);

  // Build local param state for editing
  const [localParams, setLocalParams] = useState<LocalParam[]>(() =>
    parameters.map(p => ({
      id: p.id,
      name: p.nama_parameter,
      unit: p.satuan,
      standardLimit: p.baku_mutu || '-',
      enteredValue: '',
    }))
  );

  // Update local params when API data loads
  React.useEffect(() => {
    if (parameters.length > 0 && localParams.length === 0) {
      setLocalParams(
        parameters.map(p => ({
          id: p.id,
          name: p.nama_parameter,
          unit: p.satuan,
          standardLimit: p.baku_mutu || '-',
          enteredValue: '',
        }))
      );
    }
  }, [parameters, localParams.length]);

  const handleValueChange = (paramId: string, val: string) => {
    setLocalParams(prev =>
      prev.map(p => (p.id === paramId ? { ...p, enteredValue: val } : p))
    );
  };

  const handleSaveResults = () => {
    const activeSample = samples[selectedSampleIdx];
    if (!activeSample) {
      toast.error('Pilih sampel terlebih dahulu.');
      return;
    }

    const filledParams = localParams.filter(p => p.enteredValue !== '');
    if (filledParams.length === 0) {
      toast.error('Isi minimal satu nilai hasil uji.');
      return;
    }

    // Submit each filled parameter
    filledParams.forEach(p => {
      submitMutation.mutate({
        sample_id: activeSample.sample_id || activeSample.id,
        parameter_uji_id: p.id,
        nilai_hasil: parseFloat(p.enteredValue),
      });
    });
  };

  return (
    <div className="space-y-6">
      <div className="border-b border-outline-variant pb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 className="font-headline-lg text-lg md:text-xl font-extrabold text-on-surface flex items-center gap-2">
            <FlaskConical className="text-primary" />
            Entri Hasil Pengujian Laboratorium
          </h1>
          <p className="font-body-md text-xs text-on-surface-variant mt-1 font-medium">
            Masukkan nilai pengujian parameter laboratorium. Sistem akan memvalidasi terhadap standar baku mutu nasional.
          </p>
        </div>

        {/* Sample Selection */}
        <div className="flex items-center gap-2">
          <span className="font-bold text-[10px] text-on-surface-variant uppercase shrink-0">Sampel Aktif:</span>
          <select
            value={selectedSampleIdx}
            onChange={(e) => setSelectedSampleIdx(parseInt(e.target.value))}
            className="px-3 py-2 rounded-lg border border-outline-variant bg-white font-semibold text-xs text-on-surface cursor-pointer focus:border-primary focus:ring-1 focus:ring-primary outline-none"
          >
            {samplesLoading ? (
              <option>Memuat...</option>
            ) : samples.length === 0 ? (
              <option>Tidak ada sampel</option>
            ) : (
              samples.map((s, idx) => (
                <option key={s.id} value={idx}>
                  {s.kode_sample || s.nomor_registrasi} - {s.status}
                </option>
              ))
            )}
          </select>
        </div>
      </div>

      {/* Main Workspace Grid */}
      <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {/* Param list (Col 8) */}
        <div className="bg-white p-6 rounded-xl border border-outline-variant soft-shadow lg:col-span-8 space-y-4">
          <div className="flex justify-between items-center border-b border-outline-variant pb-3">
            <h3 className="font-headline-md text-sm font-bold text-on-surface">
              Parameter Lembar Uji
            </h3>
          </div>

          <div className="overflow-x-auto">
            <table className="w-full text-left border-collapse">
              <thead>
                <tr className="bg-surface-container border-b border-outline-variant text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">
                  <th className="p-3">Nama Parameter</th>
                  <th className="p-3">Satuan</th>
                  <th className="p-3">Baku Mutu</th>
                  <th className="p-3 w-[140px]">Nilai Hasil Uji</th>
                  <th className="p-3">Status</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-outline-variant text-xs text-on-surface font-medium">
                {localParams.length === 0 ? (
                  <tr>
                    <td colSpan={5} className="p-8 text-center text-on-surface-variant">
                      {parameters.length === 0 ? 'Memuat parameter...' : 'Tidak ada parameter tersedia.'}
                    </td>
                  </tr>
                ) : (
                  localParams.map((param) => {
                    const num = parseFloat(param.enteredValue);
                    const isOut = !isNaN(num) && param.standardLimit !== '-' && num > parseFloat(param.standardLimit.replace(/[^0-9.]/g, '') || '999999');
                    return (
                      <tr 
                        key={param.id} 
                        className={`transition-all ${isOut ? 'bg-status-danger/5 hover:bg-status-danger/10' : 'hover:bg-surface-container-low'}`}
                      >
                        <td className="p-3 font-semibold text-on-surface">{param.name}</td>
                        <td className="p-3 font-semibold text-on-surface-variant">{param.unit}</td>
                        <td className="p-3 font-bold text-on-surface-variant">{param.standardLimit}</td>
                        <td className="p-3">
                          <input
                            type="text"
                            value={param.enteredValue}
                            onChange={(e) => handleValueChange(param.id, e.target.value)}
                            className={`w-full px-2.5 py-1.5 rounded-lg border text-center font-bold text-xs outline-none transition-all ${
                              isOut
                                ? 'border-status-danger bg-white text-status-danger focus:ring-1 focus:ring-status-danger'
                                : 'border-outline-variant bg-surface-container-low focus:border-primary focus:bg-white focus:ring-1 focus:ring-primary'
                            }`}
                          />
                        </td>
                        <td className="p-3">
                          {param.enteredValue === '' ? (
                            <span className="text-[10px] text-on-surface-variant">-</span>
                          ) : isOut ? (
                            <div className="flex items-center gap-1 text-status-danger font-bold text-[10px]">
                              <AlertTriangle size={14} className="animate-bounce" />
                              <span>Melebihi Mutu</span>
                            </div>
                          ) : (
                            <div className="flex items-center gap-1 text-status-success font-bold text-[10px]">
                              <CheckCircle size={14} />
                              <span>Normal (Aman)</span>
                            </div>
                          )}
                        </td>
                      </tr>
                    );
                  })
                )}
              </tbody>
            </table>
          </div>

          <div className="flex justify-end gap-3 border-t border-outline-variant pt-4">
            <button 
              onClick={() => setLocalParams(prev => prev.map(p => ({ ...p, enteredValue: '' })))}
              className="px-4 py-2 border border-outline-variant text-on-surface rounded-lg font-semibold text-xs hover:bg-surface-container transition-all cursor-pointer soft-shadow"
            >
              Reset Form
            </button>
            <button 
              onClick={handleSaveResults}
              disabled={submitMutation.isPending}
              className="flex items-center gap-2 bg-primary text-on-primary px-5 py-2 rounded-lg hover:bg-primary-container transition-all font-semibold text-xs cursor-pointer soft-shadow hover-lift disabled:opacity-80"
            >
              <Save size={14} />
              {submitMutation.isPending ? 'Menyimpan...' : 'Simpan & Ajukan Hasil'}
            </button>
          </div>
        </div>

        {/* Technical information guidelines (Col 4) */}
        <div className="lg:col-span-4 space-y-6">
          <div className="bg-white p-6 rounded-xl border border-outline-variant soft-shadow space-y-4">
            <h4 className="font-headline-sm text-xs font-bold text-on-surface border-b border-outline-variant pb-3 uppercase tracking-wider">
              Panduan ISO/IEC 17025
            </h4>
            <ul className="space-y-3.5 text-[11px] text-on-surface-variant leading-relaxed">
              <li className="flex gap-2">
                <span className="w-1.5 h-1.5 bg-primary rounded-full shrink-0 mt-1.5" />
                <span>Setiap hasil pengujian di luar ambang batas baku mutu wajib diuji ulang (retest) untuk verifikasi akurasi analitik.</span>
              </li>
              <li className="flex gap-2">
                <span className="w-1.5 h-1.5 bg-primary rounded-full shrink-0 mt-1.5" />
                <span>Harap melampirkan catatan kalibrasi alat spektrofotometer jika nilai COD/TSS melampaui 100 mg/L.</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Pengujian;
