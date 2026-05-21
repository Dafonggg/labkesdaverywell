````md id="frontend-md-labkesda"
# frontend.md
## Frontend Architecture & Development Guideline
### SIM Labkesda Purwakarta

---

# 1. Overview

Frontend web untuk SIM Labkesda Purwakarta dirancang sebagai:
- Enterprise dashboard application
- Role-based laboratory management system
- High productivity interface
- Responsive admin panel
- Real-time operational monitoring system

Frontend digunakan oleh:
1. Admin
2. Petugas Laboratorium
3. QC
4. Analis Laboratorium
5. Kepala UPTD

Aplikasi mobile dipisahkan menggunakan Expo React Native.

---

# 2. Frontend Stack

## Core Stack

### Framework
- React 19
- Vite

### Language
- TypeScript

### Styling
- TailwindCSS
- clsx
- tailwind-merge

### State Management
- Zustand

### Data Fetching
- TanStack Query
- Axios

### Routing
- React Router DOM

### Form Handling
- React Hook Form
- Zod

### Table
- TanStack Table

### Charts
- Recharts

### Icons
- Lucide React

### UI Components
- shadcn/ui

### Notification
- Sonner

### Date
- dayjs

---

# 3. Frontend Goals

## Goals
Frontend harus:
- Cepat
- Mudah digunakan
- Stabil
- Scalable
- Modular
- Maintainable

## UX Goals
- Maksimal 2 klik untuk aksi utama
- Input data cepat
- Table responsif
- Status workflow jelas
- Dashboard mudah dipahami

---

# 4. Architecture Pattern

## Pattern
Menggunakan:
- Feature-based architecture
- Modular component structure
- Atomic UI approach
- Separation of concerns

## Structure Principles
- Reusable components
- Decoupled API layer
- Independent feature modules
- Clean folder hierarchy

---

# 5. Folder Structure

