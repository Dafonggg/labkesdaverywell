````md id="api-contract-labkesda"
# api.contract.md
## API Contract & Axios Integration
### SIM Labkesda Purwakarta

---

# 1. Overview

Backend SIM Labkesda menggunakan:
- REST API
- JSON Response
- JWT Authentication
- RBAC Authorization
- Axios Client Integration

API digunakan oleh:
- Frontend Web React + Vite
- Mobile Expo React Native

---

# 2. Base URL

## Development
```env
http://localhost:8000/api/v1
````

## Production

```env
https://api.labkesda-purwakarta.id/api/v1
```

---

# 3. Global API Standards

## Content Type

```http
Content-Type: application/json
Accept: application/json
```

---

# 4. Authentication Header

## Bearer Token

```http
Authorization: Bearer {token}
```

---

# 5. Standard Success Response

```json
{
  "success": true,
  "message": "Data berhasil diambil",
  "data": {},
  "meta": {}
}
```

---

# 6. Standard Error Response

```json
{
  "success": false,
  "message": "Validation Error",
  "errors": {
    "email": [
      "Email wajib diisi"
    ]
  }
}
```

---

# 7. HTTP Status Codes

| Code | Meaning               |
| ---- | --------------------- |
| 200  | Success               |
| 201  | Created               |
| 400  | Bad Request           |
| 401  | Unauthorized          |
| 403  | Forbidden             |
| 404  | Not Found             |
| 422  | Validation Error      |
| 500  | Internal Server Error |

---

# 8. Authentication API

---

## Login

### Endpoint

```http
POST /auth/login
```

### Request

```json
{
  "email": "admin@mail.com",
  "password": "password"
}
```

### Response

```json
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "user": {
      "id": "uuid",
      "name": "Admin",
      "email": "admin@mail.com",
      "role": "admin"
    },
    "token": "jwt_token",
    "refresh_token": "refresh_token"
  }
}
```

---

## Logout

### Endpoint

```http
POST /auth/logout
```

---

## Refresh Token

### Endpoint

```http
POST /auth/refresh
```

---

## Current User

### Endpoint

```http
GET /auth/me
```

---

# 9. Permohonan API

---

## Get All Permohonan

### Endpoint

```http
GET /permohonan
```

### Query Params

```http
?page=1
&search=
&status=
```

---

## Create Permohonan

### Endpoint

```http
POST /permohonan
```

### Request

```json
{
  "jenis_pemohon": "instansi",
  "nama_instansi": "PT Contoh",
  "nama_pemohon": "Budi",
  "phone": "08123",
  "alamat": "Purwakarta",
  "jenis_sample": "Air"
}
```

---

## Get Detail Permohonan

### Endpoint

```http
GET /permohonan/{id}
```

---

## Update Permohonan

### Endpoint

```http
PUT /permohonan/{id}
```

---

## Delete Permohonan

### Endpoint

```http
DELETE /permohonan/{id}
```

---

# 10. Payment API

---

## Create Payment

### Endpoint

```http
POST /payments
```

### Request

```json
{
  "permohonan_id": "uuid",
  "jumlah": 500000,
  "metode_pembayaran": "cash"
}
```

---

# 11. Jadwal Sampling API

---

## Create Jadwal

### Endpoint

```http
POST /jadwal-sampling
```

### Request

```json
{
  "permohonan_id": "uuid",
  "petugas_lapangan_id": "uuid",
  "tanggal_sampling": "2026-05-20",
  "lokasi": "Purwakarta"
}
```

---

## Get Jadwal Mobile

### Endpoint

```http
GET /mobile/jadwal
```

---

# 12. Sampling Mobile API

---

## Batch Sync Sampling

### Endpoint

```http
POST /mobile/sync-sampling
```

### Request

```json
{
  "samples": [
    {
      "sync_id": "uuid",
      "jadwal_sampling_id": "uuid",
      "jenis_sample": "Air",
      "latitude": -6.551,
      "longitude": 107.446,
      "cuaca": "Cerah",
      "suhu": "30C",
      "catatan": "Lokasi normal",
      "waktu_pengambilan": "2026-05-20 09:00:00"
    }
  ]
}
```

### Response

```json
{
  "success": true,
  "message": "Sinkronisasi berhasil",
  "data": {
    "success_count": 1,
    "failed_count": 0
  }
}
```

---

# 13. Upload Foto Sampling

---

## Upload Image

### Endpoint

```http
POST /mobile/upload-sample-image
```

### Request

Form Data

```http
image
sample_id
```

---

# 14. Registrasi Sample API

---

## Registrasi Sample

### Endpoint

```http
POST /registrasi-sample
```

### Response

```json
{
  "success": true,
  "message": "Registrasi berhasil",
  "data": {
    "nomor_registrasi": "REG/2026/001",
    "kode_sample": "SPL-20260521-001"
  }
}
```

---

# 15. Parameter Uji API

---

## Get Parameter

### Endpoint

```http
GET /parameter-uji
```

---

# 16. Hasil Uji API

---

## Input Hasil Uji

### Endpoint

```http
POST /hasil-uji
```

### Request

```json
{
  "sample_id": "uuid",
  "parameter_uji_id": "uuid",
  "nilai_hasil": 7.2,
  "metode_pengujian": "SNI 06-6989"
}
```

---

## Get Pending QC

### Endpoint

```http
GET /hasil-uji/pending-qc
```

---

# 17. QC API

---

## Approve QC

### Endpoint

```http
POST /qc/approve
```

### Request

```json
{
  "hasil_uji_id": "uuid",
  "catatan": "Valid"
}
```

---

## Reject QC

### Endpoint

```http
POST /qc/reject
```

### Request

```json
{
  "hasil_uji_id": "uuid",
  "catatan": "Nilai tidak valid"
}
```

---

# 18. Laporan API

---

## Generate Draft Laporan

### Endpoint

```http
POST /laporan/generate
```

---

## Get Draft Laporan

### Endpoint

```http
GET /laporan/draft
```

---

## Download PDF

### Endpoint

```http
GET /laporan/{id}/download
```

---

# 19. Approval API

---

## Approve Laporan

### Endpoint

```http
POST /approval/final
```

### Request

```json
{
  "laporan_id": "uuid",
  "catatan": "Disetujui"
}
```

---

# 20. Notification API

---

## Get Notifications

### Endpoint

```http
GET /notifications
```

---

## Read Notification

### Endpoint

```http
POST /notifications/read
```

---

# 21. Monitoring API

---

## Activity Logs

### Endpoint

```http
GET /activity-logs
```

---

# 22. Dashboard API

---

## Dashboard Summary

### Endpoint

```http
GET /dashboard/summary
```

### Response

```json
{
  "success": true,
  "data": {
    "permohonan": 120,
    "pending_qc": 12,
    "laporan_final": 40
  }
}
```

---

# 23. Axios Architecture

## Folder Structure

```bash
src/
├── services/
│   ├── axios.ts
│   ├── auth.service.ts
│   ├── permohonan.service.ts
│   ├── sampling.service.ts
│   ├── qc.service.ts
│   └── laporan.service.ts
```

---

# 24. Axios Instance

## axios.ts

```ts
import axios from "axios";

