# UI/UX Design System
## Sistem Informasi Terintegrasi Pengelolaan Pengujian Sampel Lingkungan
### Labkesda Purwakarta

---

# 1. Overview Project

## Nama Sistem
SIM Labkesda Purwakarta

## Platform
- Web Admin Dashboard
- Mobile App Petugas Lapangan (Expo React Native)

## Tujuan Desain
Mendesain sistem laboratorium lingkungan yang:
- Profesional
- Bersih
- Mudah digunakan
- Cepat dipahami
- Mendukung workflow laboratorium ISO/IEC 17025
- Mobile friendly
- Efisien untuk operasional harian laboratorium

## Target User
1. Admin Laboratorium
2. Petugas Lapangan
3. Petugas Laboratorium
4. Quality Control (QC)
5. Analis Laboratorium
6. Kepala UPTD

---

# 2. Design Principles

## Core Principles

### 1. Clarity First
Semua informasi harus mudah dibaca dan tidak membingungkan.

### 2. Workflow Driven
UI mengikuti proses bisnis laboratorium:
Permohonan → Sampling → Registrasi → Pengujian → QC → Laporan → Approval → Arsip

### 3. Minimal Cognitive Load
Pengguna laboratorium bekerja dengan data kompleks.
UI harus sederhana dan fokus pada tugas utama.

### 4. Fast Operational Access
Aksi penting harus bisa dilakukan maksimal 1-2 klik.

### 5. Data Integrity
Status, validasi, approval, dan audit trail harus terlihat jelas.

### 6. Offline Friendly
Mobile app harus tetap usable saat offline.

---

# 3. Visual Identity

## Brand Personality
- Professional
- Scientific
- Clean
- Government Institution
- Modern Digital Laboratory
- Trustworthy

## Design Style
Modern Government Dashboard + Scientific Laboratory Interface

## UI Style
- Minimal
- Soft rounded corner
- Spacious layout
- Clean table design
- Strong hierarchy
- High readability

---

# 4. Color Palette

## Primary Colors
- Primary Green: #0B8658
- Dark Green: #0B6B46
- Cream Background: #FFFCF5
- Secondary Cream: #F4E6C3
- Dark Text: #323232

## Status Colors
- Success: #16A34A
- Warning: #F59E0B
- Danger: #DC2626
- Info: #2563EB

## Neutral Colors
- White: #FFFFFF
- Gray 100: #F5F5F5
- Gray 200: #E5E5E5
- Gray 400: #A3A3A3
- Gray 700: #404040

## Usage Rules
- Green untuk aksi utama
- Merah hanya untuk reject/error
- Kuning untuk pending/warning
- Biru untuk informasi
- Background dominan putih dan cream

---

# 5. Typography

## Font Family
Primary:
- Inter
Alternative:
- Poppins

## Heading
- H1: 32px Bold
- H2: 28px Bold
- H3: 24px SemiBold
- H4: 20px SemiBold

## Body
- Body Large: 16px Regular
- Body Medium: 14px Regular
- Small Text: 12px Regular

## Rules
- Gunakan line-height lega
- Hindari teks terlalu rapat
- Gunakan weight untuk hierarchy

---

# 6. Layout System

## Web Layout

### Sidebar
- Fixed sidebar kiri
- Collapsible
- Width: 280px
- Background dark green

### Topbar
- Tinggi 72px
- Search
- Notification
- User profile
- Breadcrumb

### Content Area
- Padding 24px
- Max width large
- Responsive grid

---

# 7. Mobile Layout

## Mobile Principles
- One hand usability
- Large tap area
- Offline indicator
- Fast form interaction

## Bottom Navigation
Menu:
1. Dashboard
2. Jadwal
3. Sampling
4. Sinkronisasi
5. Profil

## Mobile Style
- Floating action button
- Sticky sync status
- Offline badge
- Large primary button

---

# 8. Dashboard Design

## Admin Dashboard

### Widgets
- Total Permohonan
- Jadwal Hari Ini
- Sampel Pending
- Pengujian Berjalan
- Pending QC
- Laporan Final

### Charts
- Permohonan per bulan
- Status pengujian
- Statistik layanan

### Quick Actions
- Tambah Permohonan
- Registrasi Sampel
- Buat Jadwal
- Generate Laporan

---

# 9. Table Design

## Table Rules
- Sticky header
- Zebra row optional
- Searchable
- Sortable
- Pagination
- Responsive

## Status Badge
Gunakan pill badge:
- Pending
- Approved
- Rejected
- Draft
- Final
- Completed

## Row Actions
Gunakan icon:
- View
- Edit
- Approve
- Reject
- Print
- Download

