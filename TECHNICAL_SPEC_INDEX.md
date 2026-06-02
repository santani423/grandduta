# GRANDDUTA — Technical Design Document & API Specification
## Laravel 12 API-Only Rebuild — MASTER INDEX

**Versi:** 1.0.0 | **Tanggal:** 2026-06-02 | **Status:** Draft

---

Dokumen spesifikasi ini dibagi ke dalam 4 file terpisah:

| File | Isi | Section |
|------|-----|---------|
| [TECHNICAL_SPEC_PART1.md](TECHNICAL_SPEC_PART1.md) | Gambaran umum, arsitektur, desain database, komponen Laravel (Model, Service, Repository, Middleware, Policy, Event, Job) | 1–6 |
| [TECHNICAL_SPEC_PART2.md](TECHNICAL_SPEC_PART2.md) | Modul & fitur, alur bisnis, autentikasi, RBAC, standar API, validasi, error handling, logging, caching, file | 7–16 |
| [TECHNICAL_SPEC_PART3.md](TECHNICAL_SPEC_PART3.md) | Dokumentasi API lengkap per endpoint (Auth, Cluster, Customer, Billing, Payment, Installment, Reversal, Receivable, Report, Document, User, Audit) | 18 |
| [TECHNICAL_SPEC_PART4.md](TECHNICAL_SPEC_PART4.md) | Integrasi pihak ketiga, testing (unit+feature+factories), deployment, CI/CD, backup & recovery, checklist implementasi | 17, 19–24 |

---

## Ringkasan Cepat

### Stack Teknologi

| Layer | Teknologi |
|-------|-----------|
| Framework | Laravel 12 |
| PHP | >= 8.2 |
| Database | MySQL 8.x (InnoDB) |
| Auth | Laravel Sanctum (Bearer Token) |
| RBAC | spatie/laravel-permission |
| Cache | Redis |
| Queue | Redis + Laravel Queue |
| PDF | barryvdh/laravel-dompdf |
| Testing | Pest PHP |
| Web Server | Nginx |

### Modul Utama

1. **Auth** — Login, logout, change password
2. **Cluster** — Master klaster & tarif
3. **Customer** — CRUD pelanggan, konversi properti
4. **Billing** — Penyiapan tagihan (bulanan, khusus, mundur) & approval
5. **Payment** — Proses bayar loket, cicilan, pelunasan mundur
6. **Reversal** — Pembatalan transaksi multi-level approval
7. **Receivable** — Manajemen & analisis piutang
8. **Report** — Dashboard, laporan bulanan, LPP, RPP, kolektor
9. **Document** — Generate PDF (SPT, SPK, rekap)
10. **User** — Manajemen user & audit log

### Business Rules Kritis

- Denda **3%** jika pembayaran setelah tanggal **20**
- Format nomor kuitansi: **GD.YYYY.MM.NNNNNN** (counter reset per bulan)
- Tagihan harus di-**approve** sebelum bisa dibayar di loket
- Pembatalan transaksi memerlukan **approval Back Office/Root**
- Customer ID tepat **5 karakter** alphanumeric, unik
- Pelanggan status **AK** (Aktif) saja yang mendapat tagihan bulanan

### Roles & Akses Cepat

| Role | Akses Utama |
|------|-------------|
| `root` | Semua fitur + manajemen user |
| `back_office` | Penagihan, approval, laporan, master data |
| `loket` | Transaksi bayar, cicilan, cetak dokumen |
| `cs` | Read-only: info pelanggan & tagihan |

---

*Baca file secara berurutan: PART1 → PART2 → PART3 → PART4*
