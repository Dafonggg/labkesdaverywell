````md id="sqlite-offline-first-labkesda"
# sqlite.md
## SQLite Offline First Architecture
### SIM Labkesda Purwakarta

---

# 1. Overview

SQLite digunakan pada aplikasi mobile Expo React Native untuk:
- Offline-first workflow
- Penyimpanan lokal sementara
- Queue sinkronisasi
- Cache data lapangan
- Menjamin data tidak hilang saat offline

Sistem offline-first sangat penting karena:
- Petugas lapangan sering berada di area tanpa sinyal
- Sampling harus tetap berjalan
- Data lapangan tidak boleh hilang
- Sinkronisasi dilakukan saat internet tersedia

---

# 2. Core Offline First Principles

## Principles

### Local First
Data selalu disimpan ke SQLite terlebih dahulu.

### Sync Later
Sinkronisasi dilakukan ketika koneksi tersedia.

### Never Lose Data
Data sampling tidak boleh hilang walaupun:
- aplikasi crash
- internet putus
- device restart

### Queue Based Sync
Semua perubahan masuk ke queue sinkronisasi.

---

# 3. SQLite Stack

## Database Engine
- Expo SQLite

## Additional Libraries
- expo-sqlite
- react-native-mmkv (optional cache)
- expo-file-system

---

# 4. Architecture Overview

## Offline Flow

```text
User Input
→ Save SQLite
→ Insert Queue
→ Status Pending
→ Detect Internet
→ Batch Sync API
→ Success Remove Queue
````

---

# 5. SQLite Folder Structure

```bash
src/
├── database/
│   ├── index.ts
│   ├── migrations/
│   ├── repositories/
│   ├── schema/
│   ├── seeders/
│   ├── helpers/
│   └── sync/
```

---

# 6. Database Initialization

## database/index.ts

```ts id="2e2dnf"
import * as SQLite from "expo-sqlite";

export const db = SQLite.openDatabaseSync(
  "labkesda.db"
);
```

---

# 7. SQLite Tables

## Main Local Tables

```text id="3j80c3"
users
jadwal_sampling
draft_sampling
sampling_queue
offline_files
sync_logs
app_settings
```

---

# 8. users Table

```sql
CREATE TABLE IF NOT EXISTS users (
    id TEXT PRIMARY KEY,
    name TEXT,
    email TEXT,
    role TEXT,
    token TEXT,
    created_at TEXT
);
```

---

# 9. jadwal_sampling Table

```sql id="a4t7sq"
CREATE TABLE IF NOT EXISTS jadwal_sampling (
    id TEXT PRIMARY KEY,

    permohonan_id TEXT,

    lokasi TEXT,

    latitude REAL,
    longitude REAL,

    tanggal_sampling TEXT,

    status TEXT,

    synced INTEGER DEFAULT 1,

    created_at TEXT
);
```

---

# 10. draft_sampling Table

## Main Offline Form Table

```sql id="v26e6t"
CREATE TABLE IF NOT EXISTS draft_sampling (
    id TEXT PRIMARY KEY,

    sync_id TEXT UNIQUE,

    jadwal_sampling_id TEXT,

    jenis_sample TEXT,

    kondisi_sample TEXT,

    suhu TEXT,

    cuaca TEXT,

    latitude REAL,
    longitude REAL,

    lokasi_pengambilan TEXT,

    waktu_pengambilan TEXT,

    catatan TEXT,

    sync_status TEXT DEFAULT 'pending',

    retry_count INTEGER DEFAULT 0,

    created_at TEXT,
    updated_at TEXT
);
```

---

# 11. offline_files Table

## Local File Storage

```sql id="12vr3z"
CREATE TABLE IF NOT EXISTS offline_files (
    id TEXT PRIMARY KEY,

    draft_sampling_id TEXT,

    local_uri TEXT,

    file_type TEXT,

    uploaded INTEGER DEFAULT 0,

    created_at TEXT
);
```

---

# 12. sampling_queue Table

## Queue Table

```sql id="c99l3r"
CREATE TABLE IF NOT EXISTS sampling_queue (
    id TEXT PRIMARY KEY,

    reference_id TEXT,

    queue_type TEXT,

    payload TEXT,

    status TEXT DEFAULT 'pending',

    retry_count INTEGER DEFAULT 0,

    last_error TEXT,

    created_at TEXT,
    updated_at TEXT
);
```

---

# 13. sync_logs Table

```sql id="slm1wd"
CREATE TABLE IF NOT EXISTS sync_logs (
    id TEXT PRIMARY KEY,

    sync_type TEXT,

    total_data INTEGER,

    success_data INTEGER,

    failed_data INTEGER,

    status TEXT,

    message TEXT,

    synced_at TEXT
);
```

---

# 14. app_settings Table

```sql id="12e95w"
CREATE TABLE IF NOT EXISTS app_settings (
    key TEXT PRIMARY KEY,
    value TEXT
);
```

---

# 15. Offline Storage Strategy

## What Stored Offline

### Required Offline Data

* jadwal sampling
* draft sampling
* upload queue
* failed sync
* user session

### Optional Cache

* parameter sample
* dropdown master data

---

# 16. Sync Architecture

## Sync Type

### Push Sync

Mobile → Server

Digunakan untuk:

* hasil sampling
* upload foto
* update status

### Pull Sync

Server → Mobile

Digunakan untuk:

* jadwal baru
* update task
* notifikasi

---

# 17. Sync Status

## Status Values

```text id="1yyzqh"
pending
syncing
completed
failed
```

---

# 18. Sync Service Architecture

## Structure

```bash id="fzs21p"
database/
└── sync/
    ├── sync-manager.ts
    ├── push-sync.ts
    ├── pull-sync.ts
    ├── retry-sync.ts
    └── sync-helper.ts