---

# 10. Form Design

## Principles
- Simple
- Sequential
- Grouped logically
- Inline validation

## Input Style
- Height 48px
- Rounded 12px
- Soft border
- Clear error state

## Validation
- Error langsung muncul
- Gunakan helper text
- Highlight field error

## Required Fields
Gunakan tanda merah kecil (*)

---

# 11. Workflow Visualization

## Status Flow Design

### Permohonan
Pending → Dijadwalkan → Sampling → Registrasi → Pengujian → QC → Laporan → Final

Gunakan:
- Stepper
- Timeline
- Progress badge

---

# 12. Sampling Mobile UX

## Offline First Experience

### Offline State
- Banner:
  "Mode Offline Aktif"

### Sync Queue
- Tampilkan jumlah data pending sync

### GPS Capture
- Auto detect lokasi
- Manual retry

### Camera
- Direct camera access
- Preview image
- Compress image otomatis

---

# 13. Laboratory Input UX

## Input Hasil Uji

### Layout
- Table based input
- Parameter kiri
- Nilai kanan

### Auto Validation
- Bandingkan dengan baku mutu otomatis

### Warning State
Jika melewati baku mutu:
- Highlight kuning/merah
- Muncul warning icon

---

# 14. QC Verification UX

## QC Dashboard

### Fokus Utama
- Approve cepat
- Lihat abnormal result
- Reject dengan catatan

## Verification Card
Tampilkan:
- Parameter
- Nilai
- Baku mutu
- Status
- Catatan analis

## Actions
- Approve
- Reject
- Request Retest

---

# 15. Report Design

## Laporan PDF Style
- Formal
- Government document
- Scientific format

## Sections
1. Header instansi
2. Informasi sampel
3. Hasil pengujian
4. Validasi QC
5. Approval Kepala UPTD
6. QR/hash verification

## PDF Characteristics
- Printable A4
- Clean table
- Signature section
- Official appearance

---

# 16. Notification System

## Notification Types
- Jadwal sampling baru
- QC reject
- Approval pending
- Sync berhasil
- Sync gagal
- Laporan final selesai

## Notification Style
- Toast
- Dropdown notification
- Mobile push notification

---

# 17. Accessibility

## Rules
- Contrast tinggi
- Font readable
- Minimal font 14px
- Large tap target
- Keyboard accessible

## Important
Sistem digunakan operasional harian laboratorium.
UI harus nyaman dipakai berjam-jam.

---

# 18. Security UX

## Sensitive Data
- Hide customer identity pada label sampel
- Confirmation dialog untuk approve/reject
- Session timeout warning

## Audit Awareness
Tampilkan:
- Last updated by
- Timestamp
- Activity log

---

# 19. Empty States

## Empty State Style
Gunakan ilustrasi sederhana + CTA

Contoh:
- "Belum ada permohonan"
- "Belum ada jadwal sampling"
- "Belum ada laporan"

---

# 20. Loading States

## Loading UX
- Skeleton loading
- Progress indicator
- Disable duplicate submit

## Sync Loading
- Show upload progress
- Queue indicator

---

# 21. Responsive Rules

## Breakpoints
- Mobile: <768px
- Tablet: 768px
- Desktop: 1280px+

## Priority
Desktop first untuk web.
Mobile optimized untuk petugas lapangan.

---

# 22. Design Components

## Required Components
- Sidebar
- Topbar
- Card
- Table
- Modal
- Drawer
- Stepper
- Timeline
- Badge
- Toast
- Tabs
- Accordion
- File upload
- Image preview
- QR code section

---

# 23. Design Inspirations

## References
- Modern laboratory dashboard
- Government digital service
- Scientific management system
- Clean enterprise SaaS

## Feel
Gabungan:
- Enterprise dashboard
- Scientific system
- Government service platform

---

# 24. UX Priorities

## Most Important UX
1. Fast operational workflow
2. Easy data input
3. Clear status tracking
4. Minimal error
5. Easy approval flow
6. Reliable offline sync

---

# 25. Technical UI Notes

## Frontend Stack
### Web
- React + Vite
- TailwindCSS
- Axios
- TanStack Table
- React Query

### Mobile
- Expo React Native
- NativeWind
- Expo SQLite
- React Query
- Zustand

---

# 26. Final Goal

Desain harus terasa seperti:
- Sistem laboratorium profesional modern
- Mudah digunakan pegawai pemerintahan
- Mendukung workflow ISO/IEC 17025
- Cepat untuk operasional harian
- Stabil untuk penggunaan jangka panjang
- Modern tetapi tetap formal institusi pemerintah