````md id="workflow-labkesda"
# workflow.md
## Business Workflow System
### SIM Labkesda Purwakarta

---

# 1. Overview

Workflow SIM Labkesda Purwakarta dirancang berdasarkan:
- Proses bisnis laboratorium lingkungan
- ISO/IEC 17025
- Auditability
- Traceability
- Approval hierarchy
- Offline field operation

Workflow utama sistem:
```text
Permohonan
→ Pembayaran
→ Jadwal Sampling
→ Sampling Lapangan
→ Registrasi Sampel
→ Pengujian Laboratorium
→ Verifikasi QC
→ Draft Laporan
→ Approval Kepala UPTD
→ Final Report
→ Arsip
````

---

# 2. Workflow Principles

## Core Principles

### Traceability

Semua proses harus dapat ditelusuri.

### Validation Layer

Setiap tahap memiliki validasi.

### Role Separation

Setiap role hanya menangani tugas masing-masing.

### Immutable Final Report

Laporan final tidak dapat diubah.

### Audit Ready

Semua aktivitas dicatat.

---

# 3. Main Actors

| Role             | Responsibility             |
| ---------------- | -------------------------- |
| Admin            | Administrasi & operasional |
| Petugas Lapangan | Pengambilan sampel         |
| Petugas Lab      | Input hasil pengujian      |
| QC               | Verifikasi hasil           |
| Analis           | Penyusunan laporan         |
| Kepala UPTD      | Approval final             |

---

# 4. Master Workflow

```text id="9v3uk8"
1. Permohonan Masuk
2. Verifikasi Administrasi
3. Pembayaran
4. Penjadwalan Sampling
5. Sampling Lapangan
6. Sinkronisasi Mobile
7. Registrasi Sampel
8. Pengujian Laboratorium
9. Verifikasi QC
10. Generate Draft Laporan
11. Approval Kepala UPTD
12. Generate Final PDF
13. Arsip Digital
```

---

# 5. Workflow Permohonan

## Actor

Admin

## Flow

```text id="4x5y7v"
Create Permohonan
→ Validasi Data
→ Status Pending
→ Menunggu Pembayaran
```

## Status

| Status          | Meaning             |
| --------------- | ------------------- |
| pending         | Menunggu proses     |
| waiting_payment | Menunggu pembayaran |
| paid            | Pembayaran berhasil |
| scheduled       | Sudah dijadwalkan   |
| completed       | Selesai             |

---

# 6. Workflow Pembayaran

## Actor

Admin

## Flow

```text id="s9ec7r"
Input Pembayaran
→ Verifikasi Pembayaran
→ Update Status
→ Lanjut Penjadwalan
```

## Rules

* pembayaran wajib valid
* tidak bisa jadwal sebelum bayar

---

# 7. Workflow Jadwal Sampling

## Actor

Admin

## Flow

```text id="hr3nh8"
Pilih Petugas Lapangan
→ Tentukan Tanggal
→ Tentukan Lokasi
→ Kirim Jadwal ke Mobile
```

## Output

* jadwal mobile
* notifikasi petugas

---

# 8. Workflow Mobile Sampling

## Actor

Petugas Lapangan

## Flow

```text id="0w55zq"
Terima Jadwal
→ Datang Lokasi
→ GPS Validation
→ Input Data Sampling
→ Ambil Foto
→ Simpan SQLite
→ Queue Sync
```

---

# 9. Offline First Workflow

## Offline Flow

```text id="jqynh4"
No Internet
→ Save SQLite
→ Pending Queue
→ Reconnect Internet
→ Auto Sync
→ Success
```

## Important

Sampling tetap berjalan walaupun offline.

---

# 10. Sync Workflow

## Mobile → Backend

```text id="lshjqf"
Draft SQLite
→ Queue Pending
→ Batch Upload
→ Backend Validation
→ Save Database
→ Sync Success
```

## Validation

* duplicate sync_id
* GPS
* timestamp
* required photo

---

# 11. Workflow Registrasi Sampel

## Actor

Admin / Petugas Lab

## Flow

```text id="q7n4ow"
Data Sampling Masuk
→ Generate Nomor Registrasi
→ Generate Kode Sampel
→ Print Label
→ Sample Masuk Lab
```

---

# 12. Workflow Pengujian Laboratorium

## Actor

Petugas Lab

## Flow

```text id="3xw9n4"
Pilih Sample
→ Input Hasil Uji
→ Upload Dokumen
→ Submit QC
```

## Rules

* parameter wajib lengkap
* hasil wajib valid

---

# 13. Workflow QC

## Actor

QC

## Flow

```text id="8m4psm"
Lihat Pending QC
→ Verifikasi Hasil
→ Approve / Reject
```

---

## QC Approve Flow

```text id="3f2r9q"
Approve QC
→ Status Approved
→ Ready Draft Laporan
```

---

## QC Reject Flow

```text id="1y3qlt"
Reject QC
→ Kembali ke Petugas Lab
→ Revisi Hasil
→ Submit Ulang
```

---

# 14. Workflow Draft Laporan

## Actor

Analis

## Flow

```text id="k6dbi9"
Generate Draft
→ Ambil Hasil Approved
→ Susun PDF
→ Preview
→ Submit Approval
```

---

# 15. Workflow Approval

## Actor

Kepala UPTD

## Flow

```text id="tm4owm"
Review Draft
→ Approve / Reject
```

---

## Approval Success

```text id="efr1bc"
Approve
→ Generate Final PDF
→ Lock Report
→ Arsip
```

---

## Approval Reject

```text id="0dzz9p"
Reject
→ Kembali ke Analis
→ Revisi Draft
```

---

# 16. Workflow Final Report

## Flow

```text id="sm3mjr"
Generate PDF
→ Generate QR Verification
→ Generate SHA256 Hash
→ Finalize
```

## Important

Laporan final:

* immutable
* tidak dapat diubah
* audit ready

---

# 17. Workflow Arsip Digital

## Flow

```text id="mjlwm5"
Final Report
→ Archive
→ Backup
→ Ready Download
```

---

# 18. Notification Workflow

## Notifications

### Admin

* pembayaran masuk
* QC reject

### Petugas Lapangan

* jadwal baru
* sync gagal

### QC

* hasil pending

### Kepala UPTD

* approval pending

---

# 19. Activity Log Workflow

## Logged Activities

```text id="n7j0rq"
Login
Create
Update
Delete
Approve
Reject
Sync
Download
```

---

# 20. Error Workflow

## Sync Failed

```text id="kjc8n8"
Upload Failed
→ Save Queue
→ Retry
→ Notify User
```

---

## QC Failed

```text id="v9axng"
QC Reject
→ Revisi
→ Submit Ulang
```

---

# 21. Workflow Status System

## Permohonan

```text id="f6gclv"
pending
waiting_payment
paid
scheduled
completed
```

---

## Sampling

```text id="8rl7l5"
draft
pending_sync
synced
failed
```

---

## Pengujian

```text id="yqiy2z"
draft
pending_qc
approved
rejected
```

---

## Laporan

```text id="l7w0ee"
draft
pending_approval
approved
final
archived
```

---

# 22. Queue Workflow

## Sync Queue

```text id="u5d4u3"
Insert Queue
→ Pending
→ Syncing
→ Completed
```

---

# 23. Transaction Workflow

## Critical Transaction

Gunakan DB Transaction pada:

* registrasi sampel
* QC approval
* final approval
* payment

---

# 24. Security Workflow

## Rules

### Petugas Lapangan

Hanya melihat jadwal sendiri.

### QC

Tidak boleh edit hasil.

### Kepala UPTD

Hanya approval.

### Final Report

Tidak dapat diubah.

---

# 25. Audit Workflow

## Audit Trail

Semua proses:

* timestamp
* user
* old value
* new value
* action type

---

# 26. Mobile Workflow

## Mobile User Flow

```text id="p9hnvk"
Login
→ Download Jadwal
→ Sampling
→ Offline Save
→ Sync
→ Success
```

---

# 27. API Workflow

## Backend Validation

```text id="p2y8ms"
Request API
→ Validate JWT
→ Validate Permission
→ Validate Payload
→ Process Service
→ DB Transaction
→ JSON Response
```

---

# 28. Report Lifecycle

## Report Flow

```text id="q2owyt"
Draft
→ QC Approved
→ Approval
→ Final PDF
→ Archived
```

---

# 29. Backup Workflow

## Backup System

```text id="d0r1m8"
Database Backup
→ File Backup
→ Archive Retention
```

---

# 30. Future Workflow Scalability

Workflow harus siap untuk:

* multi cabang
* realtime websocket
* GIS integration
* barcode tracking
* AI validation
* external government integration

---

# 31. Final Workflow Vision

Workflow sistem harus terasa seperti:

* enterprise laboratory workflow engine
* scientific operational platform
* government-grade approval system
* audit-ready digital laboratory process
