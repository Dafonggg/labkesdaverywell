````md id="mobile-md-labkesda"
# mobile.md
## Mobile Architecture & Development Guideline
### SIM Labkesda Purwakarta

---

# 1. Overview

Aplikasi mobile SIM Labkesda Purwakarta digunakan khusus untuk:
- Petugas Lapangan
- Proses pengambilan sampel lingkungan
- Input data lapangan
- Offline-first synchronization
- GPS & dokumentasi sampling

Mobile app dirancang untuk:
- Cepat digunakan di lapangan
- Stabil pada koneksi buruk
- Mudah digunakan satu tangan
- Meminimalkan human error
- Mendukung workflow ISO/IEC 17025

---

# 2. Mobile Stack

## Core Stack

### Framework
- Expo React Native

### Language
- TypeScript

### Navigation
- Expo Router

### Styling
- NativeWind
- TailwindCSS

### State Management
- Zustand

### Data Fetching
- TanStack Query
- Axios

### Offline Storage
- Expo SQLite

### Forms
- React Hook Form
- Zod

### Camera
- Expo Camera
- Expo Image Picker

### GPS
- Expo Location

### File System
- Expo FileSystem

### Notifications
- Expo Notifications

### Secure Storage
- Expo Secure Store

---

# 3. Mobile Goals

## Goals
Aplikasi mobile harus:
- Offline first
- Fast sync
- Stable
- Lightweight
- Easy input
- Reliable

## UX Goals
- Maksimal 1-2 tap untuk aksi utama
- Form mudah digunakan di lapangan
- Sinkronisasi jelas
- Tidak kehilangan data saat offline

---

# 4. Architecture Pattern

## Pattern
Menggunakan:
- Feature based architecture
- Modular architecture
- Offline-first architecture
- Clean separation

## Principles
- Reusable component
- Local-first data
- Sync queue
- API abstraction
- Lightweight rendering

---

# 5. Folder Structure

