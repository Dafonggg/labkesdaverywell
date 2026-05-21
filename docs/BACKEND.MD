````md id="backend-md-labkesda"
# backend.md
## Backend Architecture & Development Guideline
### SIM Labkesda Purwakarta

---

# 1. Overview

Backend SIM Labkesda Purwakarta berfungsi sebagai:
- Core business logic system
- REST API provider
- Authentication server
- Data processing layer
- Offline synchronization handler
- Workflow orchestration system

Backend harus mampu:
- menangani workflow laboratorium
- menjaga integritas data
- mendukung offline sync mobile
- memastikan keamanan data
- mendukung audit trail ISO/IEC 17025

---

# 2. Backend Stack

## Core Stack

### Framework
- Laravel 13

### Language
- PHP 8.4+

### Database
- PostgreSQL 17

### Cache & Queue
- Redis

### Authentication
- JWT Authentication

### API
- REST API

### Storage
- Laravel Storage

### Queue Driver
- Redis Queue

### Realtime Future Ready
- Laravel Reverb

### Search
- PostgreSQL Full Text Search

### File Processing
- Laravel Media Library (optional)

---

# 3. Backend Goals

## Goals
Backend harus:
- scalable
- maintainable
- modular
- secure
- high integrity
- API driven

## Main Priorities
- data consistency
- offline synchronization
- workflow validation
- role security
- auditability

---

# 4. Architecture Pattern

## Pattern
Menggunakan:
- Service Repository Pattern
- Modular Feature Architecture
- Clean Architecture approach
- Thin Controller Pattern

## Principles
- business logic tidak di controller
- reusable services
- centralized validation
- decoupled modules

---

# 5. Folder Structure

```bash
app/
├── Actions/
├── Console/
├── DTOs/
├── Enums/
├── Events/
├── Exceptions/
├── Helpers/
├── Http/
├── Jobs/
├── Listeners/
├── Models/
├── Notifications/
├── Policies/
├── Providers/
├── Repositories/
├── Rules/
├── Services/
├── Traits/
└── Utils/
````

---

# 6. Http Structure

```bash
Http/
├── Controllers/
├── Middleware/
├── Requests/
├── Resources/
└── Responses/
```

---

# 7. Feature Module Structure

Contoh:

```bash
Permohonan/
├── Controllers/
├── Requests/
├── Resources/
├── Services/
├── Repositories/
├── DTOs/
├── Policies/
├── Events/
└── Jobs/
```

---

# 8. API Architecture

## API Type

REST API only.

## Response Format

### Success

```json
{
  "success": true,
  "message": "Success",
  "data": {}
}
```

### Error

```json
{
  "success": false,
  "message": "Validation Error",
  "errors": {}
}
```

---

# 9. API Versioning

## Standard

```bash
/api/v1/
```

## Example

```bash
/api/v1/auth/login
/api/v1/permohonan
/api/v1/qc/verifikasi
```

---

# 10. Authentication System

## Authentication

Menggunakan:

* JWT Access Token
* Refresh Token

## Features

* token expiration
* refresh token
* logout blacklist
* multi device ready

## Package

Gunakan:

* tymon/jwt-auth

---

# 11. RBAC System

## Role System

Roles:

* admin
* petugas_lapangan
* petugas_lab
* qc
* analis
* kepala_uptd

## Authorization

Gunakan:

* Laravel Policies
* Middleware
* Gates

---

# 12. Database Architecture

## Database

Gunakan:

* PostgreSQL 17

## Kenapa PostgreSQL

* strong ACID
* better concurrency
* JSON support
* full text search
* scalability
* indexing power

---

# 13. Database Standards

## Rules

* gunakan UUID
* gunakan foreign key
* gunakan soft delete
* gunakan timestamp

## Naming

```sql
snake_case
plural table
```

---

# 14. Main Tables

## Core Tables

```text
users
permohonan_pengujian
jadwal_sampling
registrasi_sample
samples
parameter_uji
hasil_uji
verifikasi_qc
draft_laporan
laporan_final
persetujuan_laporan
arsip_laporan
payments
activity_logs
sync_logs
```

---

# 15. UUID Standards

## UUID Usage

Semua primary key menggunakan UUID.

## Benefits

* secure ID
* distributed ready
* sync friendly
* difficult enumeration

---

# 16. Service Layer

## Principle

Semua business logic harus di Service Layer.

## Example

```bash
Services/
├── AuthService.php
├── PermohonanService.php
├── SamplingService.php
├── QCService.php
└── LaporanService.php
```

---

# 17. Repository Layer

## Purpose

Repository digunakan untuk:

* abstraction query
* reusable query
* complex query management

## Example

```bash
Repositories/
├── PermohonanRepository.php
├── SampleRepository.php
└── QCRepository.php
```

---

# 18. Request Validation

## Validation System

Gunakan:

* Form Request Laravel

## Rules

* centralized validation
* custom validation
* clean controller

---

# 19. API Resource

## API Resource Usage

Gunakan:

* Laravel API Resource

## Benefits

* consistent response
* hidden fields
* transform response

---

# 20. Queue System

## Queue Usage

Digunakan untuk:

* notification
* sync processing
* report generation
* email
* audit logging

## Driver

Redis Queue

---

# 21. Offline Sync System

## Core Feature

Backend harus mendukung:

* batch synchronization
* retry sync
* duplicate prevention
* conflict handling

## Sync Flow

```text
Mobile Offline
→ SQLite Queue
→ Batch Upload
→ Validation
→ Save Transaction
→ Sync Response
```

---

# 22. Sync Validation

## Important Rules

* duplicate prevention
* transaction validation
* GPS validation
* timestamp validation

## Strategy

Gunakan:

* checksum
* unique sync ID
* transaction rollback

---

# 23. Transaction Management

## Critical Operations

Gunakan DB Transaction untuk:

* registrasi sample
* QC verification
* laporan approval
* payment confirmation

## Principle

All or nothing.

---

# 24. Audit Trail System

## Audit Logging

Semua aktivitas penting harus dicatat.

## Log Content

* user
* action
* entity
* timestamp
* old value
* new value
* IP address

---

# 25. File Upload System

## Upload Features

* secure upload
* validation
* compression
* file naming

## Supported Files

* PDF
* JPG
* PNG
* DOCX

---

# 26. Storage Architecture

## Storage Strategy

### Public

* preview file

### Private

* laporan final
* hasil pengujian
* sensitive document

## Driver

* local storage
* S3 ready

---

# 27. PDF Generation

## PDF Features

* generate laporan hasil uji
* QR verification
* SHA256 hash
* official template

## Package

Gunakan:

* barryvdh/laravel-dompdf

---

# 28. Notification System

## Notification Types

* QC reject
* approval pending
* jadwal baru
* laporan final
* sync failed

## Channels

* database
* push notification
* email future ready

---

# 29. Redis Usage

## Redis Functions

* cache
* queue
* token blacklist
* rate limiting

---

# 30. Cache Strategy

## Cache Usage

Gunakan cache untuk:

* dashboard stats
* master data
* frequently accessed data

## TTL

* short cache only
* invalidate after update

---

# 31. Search System

## Search

Gunakan:

* PostgreSQL Full Text Search

## Search Features

* nomor registrasi
* kode sample
* nama instansi
* parameter

---

# 32. Security Standards

## Security Features

* JWT
* RBAC
* rate limiting
* request validation
* SQL injection protection
* XSS protection

---

# 33. API Security

## Rules

* protected routes
* role middleware
* throttle API
* sanitize input

## Important

Tidak boleh ada endpoint tanpa auth kecuali login.

---

# 34. Rate Limiting

## API Limit

Gunakan:

* Laravel Rate Limiter

## Example

* auth login
* sync endpoint
* upload endpoint

---

# 35. Exception Handling

## Global Exception

Gunakan:

* centralized exception handler

## Response

Selalu JSON response.

---

# 36. Logging System

## Logging

Gunakan:

* Laravel Log
* daily log channel

## Important Logs

* auth failed
* sync failed
* payment
* approval
* exception

---

# 37. Event Driven Architecture

## Events

Gunakan event untuk:

* QC approved
* laporan final
* sync success
* payment success

## Benefits

* decoupled logic
* scalable future

---

# 38. Background Jobs

## Jobs

Digunakan untuk:

* PDF generation
* notification
* heavy sync process
* backup

---

# 39. Testing Standards

## Testing

Gunakan:

* PestPHP
* Feature Test
* Unit Test

## Important Tests

* auth
* sync
* approval
* transaction
* RBAC

---

# 40. API Documentation

## Documentation

Gunakan:

* Swagger/OpenAPI

## Package

* l5-swagger

---

# 41. Environment Variables

## Example

```env
APP_NAME=SIM_LABKESDA
APP_ENV=production

