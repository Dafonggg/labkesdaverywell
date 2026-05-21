````md id="database-schema-labkesda"
# database-schema.md
## Database Schema Design
### SIM Labkesda Purwakarta

---

# 1. Database Overview

## Database Engine
- PostgreSQL 17

## Architecture Principles
- UUID Primary Key
- ACID Compliance
- Relational Integrity
- Auditability
- ISO/IEC 17025 Alignment

## Naming Convention
- snake_case
- plural table names
- singular foreign key

---

# 2. Main Database Modules

## Modules
1. Authentication & RBAC
2. Permohonan Pengujian
3. Jadwal Sampling
4. Registrasi Sampel
5. Pengujian Laboratorium
6. Verifikasi QC
7. Laporan Hasil Uji
8. Approval Kepala UPTD
9. Arsip Digital
10. Monitoring & Audit Trail
11. Mobile Offline Sync

---

# 3. Authentication Tables

---

## users

```sql
CREATE TABLE users (
    id UUID PRIMARY KEY,
    role_id UUID NOT NULL,
    
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(30),

    is_active BOOLEAN DEFAULT TRUE,

    last_login_at TIMESTAMP NULL,

    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP
);
````

---

## roles

```sql
CREATE TABLE roles (
    id UUID PRIMARY KEY,

    name VARCHAR(100) UNIQUE NOT NULL,
    code VARCHAR(100) UNIQUE NOT NULL,

    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

## permissions

```sql
CREATE TABLE permissions (
    id UUID PRIMARY KEY,

    name VARCHAR(255) NOT NULL,
    code VARCHAR(255) UNIQUE NOT NULL,

    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

## role_permissions

```sql
CREATE TABLE role_permissions (
    id UUID PRIMARY KEY,

    role_id UUID NOT NULL,
    permission_id UUID NOT NULL,

    created_at TIMESTAMP
);
```

---

# 4. Permohonan Pengujian

---

## permohonan_pengujian

```sql
CREATE TABLE permohonan_pengujian (
    id UUID PRIMARY KEY,

    nomor_permohonan VARCHAR(100) UNIQUE NOT NULL,

    jenis_pemohon VARCHAR(50) NOT NULL,

    nama_instansi VARCHAR(255),
    nama_pemohon VARCHAR(255) NOT NULL,

    email VARCHAR(255),
    phone VARCHAR(30),

    alamat TEXT,

    jenis_sample VARCHAR(100) NOT NULL,

    total_biaya NUMERIC(15,2) DEFAULT 0,

    status VARCHAR(50) DEFAULT 'pending',

    catatan TEXT,

    dibuat_oleh UUID NOT NULL,

    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP
);
```

---

## payments

```sql
CREATE TABLE payments (
    id UUID PRIMARY KEY,

    permohonan_id UUID NOT NULL,

    metode_pembayaran VARCHAR(100),

    jumlah NUMERIC(15,2) NOT NULL,

    status VARCHAR(50) DEFAULT 'pending',

    tanggal_bayar TIMESTAMP,

    bukti_pembayaran TEXT,

    dicatat_oleh UUID,

    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

# 5. Jadwal Sampling

---

## jadwal_sampling

```sql
CREATE TABLE jadwal_sampling (
    id UUID PRIMARY KEY,

    permohonan_id UUID NOT NULL,

    petugas_lapangan_id UUID NOT NULL,

    tanggal_sampling DATE NOT NULL,

    jam_sampling TIME,

    lokasi TEXT NOT NULL,

    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),

    status VARCHAR(50) DEFAULT 'scheduled',

    catatan TEXT,

    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

# 6. Registrasi Sampel

---

## registrasi_sample

```sql
CREATE TABLE registrasi_sample (
    id UUID PRIMARY KEY,

    permohonan_id UUID NOT NULL,

    nomor_registrasi VARCHAR(100) UNIQUE NOT NULL,

    kode_sample VARCHAR(100) UNIQUE NOT NULL,

    tanggal_registrasi TIMESTAMP NOT NULL,

    status VARCHAR(50) DEFAULT 'registered',

    dibuat_oleh UUID NOT NULL,

    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

## samples

```sql
CREATE TABLE samples (
    id UUID PRIMARY KEY,

    registrasi_sample_id UUID NOT NULL,

    jadwal_sampling_id UUID,

    jenis_sample VARCHAR(100) NOT NULL,

    kondisi_sample TEXT,

    metode_pengambilan TEXT,

    suhu VARCHAR(50),

    cuaca VARCHAR(100),

    lokasi_pengambilan TEXT,

    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),

    waktu_pengambilan TIMESTAMP,

    status VARCHAR(50) DEFAULT 'sampled',

    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

## sample_files

```sql
CREATE TABLE sample_files (
    id UUID PRIMARY KEY,

    sample_id UUID NOT NULL,

    file_name VARCHAR(255),
    file_path TEXT,

    file_type VARCHAR(100),

    uploaded_by UUID,

    created_at TIMESTAMP
);
```

---

# 7. Parameter Pengujian

---

## parameter_uji

```sql
CREATE TABLE parameter_uji (
    id UUID PRIMARY KEY,

    nama_parameter VARCHAR(255) NOT NULL,

    satuan VARCHAR(100),

    baku_mutu_min NUMERIC(15,4),
    baku_mutu_max NUMERIC(15,4),

    metode_uji TEXT,

    is_active BOOLEAN DEFAULT TRUE,

    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

# 8. Pengujian Laboratorium

---

## hasil_uji

```sql
CREATE TABLE hasil_uji (
    id UUID PRIMARY KEY,

    sample_id UUID NOT NULL,

    parameter_uji_id UUID NOT NULL,

    analis_id UUID NOT NULL,

    nilai_hasil NUMERIC(15,4),

    status_hasil VARCHAR(50),

    metode_pengujian TEXT,

    catatan TEXT,

    status_qc VARCHAR(50) DEFAULT 'pending_qc',

    diuji_pada TIMESTAMP,

    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

## hasil_uji_files

```sql
CREATE TABLE hasil_uji_files (
    id UUID PRIMARY KEY,

    hasil_uji_id UUID NOT NULL,

    file_name VARCHAR(255),
    file_path TEXT,

    file_type VARCHAR(100),

    created_at TIMESTAMP
);
```

---

# 9. Verifikasi QC

---

## verifikasi_qc

```sql
CREATE TABLE verifikasi_qc (
    id UUID PRIMARY KEY,

    hasil_uji_id UUID NOT NULL,

    qc_id UUID NOT NULL,

    status VARCHAR(50) NOT NULL,

    catatan TEXT,

    diverifikasi_pada TIMESTAMP,

    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

# 10. Draft Laporan

---

## draft_laporan

```sql
CREATE TABLE draft_laporan (
    id UUID PRIMARY KEY,

    permohonan_id UUID NOT NULL,

    analis_id UUID NOT NULL,

    nomor_laporan VARCHAR(100) UNIQUE,

    status VARCHAR(50) DEFAULT 'draft',

    file_path TEXT,

    catatan TEXT,

    dibuat_pada TIMESTAMP,

    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

# 11. Approval Kepala UPTD

---

## persetujuan_laporan

```sql
CREATE TABLE persetujuan_laporan (
    id UUID PRIMARY KEY,

    draft_laporan_id UUID NOT NULL,

    kepala_uptd_id UUID NOT NULL,

    status VARCHAR(50) DEFAULT 'pending',

    catatan TEXT,

    approved_at TIMESTAMP,

    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

# 12. Final Report

---

## laporan_final

```sql
CREATE TABLE laporan_final (
    id UUID PRIMARY KEY,

    draft_laporan_id UUID NOT NULL,

    nomor_laporan VARCHAR(100) UNIQUE NOT NULL,

    file_pdf TEXT NOT NULL,

    hash_sha256 TEXT NOT NULL,

    is_final BOOLEAN DEFAULT TRUE,

    finalized_at TIMESTAMP,

    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

# 13. Arsip Digital

---

## arsip_laporan

```sql
CREATE TABLE arsip_laporan (
    id UUID PRIMARY KEY,

    laporan_final_id UUID NOT NULL,

    archived_at TIMESTAMP,

    archived_by UUID,

    created_at TIMESTAMP
);
```

---

# 14. Audit Trail

---

## activity_logs

```sql
CREATE TABLE activity_logs (
    id UUID PRIMARY KEY,

    user_id UUID,

    action VARCHAR(255),

    entity_type VARCHAR(255),

    entity_id UUID,

    old_values JSONB,

    new_values JSONB,

    ip_address VARCHAR(100),

    user_agent TEXT,

    created_at TIMESTAMP
);
```

---

# 15. Notification System

---

## notifications

```sql
CREATE TABLE notifications (
    id UUID PRIMARY KEY,

    user_id UUID NOT NULL,

    title VARCHAR(255),

    message TEXT,

    type VARCHAR(100),

    is_read BOOLEAN DEFAULT FALSE,

    created_at TIMESTAMP
);
```

---

# 16. Mobile Sync Tables

---

## sync_logs

```sql
CREATE TABLE sync_logs (
    id UUID PRIMARY KEY,

    user_id UUID,

    sync_type VARCHAR(100),

    status VARCHAR(50),

    total_data INTEGER DEFAULT 0,

    success_data INTEGER DEFAULT 0,

    failed_data INTEGER DEFAULT 0,

    response_message TEXT,

    synced_at TIMESTAMP,

    created_at TIMESTAMP
);
```

---

## failed_syncs

```sql
CREATE TABLE failed_syncs (
    id UUID PRIMARY KEY,

    user_id UUID,

    reference_id UUID,

    payload JSONB,

    error_message TEXT,

    retry_count INTEGER DEFAULT 0,

    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

# 17. Foreign Key Relationships

---

## User Relations

```sql
ALTER TABLE users
ADD CONSTRAINT fk_users_role
FOREIGN KEY (role_id)
REFERENCES roles(id);
```

---

## Payment Relations

```sql
ALTER TABLE payments
ADD CONSTRAINT fk_payment_permohonan
FOREIGN KEY (permohonan_id)
REFERENCES permohonan_pengujian(id);
```

---

## Jadwal Relations

```sql
ALTER TABLE jadwal_sampling
ADD CONSTRAINT fk_jadwal_permohonan
FOREIGN KEY (permohonan_id)
REFERENCES permohonan_pengujian(id);
```

---

## Sample Relations

```sql
ALTER TABLE samples
ADD CONSTRAINT fk_sample_registrasi
FOREIGN KEY (registrasi_sample_id)
REFERENCES registrasi_sample(id);
```

---

## Hasil Uji Relations

```sql
ALTER TABLE hasil_uji
ADD CONSTRAINT fk_hasil_uji_sample
FOREIGN KEY (sample_id)
REFERENCES samples(id);
```

---

# 18. Indexing Strategy

## Important Indexes

```sql
CREATE INDEX idx_permohonan_status
ON permohonan_pengujian(status);

CREATE INDEX idx_jadwal_tanggal
ON jadwal_sampling(tanggal_sampling);

CREATE INDEX idx_sample_kode
ON registrasi_sample(kode_sample);

CREATE INDEX idx_hasil_status_qc
ON hasil_uji(status_qc);

CREATE INDEX idx_logs_entity
ON activity_logs(entity_type, entity_id);
```

---

# 19. Full Text Search

## PostgreSQL Search

```sql
CREATE INDEX idx_permohonan_search
ON permohonan_pengujian
USING gin(to_tsvector('simple', nama_pemohon));
```

---

# 20. Soft Delete Strategy

## Soft Delete Tables

Gunakan soft delete pada:

* users
* permohonan_pengujian
* registrasi_sample
* jadwal_sampling

## Reason

Untuk kebutuhan audit dan histori.

---

# 21. Auditability Standards

## ISO/IEC 17025 Alignment

Database harus mendukung:

* traceability
* immutable final report
* activity history
* QC validation history
* approval workflow

---

# 22. Transaction Critical Tables

## Use DB Transaction

Pada:

* registrasi_sample
* hasil_uji
* verifikasi_qc
* laporan_final
* payments

---

# 23. Security Strategy

## Security Rules

* UUID only
* no sequential IDs
* protected sensitive data
* audit every critical action

---

# 24. File Storage Reference

## File Strategy

Database hanya menyimpan:

* path
* metadata

File fisik disimpan di storage server.

---

# 25. Scalability Strategy

## Future Ready

Schema harus siap untuk:

* multi cabang lab
* GIS integration
* realtime sync
* external integration
* multi parameter testing

---

# 26. Final Architecture Vision

Database harus terasa seperti:

* enterprise laboratory database
* scientific workflow database
* government-grade operational database
* high integrity audit-ready system