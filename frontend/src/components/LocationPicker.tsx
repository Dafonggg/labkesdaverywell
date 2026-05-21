import React, { useState, useCallback, useEffect } from 'react';
import { MapContainer, TileLayer, Marker, useMapEvents, useMap } from 'react-leaflet';
import L from 'leaflet';
import { MapPin, Navigation, Search } from 'lucide-react';
import 'leaflet/dist/leaflet.css';

// Fix Leaflet default marker icon issue in bundlers
delete (L.Icon.Default.prototype as Record<string, unknown>)._getIconUrl;
L.Icon.Default.mergeOptions({
  iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
  iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
  shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
});

// Custom marker icon with primary color
const customIcon = new L.Icon({
  iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
  iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
  shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
  iconSize: [25, 41],
  iconAnchor: [12, 41],
  popupAnchor: [1, -34],
  shadowSize: [41, 41],
});

interface LocationPickerProps {
  latitude: number | null;
  longitude: number | null;
  onChange: (lat: number, lng: number) => void;
  height?: string;
}

// Inner component to handle map click events
const MapClickHandler: React.FC<{ onClick: (lat: number, lng: number) => void }> = ({ onClick }) => {
  useMapEvents({
    click(e) {
      onClick(e.latlng.lat, e.latlng.lng);
    },
  });
  return null;
};

// Inner component to fly to new coordinates
const FlyToLocation: React.FC<{ center: [number, number] }> = ({ center }) => {
  const map = useMap();
  useEffect(() => {
    map.flyTo(center, 15, { duration: 1.2 });
  }, [center, map]);
  return null;
};

// Default center: Purwakarta, Jawa Barat, Indonesia
const DEFAULT_CENTER: [number, number] = [-6.5562, 107.4467];

const LocationPicker: React.FC<LocationPickerProps> = ({
  latitude,
  longitude,
  onChange,
  height = '280px',
}) => {
  const [searchQuery, setSearchQuery] = useState('');
  const [isSearching, setIsSearching] = useState(false);
  const [flyTarget, setFlyTarget] = useState<[number, number] | null>(null);

  const center: [number, number] = latitude && longitude
    ? [latitude, longitude]
    : DEFAULT_CENTER;

  const handleMapClick = useCallback(
    (lat: number, lng: number) => {
      onChange(lat, lng);
    },
    [onChange]
  );

  // Use Nominatim (OpenStreetMap) geocoding for address search
  const handleSearch = async () => {
    if (!searchQuery.trim()) return;
    setIsSearching(true);
    try {
      const res = await fetch(
        `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchQuery)}&limit=1&countrycodes=id`
      );
      const data = await res.json();
      if (data && data.length > 0) {
        const lat = parseFloat(data[0].lat);
        const lng = parseFloat(data[0].lon);
        onChange(lat, lng);
        setFlyTarget([lat, lng]);
      }
    } catch {
      // silently fail
    }
    setIsSearching(false);
  };

  // Use browser geolocation API
  const handleUseCurrentLocation = () => {
    if (!navigator.geolocation) return;
    navigator.geolocation.getCurrentPosition(
      (pos) => {
        const lat = pos.coords.latitude;
        const lng = pos.coords.longitude;
        onChange(lat, lng);
        setFlyTarget([lat, lng]);
      },
      () => {
        // permission denied or error
      },
      { enableHighAccuracy: true }
    );
  };

  return (
    <div className="space-y-2">
      <label className="block font-label-sm text-[11px] font-bold text-on-surface-variant uppercase flex items-center gap-1.5">
        <MapPin size={13} className="text-primary" />
        Titik Koordinat Lokasi Sampling
      </label>

      {/* Search Bar + GPS Button */}
      <div className="flex gap-2">
        <div className="relative flex-1">
          <Search className="absolute left-2.5 top-1/2 -translate-y-1/2 text-on-surface-variant" size={13} />
          <input
            type="text"
            placeholder="Cari lokasi... (mis. Sungai Citarum Purwakarta)"
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            onKeyDown={(e) => e.key === 'Enter' && (e.preventDefault(), handleSearch())}
            className="w-full pl-8 pr-3 py-2 rounded-lg border border-outline-variant bg-surface-container-low text-[11px] text-on-surface outline-none focus:border-primary transition-all"
          />
        </div>
        <button
          type="button"
          onClick={handleSearch}
          disabled={isSearching}
          className="px-3 py-2 bg-primary text-on-primary rounded-lg text-[10px] font-bold hover:bg-primary-container transition-all cursor-pointer disabled:opacity-70"
        >
          {isSearching ? '...' : 'Cari'}
        </button>
        <button
          type="button"
          onClick={handleUseCurrentLocation}
          title="Gunakan lokasi saat ini"
          className="p-2 bg-white border border-outline-variant rounded-lg text-primary hover:bg-primary/5 transition-all cursor-pointer"
        >
          <Navigation size={14} />
        </button>
      </div>

      {/* Map Container */}
      <div
        className="rounded-xl overflow-hidden border border-outline-variant soft-shadow"
        style={{ height }}
      >
        <MapContainer
          center={center}
          zoom={latitude && longitude ? 15 : 12}
          style={{ height: '100%', width: '100%' }}
          scrollWheelZoom={true}
          className="z-0"
        >
          <TileLayer
            attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
          />
          <MapClickHandler onClick={handleMapClick} />
          {flyTarget && <FlyToLocation center={flyTarget} />}
          {latitude && longitude && (
            <Marker position={[latitude, longitude]} icon={customIcon} />
          )}
        </MapContainer>
      </div>

      {/* Coordinate Display */}
      <div className="flex items-center gap-3">
        <div className="flex-1 grid grid-cols-2 gap-2">
          <div className="relative">
            <span className="absolute left-2.5 top-1/2 -translate-y-1/2 text-[9px] font-bold text-on-surface-variant uppercase">Lat</span>
            <input
              type="text"
              value={latitude?.toFixed(6) || ''}
              onChange={(e) => {
                const v = parseFloat(e.target.value);
                if (!isNaN(v)) onChange(v, longitude || DEFAULT_CENTER[1]);
              }}
              placeholder="-6.556200"
              className="w-full pl-9 pr-2 py-2 rounded-lg border border-outline-variant bg-surface-container-low text-[11px] font-mono text-on-surface outline-none focus:border-primary transition-all"
            />
          </div>
          <div className="relative">
            <span className="absolute left-2.5 top-1/2 -translate-y-1/2 text-[9px] font-bold text-on-surface-variant uppercase">Lng</span>
            <input
              type="text"
              value={longitude?.toFixed(6) || ''}
              onChange={(e) => {
                const v = parseFloat(e.target.value);
                if (!isNaN(v)) onChange(latitude || DEFAULT_CENTER[0], v);
              }}
              placeholder="107.446700"
              className="w-full pl-9 pr-2 py-2 rounded-lg border border-outline-variant bg-surface-container-low text-[11px] font-mono text-on-surface outline-none focus:border-primary transition-all"
            />
          </div>
        </div>
      </div>

      {!latitude && !longitude && (
        <p className="text-[10px] text-on-surface-variant italic">
          Klik pada peta atau gunakan tombol navigasi untuk menandai titik lokasi pengambilan sampel.
        </p>
      )}
    </div>
  );
};

export default LocationPicker;