```

---

# 19. Sync Manager

## Responsibilities

* detect internet
* execute queue
* retry failed sync
* upload batch
* cleanup success data

---

# 20. Batch Sync Strategy

## Rules

### Batch Upload

Kirim beberapa data sekaligus.

### Atomic Validation

Jika 1 item gagal:

* rollback batch
  atau
* partial failed mode

### Recommended

Gunakan partial failed mode.

---

# 21. Sync Payload Example

```json id="6nwbzk"
{
  "samples": [
    {
      "sync_id": "uuid",
      "jadwal_sampling_id": "uuid",
      "latitude": -6.551,
      "longitude": 107.446,
      "cuaca": "Cerah"
    }
  ]
}
```

---

# 22. Retry Strategy

## Exponential Backoff

```text id="j9kht6"
Retry 1 → 5 detik
Retry 2 → 10 detik
Retry 3 → 30 detik
Retry 4 → 60 detik
Retry 5 → failed
```

---

# 23. Duplicate Prevention

## Strategy

Gunakan:

* sync_id unique
* UUID
* timestamp validation

## Important

Backend wajib reject duplicate sync_id.

---

# 24. Conflict Handling

## Conflict Rules

### Server Wins

Untuk:

* jadwal
* approval
* QC

### Local Wins

Untuk:

* draft belum sync

---

# 25. File Storage Strategy

## Local File Handling

### Images

Disimpan lokal sebelum upload.

### Upload Later

Saat sync online.

---

# 26. File Compression

## Before Upload

Lakukan:

* image resize
* image compress

## Goal

Mengurangi upload gagal.

---

# 27. Network Detection

## Internet Detection

Gunakan:

* expo-network

## Features

* online detection
* offline detection
* realtime monitoring

---

# 28. Offline Banner UX

## Banner Rules

### Offline

```text id="mq8r3s"
Mode Offline Aktif
```

### Syncing

```text id="bh39ak"
Sinkronisasi Sedang Berjalan
```

### Failed

```text id="93ev3i"
Sinkronisasi Gagal
```

---

# 29. Auto Sync Strategy

## Trigger Sync

Sync otomatis saat:

* app foreground
* internet reconnect
* manual refresh

---

# 30. Manual Sync

## User Control

User tetap dapat:

* sync manual
* retry failed sync
* lihat queue

---

# 31. Local Repository Pattern

## Structure

```bash id="dn8x5w"
repositories/
├── user.repository.ts
├── jadwal.repository.ts
├── draft.repository.ts
├── sync.repository.ts
└── file.repository.ts
```

---

# 32. Repository Example

## draft.repository.ts

```ts id="qhtz1c"
import { db } from "../index";

export const insertDraftSampling = async (
  data: any
) => {
  await db.runAsync(
    `
    INSERT INTO draft_sampling (
      id,
      sync_id,
      jenis_sample
    )
    VALUES (?, ?, ?)
    `,
    [
      data.id,
      data.sync_id,
      data.jenis_sample
    ]
  );
};
```

---

# 33. Queue Insert Example

## Add Queue

```ts id="bq0d04"
await db.runAsync(
  `
  INSERT INTO sampling_queue (
    id,
    reference_id,
    queue_type,
    payload,
    status
  )
  VALUES (?, ?, ?, ?, ?)
  `,
  [
    queueId,
    draftId,
    "sampling",
    JSON.stringify(payload),
    "pending"
  ]
);
```

---

# 34. Pull Sync Example

## Fetch Jadwal

```ts id="nd7jdl"
const response = await axiosInstance.get(
  "/mobile/jadwal"
);
```

---

# 35. Push Sync Example

## Upload Batch

```ts id="dck2tx"
const response = await axiosInstance.post(
  "/mobile/sync-sampling",
  payload
);
```

---

# 36. Sync Cleanup

## After Success

Jika sync berhasil:

* remove queue
* mark synced
* clear temporary file

---

# 37. Data Integrity

## Important Rules

### Never Delete Draft Immediately

Hapus hanya setelah:

* sync success
* upload success confirmed

### Validate Before Save

* GPS required
* timestamp required
* image required

---

# 38. Performance Optimization

## Optimization

* indexed query
* lightweight schema
* minimal select
* batch insert

---

# 39. Security Strategy

## Sensitive Data

SQLite hanya menyimpan:

* operational data
* temporary data

## Avoid

* sensitive token raw
* unnecessary customer data

---

# 40. Secure Storage

## Use Secure Store

Gunakan:

* Expo Secure Store

Untuk:

* JWT token
* refresh token
* session info

---

# 41. Crash Recovery

## Recovery Rules

Jika app crash:

* queue tetap aman
* draft tetap ada
* upload bisa dilanjutkan

---

# 42. Sync Monitoring

## Monitoring Features

* pending queue count
* failed queue count
* last sync time

---

# 43. Future Scalability

SQLite architecture harus siap untuk:

* background sync
* websocket sync
* realtime sync
* GIS offline map
* barcode scanner
* QR scanner

---

# 44. Testing Strategy

## Important Tests

### Offline Test

* no internet
* unstable internet
* airplane mode

### Recovery Test

* app killed
* app restart
* sync retry

### Queue Test

* duplicate queue
* failed upload
* retry upload

---

# 45. Final Vision

SQLite offline-first architecture harus terasa seperti:

* reliable field operation database
* resilient mobile sync system
* stable offline-first infrastructure
* enterprise-grade mobile persistence layer
