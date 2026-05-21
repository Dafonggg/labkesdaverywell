import React, { useState } from 'react';

interface DayData {
  day: string;
  value: number;
  heightPercent: number;
}

const WeeklyThroughput: React.FC = () => {
  const [filter, setFilter] = useState<'7days' | 'month'>('7days');
  const [activeDayIdx, setActiveDayIdx] = useState<number | null>(null);

  const weeklyData: DayData[] = [
    { day: 'Mon', value: 45, heightPercent: 40 },
    { day: 'Tue', value: 72, heightPercent: 65 },
    { day: 'Wed', value: 105, heightPercent: 90 },
    { day: 'Thu', value: 55, heightPercent: 50 },
    { day: 'Fri', value: 88, heightPercent: 75 },
    { day: 'Sat', value: 22, heightPercent: 20 },
    { day: 'Sun', value: 12, heightPercent: 10 },
  ];

  const monthlyData: DayData[] = [
    { day: 'W1', value: 340, heightPercent: 70 },
    { day: 'W2', value: 410, heightPercent: 85 },
    { day: 'W3', value: 480, heightPercent: 95 },
    { day: 'W4', value: 280, heightPercent: 60 },
  ];

  const currentData = filter === '7days' ? weeklyData : monthlyData;

  return (
    <div className="bg-surface rounded-xl p-6 soft-shadow border border-outline-variant/30 flex flex-col flex-1 h-full">
      <div className="flex justify-between items-center mb-6">
        <div>
          <h3 className="font-headline-sm text-lg font-bold text-on-surface">Weekly Throughput</h3>
          <p className="font-body-sm text-xs text-on-surface-variant mt-0.5">Completed laboratory examinations count</p>
        </div>
        <select
          value={filter}
          onChange={(e) => setFilter(e.target.value as '7days' | 'month')}
          className="bg-surface-container-low border border-outline-variant/30 rounded-lg text-xs font-semibold text-on-surface-variant py-2 pl-3 pr-8 focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all cursor-pointer"
        >
          <option value="7days">Last 7 Days</option>
          <option value="month">This Month</option>
        </select>
      </div>

      {/* Chart Canvas */}
      <div className="flex-1 flex items-end justify-between gap-4 border-b border-outline-variant/30 pb-4 h-48 mt-4 relative">
        {currentData.map((data, idx) => {
          const isHighest = data.value === Math.max(...currentData.map(d => d.value));
          const isHovered = activeDayIdx === idx;
          return (
            <div
              key={idx}
              className="w-full flex flex-col items-center gap-3 group relative cursor-pointer"
              onMouseEnter={() => setActiveDayIdx(idx)}
              onMouseLeave={() => setActiveDayIdx(null)}
            >
              {/* Dynamic Animated Bar */}
              <div 
                className={`w-full rounded-t-md relative transition-all duration-500 ease-out ${
                  isHighest 
                    ? 'bg-primary group-hover:bg-primary-container shadow-md' 
                    : 'bg-primary/60 group-hover:bg-primary/80'
                }`}
                style={{ 
                  height: `${data.heightPercent}%`,
                  minHeight: '8px'
                }}
              >
                {/* Floating Tooltip Indicator */}
                <div className={`
                  absolute -top-10 left-1/2 -translate-x-1/2 bg-inverse-surface text-inverse-on-surface text-[10px] font-semibold px-2 py-1 rounded transition-all duration-200 z-10 pointer-events-none soft-shadow whitespace-nowrap
                  ${isHovered ? 'opacity-100 scale-100 translate-y-0' : 'opacity-0 scale-90 translate-y-2'}
                `}>
                  {data.value} samples
                </div>
              </div>

              {/* Day Label */}
              <span className={`font-body-sm text-xs transition-colors duration-200 ${
                isHighest ? 'text-primary font-bold' : 'text-on-surface-variant'
              }`}>
                {data.day}
              </span>
            </div>
          );
        })}
      </div>
    </div>
  );
};

export default WeeklyThroughput;
