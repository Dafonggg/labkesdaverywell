import React, { useState } from 'react';

interface Stage {
  name: string;
  indonesianName: string;
  percentage: number;
  colorClass: string;
  bgClass: string;
  count: number;
}

const WorkflowDistribution: React.FC = () => {
  const [hoveredIndex, setHoveredIndex] = useState<number | null>(null);

  const stages: Stage[] = [
    {
      name: 'Permohonan',
      indonesianName: 'Registrasi',
      percentage: 25,
      colorClass: 'text-primary',
      bgClass: 'bg-primary/20 hover:bg-primary/30',
      count: 312,
    },
    {
      name: 'Sampling',
      indonesianName: 'Lapangan',
      percentage: 15,
      colorClass: 'text-primary-container',
      bgClass: 'bg-primary/40 hover:bg-primary/50',
      count: 187,
    },
    {
      name: 'Analisis',
      indonesianName: 'Laboratorium',
      percentage: 40,
      colorClass: 'text-primary-fixed-dim',
      bgClass: 'bg-primary/60 hover:bg-primary/70',
      count: 499,
    },
    {
      name: 'Verifikasi',
      indonesianName: 'QC',
      percentage: 10,
      colorClass: 'text-status-warning',
      bgClass: 'bg-status-warning/40 hover:bg-status-warning/50',
      count: 125,
    },
    {
      name: 'Selesai',
      indonesianName: 'Final',
      percentage: 10,
      colorClass: 'text-status-success',
      bgClass: 'bg-status-success/40 hover:bg-status-success/50',
      count: 125,
    },
  ];

  return (
    <div className="bg-surface rounded-xl p-6 soft-shadow border border-outline-variant/30 transition-all">
      <div className="flex justify-between items-center mb-6">
        <h3 className="font-headline-sm text-lg font-bold text-on-surface">Distribusi Alur Kerja</h3>
        <span className="text-xs text-on-surface-variant font-medium bg-surface-container-low px-2.5 py-1 rounded-lg">
          Permohonan Aktif: 1.248
        </span>
      </div>

      {/* Stacked Progress Bar */}
      <div className="relative h-12 bg-surface-container-low rounded-full overflow-hidden flex mb-4 soft-shadow">
        {stages.map((stage, idx) => {
          const isHovered = hoveredIndex === idx;
          const isAnyHovered = hoveredIndex !== null;
          return (
            <div
              key={idx}
              className={`h-full flex items-center justify-center border-r border-surface last:border-r-0 transition-all duration-300 cursor-pointer ${stage.bgClass} ${
                isAnyHovered && !isHovered ? 'opacity-40 scale-95' : 'opacity-100 scale-100'
              }`}
              style={{ width: `${stage.percentage}%` }}
              onMouseEnter={() => setHoveredIndex(idx)}
              onMouseLeave={() => setHoveredIndex(null)}
            >
              {stage.percentage >= 10 && (
                <span className={`font-label-sm text-xs font-semibold ${stage.colorClass} truncate px-1 transition-transform duration-200 ${isHovered ? 'scale-105' : ''}`}>
                  {stage.indonesianName} ({stage.percentage}%)
                </span>
              )}
            </div>
          );
        })}
      </div>

      {/* Info popover on hover */}
      <div className="h-6 flex items-center justify-center mb-2">
        {hoveredIndex !== null ? (
          <p className="text-xs font-semibold text-primary transition-all duration-200 animate-fadeIn">
            {stages[hoveredIndex].name} ({stages[hoveredIndex].indonesianName}): <span className="font-bold text-on-surface">{stages[hoveredIndex].count}</span> kasus pada tahap siklus saat ini.
          </p>
        ) : (
          <p className="text-xs text-on-surface-variant italic">Arahkan kursor ke segmen untuk melihat rincian analitik.</p>
        )}
      </div>

      {/* Legend */}
      <div className="flex flex-wrap justify-between gap-4 mt-4 border-t border-outline-variant/20 pt-4">
        {stages.map((stage, idx) => (
          <div
            key={idx}
            className={`flex items-center gap-2 cursor-pointer transition-all duration-250 ${
              hoveredIndex === idx ? 'scale-105 font-semibold text-primary' : 'text-on-surface-variant'
            }`}
            onMouseEnter={() => setHoveredIndex(idx)}
            onMouseLeave={() => setHoveredIndex(null)}
          >
            <div className={`w-3.5 h-3.5 rounded-full ${stage.bgClass.split(' ')[0]}`} />
            <span className="font-body-sm text-xs">
              {stage.name} <span className="text-[10px] opacity-75">({stage.indonesianName})</span>
            </span>
          </div>
        ))}
      </div>
    </div>
  );
};

export default WorkflowDistribution;