const axiosInstance = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
  timeout: 10000,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
});

axiosInstance.interceptors.request.use((config) => {
  const token = localStorage.getItem("token");

  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }

  return config;
});

export default axiosInstance;
```

---

# 25. Axios Auth Service

## auth.service.ts

```ts
import axiosInstance from "./axios";

export const login = async (payload: {
  email: string;
  password: string;
}) => {
  const response = await axiosInstance.post(
    "/auth/login",
    payload
  );

  return response.data;
};

export const logout = async () => {
  const response = await axiosInstance.post(
    "/auth/logout"
  );

  return response.data;
};
```

---

# 26. Axios Permohonan Service

## permohonan.service.ts

```ts
import axiosInstance from "./axios";

export const getPermohonan = async () => {
  const response = await axiosInstance.get(
    "/permohonan"
  );

  return response.data;
};

export const createPermohonan = async (
  payload: any
) => {
  const response = await axiosInstance.post(
    "/permohonan",
    payload
  );

  return response.data;
};
```

---

# 27. Axios Mobile Sync Service

## sampling.service.ts

```ts
import axiosInstance from "./axios";

export const syncSampling = async (
  payload: any
) => {
  const response = await axiosInstance.post(
    "/mobile/sync-sampling",
    payload
  );

  return response.data;
};
```

---

# 28. Axios Error Interceptor

## Error Handling

```ts
axiosInstance.interceptors.response.use(
  (response) => response,
  async (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem("token");
      window.location.href = "/login";
    }

    return Promise.reject(error);
  }
);
```

---

# 29. Axios Retry Strategy

## Retry Rules

Digunakan untuk:

* mobile sync
* upload image
* unstable connection

## Strategy

* retry 3x
* exponential backoff

---

# 30. TanStack Query Integration

## Example

```ts
import { useQuery } from "@tanstack/react-query";
import { getPermohonan } from "@/services/permohonan.service";

export const usePermohonan = () => {
  return useQuery({
    queryKey: ["permohonan"],
    queryFn: getPermohonan,
  });
};
```

---

# 31. API Security

## Rules

* semua endpoint protected
* role middleware
* request validation
* rate limiting

---

# 32. File Upload Standards

## Max Upload

* image: 5MB
* pdf: 10MB

## Allowed

* JPG
* PNG
* PDF

---

# 33. Offline Sync Rules

## Important

Backend harus:

* reject duplicate sync_id
* validate timestamp
* rollback failed batch

---

# 34. API Documentation

## Documentation Tool

Gunakan:

* Swagger/OpenAPI

## Endpoint Docs

Setiap endpoint wajib memiliki:

* request
* response
* auth requirement
* validation rules

---

# 35. Final Vision

API contract harus terasa seperti:

* enterprise API architecture
* government-grade API standard
* stable sync infrastructure
* secure laboratory backend integration