```bash
src/
├── app/
├── features/
├── components/
├── services/
├── database/
├── hooks/
├── stores/
├── utils/
├── constants/
├── providers/
├── types/
├── assets/
└── styles/
````

---

# 6. Detailed Structure

## app/

Routing utama expo router.

## features/

Feature bisnis mobile.

```bash id="s4nbz6"
features/
├── auth/
├── dashboard/
├── jadwal/
├── sampling/
├── sinkronisasi/
├── profile/
└── notifikasi/
```

## database/

Berisi:

* SQLite schema
* migration
* local repository
* sync queue

---

# 7. Offline First Architecture

## Core Principle

Aplikasi harus tetap dapat digunakan:

* tanpa internet
* jaringan buruk
* koneksi putus

## Strategy

* Simpan data lokal terlebih dahulu
* Sync ke server saat online

---

# 8. SQLite Architecture

## SQLite Usage

Digunakan untuk:

* jadwal sampling
* draft form sampling
* queue sinkronisasi
* data temporary
* offline cache

## Important

SQLite menjadi source utama saat offline.

---

# 9. Database Tables

## Local SQLite Tables

```sql id="a6e3z4"
users
jadwal_sampling
draft_sampling
sampling_queue
sync_logs
offline_files
```

---

# 10. Sync System

## Sync Strategy

Menggunakan:

* batch synchronization
* queue system
* retry mechanism

## Flow

```text id="ow20b5"
Create Data
→ Save SQLite
→ Queue Pending
→ Detect Internet
→ Upload Batch
→ Success Remove Queue
```

---

# 11. Retry Mechanism

## Retry Policy

Exponential backoff:

* 5 detik
* 10 detik
* 30 detik
* maksimal 5x retry

## Failed Sync

Jika gagal:

* tetap tersimpan lokal
* tampilkan status pending

---

# 12. Authentication System

## Authentication

Menggunakan:

* JWT
* Refresh token

## Storage

Gunakan:

* Expo Secure Store

## Features

* auto login
* session persistence
* token refresh

---

# 13. Mobile Navigation

## Navigation Type

### Bottom Tabs

* Dashboard
* Jadwal
* Sampling
* Sinkronisasi
* Profil

### Stack Navigation

Untuk:

* detail jadwal
* form sampling
* preview foto

---

# 14. Mobile Layout

## Design Principles

* one hand usability
* large touch area
* clean spacing
* minimal distraction

## Safe Area

Gunakan:

* react-native-safe-area-context

---

# 15. Dashboard Screen

## Dashboard Content

* jadwal hari ini
* jumlah pending sync
* status internet
* quick action
* notifikasi

## Quick Actions

* mulai sampling
* sinkronisasi
* lihat jadwal

---

# 16. Jadwal Sampling Screen

## Features

* daftar jadwal
* filter tanggal
* status sampling
* detail lokasi

## Card Content

* nama lokasi
* tanggal
* jenis sampel
* status
* tombol mulai

---

# 17. Sampling Form Architecture

## Form Sections

### Informasi Lokasi

* GPS otomatis
* alamat lokasi

### Informasi Sampel

* jenis sampel
* parameter

### Kondisi Lingkungan

* cuaca
* suhu
* catatan

### Dokumentasi

* foto lokasi
* foto sampel

---

# 18. GPS System

## GPS Features

* auto detect location
* coordinate validation
* retry location
* location accuracy

## Important

GPS wajib sebelum submit sampling.

---

# 19. Camera System

## Features

* open camera langsung
* compress image otomatis
* preview image
* retake image

## Rules

* optimize size upload
* tetap readable

---

# 20. File Upload System

## Upload Strategy

Saat offline:

* file disimpan lokal

Saat online:

* upload bersama batch sync

## Supported Files

* JPG
* PNG

---

# 21. Sync Screen

## Features

* jumlah pending sync
* sync history
* upload progress
* retry failed sync

## Status

* online
* offline
* syncing
* failed
* completed

---

# 22. Network Detection

## Internet Detection

Gunakan:

* expo-network

## Behavior

Jika offline:

* tampilkan banner merah
* disable API realtime
* aktifkan local mode

---

# 23. Notification System

## Notifications

* jadwal baru
* sync berhasil
* sync gagal
* reminder sampling

## Push Notification

Gunakan:

* Expo Notifications

---

# 24. API Architecture

## Axios Instance

Features:

* auth interceptor
* refresh token
* retry request
* timeout

## Structure

```bash id="1xmy9g"
services/
├── axios.ts
├── auth.service.ts
├── sampling.service.ts
├── jadwal.service.ts
└── sync.service.ts
```

---

# 25. TanStack Query Usage

## Usage

Digunakan untuk:

* API fetch
* cache
* mutation
* background refetch

## Jangan Gunakan Untuk

* offline queue
* local draft

Gunakan SQLite untuk itu.

---

# 26. Zustand Usage

## Zustand Stores

Digunakan untuk:

* auth
* sync state
* network state
* app state

## Example

```bash id="ow4rdb"
stores/
├── auth.store.ts
├── sync.store.ts
├── network.store.ts
└── app.store.ts
```

---

# 27. Form Validation

## Validation Stack

* React Hook Form
* Zod

## Validation Rules

* realtime validation
* required GPS
* required photo
* required parameter

---

# 28. Mobile UI System

## Design Style

* modern field app
* clean operational UI
* high readability

## UI Characteristics

* rounded card
* soft shadow
* large button
* simple interaction

---

# 29. Theme Colors

## Main Colors

```css id="mg9dr6"
Primary: #0B8658
Dark Green: #0B6B46
Cream: #F4E6C3
Background: #FFFCF5
Text: #323232
```

## Status

```css id="8gc0h0"
Success: #16A34A
Danger: #DC2626
Warning: #F59E0B
Info: #2563EB
```

---

# 30. Important Mobile Components

## Core Components

* AppButton
* AppInput
* AppCard
* OfflineBanner
* SyncStatus
* UploadPreview
* GPSCard
* SamplingStepper
* StatusBadge

---

# 31. UX Rules

## UX Principles

* minimize typing
* use dropdown
* use auto fill
* use quick actions

## Avoid

* long forms
* tiny buttons
* complex navigation

---

# 32. Error Handling

## Error Types

* network error
* sync error
* GPS error
* upload error

## UX

* tampilkan pesan jelas
* jangan crash app
* data tetap aman

---

# 33. Loading State

## Loading UI

Gunakan:

* skeleton
* spinner
* progress upload

## Important

Jangan freeze UI saat sync.

---

# 34. Security Standards

## Security Features

* secure token storage
* encrypted auth
* protected routes
* secure upload

## Important

Data sampling tidak boleh hilang.

---

# 35. Performance Optimization

## Optimization

* image compression
* lazy rendering
* flatlist optimization
* memoization

## Goals

* smooth scrolling
* fast form input
* low memory usage

---

# 36. Accessibility

## Accessibility Rules

* readable font
* large touch area
* proper contrast

## Minimum Touch Size

44x44

---

# 37. Permissions

## Required Permissions

### Android

* Camera
* Location
* Storage
* Notifications
* Internet

---

# 38. Environment Variables

## Example

```env id="9i58w5"
EXPO_PUBLIC_API_URL=
EXPO_PUBLIC_APP_ENV=
```

---

# 39. Build & Deployment

## Build System

Gunakan:

* EAS Build

## Android Target

* Android 8+

## Distribution

* APK internal
* Play Store private
* direct install

---

# 40. Future Scalability

Mobile app harus siap untuk:

* realtime websocket
* background sync
* barcode scanner
* QR scanner
* GIS map
* multi cabang
* digital signature

---

# 41. Final Vision

Mobile app harus terasa seperti:

* professional field operation app
* modern environmental sampling tool
* stable offline-first platform
* fast laboratory field workflow system

Bukan sekadar form mobile biasa.

```
```