```bash
src/
├── app/
├── routes/
├── layouts/
├── pages/
├── features/
├── components/
├── services/
├── hooks/
├── stores/
├── lib/
├── utils/
├── types/
├── constants/
├── assets/
├── styles/
└── providers/
````

---

# 6. Detailed Structure

## app/

Konfigurasi utama aplikasi:

* router
* query client
* providers
* auth setup

## routes/

Berisi:

* protected routes
* role routes
* lazy routes

## layouts/

Layout utama:

* DashboardLayout
* AuthLayout
* MinimalLayout

## pages/

Halaman global:

* Login
* 404
* Unauthorized

## features/

Module berdasarkan fitur bisnis.

Contoh:

```bash
features/
├── auth/
├── dashboard/
├── permohonan/
├── registrasi/
├── sampling/
├── pengujian/
├── qc/
├── laporan/
├── approval/
├── arsip/
└── monitoring/
```

---

# 7. Feature Module Structure

Contoh:

```bash
permohonan/
├── api/
├── components/
├── hooks/
├── pages/
├── schemas/
├── types/
├── utils/
└── store/
```

---

# 8. Routing Structure

## Public Routes

```bash
/login
```

## Protected Routes

### Admin

```bash
/dashboard
/permohonan
/registrasi
/jadwal
/pembayaran
/monitoring
```

### Petugas Laboratorium

```bash
/pengujian
/sample-masuk
```

### QC

```bash
/qc/verifikasi
/qc/history
```

### Analis

```bash
/laporan
/laporan/draft
```

### Kepala UPTD

```bash
/approval
/laporan-final
```

---

# 9. Authentication System

## Authentication

Menggunakan:

* JWT Access Token
* Refresh Token

## Storage

Gunakan:

* httpOnly cookie jika backend support
  atau
* secure localStorage fallback

## Features

* Auto refresh token
* Session timeout
* Auto logout
* RBAC middleware

---

# 10. Axios Architecture

## Axios Instance

Buat centralized axios instance.

Features:

* baseURL
* auth interceptor
* refresh token interceptor
* error interceptor
* timeout handling

## Structure

```bash
services/
├── axios.ts
├── auth.service.ts
├── permohonan.service.ts
├── qc.service.ts
└── laporan.service.ts
```

---

# 11. API Standards

## Response Format

```json
{
  "success": true,
  "message": "Success",
  "data": {}
}
```

## Error Format

```json
{
  "success": false,
  "message": "Validation Error",
  "errors": {}
}
```

---

# 12. State Management

## Zustand Usage

Digunakan untuk:

* auth state
* sidebar state
* theme state
* notification state

## Jangan Gunakan Zustand Untuk

* server state
* API caching

Gunakan TanStack Query untuk itu.

---

# 13. TanStack Query Strategy

## Query Usage

Digunakan untuk:

* fetching
* cache
* mutation
* optimistic update
* retry

## Query Key Standard

```ts
["permohonan"]
["permohonan", id]
["laporan"]
["qc-verifikasi"]
```

---

# 14. Form Architecture

## Form Stack

* React Hook Form
* Zod validation

## Principles

* reusable form field
* inline validation
* loading submit
* disable duplicate submit

---

# 15. Table Architecture

## TanStack Table

Features:

* sorting
* filtering
* pagination
* column visibility
* export
* sticky header

## Common Tables

* permohonan table
* sample table
* QC table
* laporan table

---

# 16. UI Design System

## UI Framework

Gunakan:

* shadcn/ui
* TailwindCSS

## Design Characteristics

* modern enterprise
* soft corner
* clean spacing
* readable typography

---

# 17. Theme Colors

## Main Colors

```css
Primary: #0B8658
Dark: #0B6B46
Cream: #F4E6C3
Background: #FFFCF5
Text: #323232
```

## Status

```css
Success: #16A34A
Danger: #DC2626
Warning: #F59E0B
Info: #2563EB
```

---

# 18. Layout System

## Sidebar

Features:

* collapsible
* role menu
* active indicator

## Topbar

Features:

* breadcrumb
* notification
* profile dropdown
* search

---

# 19. Dashboard System

## Dashboard Widgets

* jumlah permohonan
* sample pending
* pending QC
* laporan final
* pembayaran
* jadwal hari ini

## Dashboard Charts

* statistik bulanan
* status workflow
* parameter pengujian

---

# 20. Workflow UI

## Workflow Status

Gunakan:

* badge
* stepper
* timeline

## Workflow

```text
Permohonan
→ Jadwal
→ Sampling
→ Registrasi
→ Pengujian
→ QC
→ Laporan
→ Approval
→ Arsip
```

---

# 21. Role Based Menu

## Admin

* Dashboard
* Permohonan
* Registrasi
* Jadwal Sampling
* Pembayaran
* Monitoring
* Arsip

## Petugas Lab

* Sample Masuk
* Pengujian
* Hasil Uji

## QC

* Verifikasi
* Riwayat QC

## Analis

* Draft Laporan
* Generate Laporan

## Kepala UPTD

* Approval
* Final Report

---

# 22. Error Handling

## Global Error Handling

* axios interceptor
* toast notification
* fallback UI

## Validation Error

* inline field error
* focus invalid field

---

# 23. Loading State

## Loading UI

Gunakan:

* skeleton
* spinner
* button loading

## Jangan

* blank white screen
* freeze UI

---

# 24. Notification System

## Notification Types

* success
* error
* warning
* info

## Notification Library

Gunakan:

* Sonner

---

# 25. File Upload System

## Upload Features

* drag and drop
* preview
* validation
* progress indicator

## Supported Files

* PDF
* JPG
* PNG
* DOCX

---

# 26. PDF Preview

## Features

* preview laporan
* print
* download

## Libraries

* react-pdf
  atau
* iframe preview

---

# 27. Security Standards

## Frontend Security

* sanitize input
* protected routes
* RBAC
* secure token handling
* CSRF ready

## Prevent

* unauthorized route access
* role manipulation
* token leak

---

# 28. Performance Optimization

## Optimization

* lazy loading
* route splitting
* image optimization
* memoization
* virtualization table

## Target

* fast page load
* responsive dashboard
* smooth table interaction

---

# 29. Accessibility

## Accessibility Rules

* keyboard accessible
* readable contrast
* aria labels
* semantic HTML

---

# 30. Responsive Rules

## Breakpoints

```css
sm: 640px
md: 768px
lg: 1024px
xl: 1280px
2xl: 1536px
```

## Priority

Desktop first.

---

# 31. Reusable Components

## Common Components

```bash
components/
├── ui/
├── table/
├── forms/
├── charts/
├── layout/
├── feedback/
└── workflow/
```

---

# 32. Important Components

## Core Components

* DataTable
* StatusBadge
* WorkflowStepper
* ApprovalCard
* UploadField
* FilterBar
* SearchInput
* EmptyState
* ConfirmDialog

---

# 33. Logging & Monitoring UI

## Monitoring Features

* activity log
* audit trail
* user action history

## Admin Only

Monitoring page hanya untuk admin.

---

# 34. Development Standards

## Naming

Gunakan:

* PascalCase component
* camelCase variable
* kebab-case folder

## Example

```bash
PermohonanTable.tsx
usePermohonan.ts
permohonan.service.ts
```

---

# 35. Code Standards

## Principles

* clean code
* reusable
* strongly typed
* scalable

## Avoid

* giant component
* duplicated logic
* inline business logic

---

# 36. Environment Variables

## Example

```env
VITE_API_URL=
VITE_APP_NAME=
VITE_APP_ENV=
```

---

# 37. Deployment

## Hosting

Frontend deploy:

* Vercel
  atau
* VPS Nginx

## Build

Gunakan:

```bash
npm run build
```

---

# 38. Recommended Libraries

## Utility

* clsx
* lodash
* dayjs

## Table Export

* xlsx

## Print

* react-to-print

## Icons

* lucide-react

---

# 39. Future Scalability

Frontend harus siap untuk:

* multi cabang lab
* realtime notification
* websocket
* dark mode
* analytics dashboard
* GIS integration

---

# 40. Final Vision

Frontend harus terasa seperti:

* modern laboratory enterprise system
* scientific workflow platform
* government-grade digital service
* fast operational dashboard
* stable long-term management platform

Bukan sekadar admin panel biasa.

```
```