DB_CONNECTION=pgsql
DB_HOST=
DB_PORT=5432
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

REDIS_HOST=
REDIS_PASSWORD=
REDIS_PORT=

JWT_SECRET=

QUEUE_CONNECTION=redis
CACHE_STORE=redis
```

---

# 42. Deployment Architecture

## Server Stack

* Ubuntu Server
* Nginx
* PHP-FPM
* PostgreSQL
* Redis

## Deployment

Gunakan:

* GitHub Actions
  atau
* Laravel Forge

---

# 43. Nginx Architecture

## Features

* reverse proxy
* SSL
* gzip compression
* cache headers

---

# 44. SSL & HTTPS

## Security

Semua traffic wajib:

* HTTPS
* TLS latest version

## Certificate

Gunakan:

* Let's Encrypt

---

# 45. Backup Strategy

## Backup

* daily database backup
* file backup
* retention policy

## Important

Laporan final wajib aman.

---

# 46. Monitoring System

## Monitoring

Gunakan:

* Laravel Telescope
* Laravel Pulse
* Sentry

## Monitor

* errors
* slow query
* failed jobs
* queue

---

# 47. Performance Optimization

## Optimization

* eager loading
* query indexing
* caching
* queue heavy process

## Avoid

* N+1 query
* giant response
* blocking process

---

# 48. Future Scalability

Backend harus siap untuk:

* multi cabang lab
* realtime websocket
* GIS integration
* microservice migration
* AI analytics
* external government integration

---

# 49. ISO/IEC 17025 Alignment

Backend harus mendukung:

* auditability
* traceability
* integrity
* approval workflow
* immutable final report
* activity history

---

# 50. Final Vision

Backend harus terasa seperti:

* enterprise laboratory platform
* government-grade backend system
* secure workflow engine
* stable operational API infrastructure