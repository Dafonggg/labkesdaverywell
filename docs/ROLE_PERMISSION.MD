# role-permission.md
## Role & Permission Matrix
### SIM Labkesda Purwakarta

---

# 1. Overview

Sistem menggunakan:
- RBAC (Role Based Access Control)
- Permission Based Authorization
- Middleware Protection
- Policy Authorization Laravel

Tujuan:
- membatasi akses sesuai tugas
- menjaga keamanan data
- mendukung audit ISO/IEC 17025
- mencegah manipulasi workflow laboratorium

---

# 2. Roles

## Main Roles

| Role | Keterangan |
|---|---|
| admin | Pengelola administrasi dan operasional |
| petugas_lapangan | Pengambil sampel lapangan |
| petugas_lab | Penguji laboratorium |
| qc | Verifikasi dan validasi hasil |
| analis | Penyusun laporan hasil uji |
| kepala_uptd | Approval laporan final |

---

# 3. Permission Naming Convention

## Standard Format

```text
module.action