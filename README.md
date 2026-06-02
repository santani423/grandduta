# GRANDDUTA - Sistem Manajemen Properti & Penagihan

Sistem informasi manajemen tagihan dan penagihan untuk perumahan Grand Duta. Dibangun menggunakan framework **CodeIgniter (PHP MVC)** dengan database **MySQL**, sistem ini mengelola data pelanggan/pemilik properti, penagihan bulanan, pembayaran, cicilan, hingga pelaporan keuangan secara terpadu.

---

## Daftar Isi

1. [Gambaran Umum](#gambaran-umum)
2. [Teknologi yang Digunakan](#teknologi-yang-digunakan)
3. [Fitur-Fitur Sistem](#fitur-fitur-sistem)
4. [Struktur Proyek](#struktur-proyek)
5. [Rancangan Database](#rancangan-database)
6. [Hak Akses Pengguna](#hak-akses-pengguna)
7. [Cara Instalasi](#cara-instalasi)
8. [Konfigurasi](#konfigurasi)

---

## Gambaran Umum

**Grand Duta** adalah sistem manajemen properti yang dirancang untuk kompleks perumahan berskala besar dengan banyak klaster dan blok. Sistem ini memungkinkan pengelolaan:

- Data master pelanggan/pemilik unit properti
- Penagihan bulanan otomatis berdasarkan tarif klaster
- Pemrosesan pembayaran melalui loket (counter)
- Pembayaran cicilan dan tunggakan
- Pencetakan kuitansi, SPT, dan SPK
- Pelaporan keuangan bulanan dan per kolektor
- Manajemen piutang dan denda keterlambatan

---

## Teknologi yang Digunakan

| Komponen | Keterangan |
|----------|-----------|
| Framework | CodeIgniter (PHP MVC) |
| Bahasa Pemrograman | PHP 5.6+ |
| Database | MySQL 5.0.7+ / MariaDB 10.1.31 |
| Frontend | HTML, CSS, JavaScript |
| PDF | FPDF Library, CFPDF (custom) |
| Server | Apache (XAMPP) |
| Karakter Set | UTF-8 |

---

## Fitur-Fitur Sistem

### 1. Autentikasi & Manajemen Pengguna

- Login sistem dengan validasi username dan password (MD5)
- Role-based access control — 5 level hak akses berbeda
- Menu dinamis berdasarkan level pengguna
- Fitur ganti password

**Pengguna terdaftar:** brata, rini, ayu, gista, umum, ratu, beben

---

### 2. Master Data Pelanggan

Pengelolaan data lengkap pemilik/penghuni unit properti.

**Informasi yang dikelola:**
- ID Pelanggan (idipkl) — kode unik 5 karakter
- Nama lengkap pelanggan
- Klaster, blok, dan nomor kavling
- Jenis properti: Bangunan, Kavling Developer, Kavling Pelanggan
- Nomor HP dan telepon
- Alamat KTP, Kecamatan, Kabupaten
- Luas bangunan (LB) dan luas tanah (LT)
- Email
- Tanggal serah terima properti
- Status hunian: Huni / Kosong
- Status pelanggan: Aktif / Rumah Kosong / Tidak Aktif

**Fitur:**
- Tambah, ubah, hapus data pelanggan
- Pencarian dan filter berdasarkan klaster atau jenis properti
- Cetak daftar master pelanggan
- Konversi status kavling ke bangunan

---

### 3. Master Data Klaster

Pengelolaan 14 klaster perumahan Grand Duta.

**Daftar Klaster:**

| ID | Nama Klaster |
|----|-------------|
| DO | Dolomite |
| GA | Garnet |
| JA | Jade |
| AM | Amber |
| DI | Diamond |
| RK | Ruko (berbagai varian) |
| EM | Emerald |
| AL | Alexandrite |
| BE | Beryl |
| CH | Chrysocolla |
| (dan lainnya) | |

Setiap klaster memiliki **tarif dasar** bulanan yang digunakan sebagai acuan penghitungan tagihan.

---

### 4. Penagihan (Billing)

Modul utama untuk mengelola tagihan bulanan seluruh pelanggan.

**Sub-fitur:**
- **Penyiapan Tagihan** — Menyiapkan tagihan bulan tertentu untuk seluruh atau sebagian pelanggan
- **Penyiapan Tagihan Khusus** — Tagihan dengan nominal khusus di luar tarif standar
- **Penyiapan Tagihan Mundur** — Penagihan untuk periode bulan sebelumnya
- **Input Manual Tagihan** — Entri tagihan secara manual per pelanggan
- **Update Tagihan** — Pembaruan data tagihan yang sudah ada
- **Approval Penagihan** — Proses persetujuan tagihan sebelum diterbitkan

**Status tagihan:**
- `Belum Bayar` — Tagihan sudah diterbitkan, belum dilunasi
- `Lunas` — Tagihan sudah dibayar

**Komponen tagihan:**
- Nominal pokok (sesuai tarif klaster)
- Denda keterlambatan (3% dari tagihan jika melewati tanggal 20)
- Diskon (jika memenuhi syarat)

---

### 5. Loket (Counter Pembayaran)

Pemrosesan transaksi pembayaran langsung di loket/counter.

**Fitur:**
- Pembayaran tagihan tunggal maupun sekaligus (multiple bulan)
- Pilihan metode pembayaran: **Cash** atau **Debet**
- Pencetakan kuitansi otomatis setelah pembayaran
- Pencatatan nama kasir dan loket pemroses
- Rincian bulan tagihan yang dibayar

**Data kuitansi yang dihasilkan:**
- Nomor kuitansi unik (16 karakter)
- Tanggal dan jam pembayaran
- Nama pelanggan, klaster, blok, nomor kavling
- Total tagihan, total denda, dan jumlah grand total
- Nama kasir dan loket

---

### 6. Cicilan

Manajemen pembayaran tagihan secara cicilan.

**Fitur:**
- Input cicilan pembayaran tunggakan
- Pelacakan jadwal cicilan per pelanggan
- Laporan status cicilan

---

### 7. Pelunasan Mundur

Pemrosesan pelunasan tagihan untuk periode bulan-bulan yang telah lalu (mundur), termasuk penghitungan denda akumulatif.

---

### 8. Pembatalan (Batal/Reversal)

Fitur pembatalan transaksi pembayaran yang sudah terjadi.

**Alur:**
1. Pengajuan pembatalan oleh operator loket
2. Review dan persetujuan oleh Back Office/Root
3. Pemrosesan reversal dan pembaruan status tagihan
4. Laporan pembatalan tersimpan sebagai audit trail

---

### 9. Informasi Tagihan & Pelanggan

Modul inquiry untuk melihat informasi tanpa mengubah data.

**Fitur:**
- Cari info tagihan berdasarkan ID pelanggan
- Lihat histori pembayaran pelanggan
- Status piutang per pelanggan
- Detail klaster, blok, dan kavling

---

### 10. Pencetakan Dokumen

Sistem menghasilkan berbagai dokumen cetak dalam format **PDF** menggunakan FPDF.

| Dokumen | Keterangan |
|---------|-----------|
| **SPT** (Surat Pembayaran Tunai) | Bukti pembayaran tagihan |
| **SPK** (Surat Pemberitahuan Kredit) | Lembar tagihan/invoice |
| **Kuitansi** | Tanda terima pembayaran |
| **Rekap Tagihan** | Rekapitulasi tagihan per periode |
| **Rekap per Klaster** | Ringkasan tagihan per klaster |
| **Rekap per Kavling** | Ringkasan tagihan per kavling |
| **Daftar Master Pelanggan** | Cetak data master pelanggan |
| **Tagihan Tidak Terangkut** | Tagihan yang belum terproses |

---

### 11. Laporan Keuangan

**Laporan yang tersedia:**

| Laporan | Keterangan |
|---------|-----------|
| **Laporan Bulanan** | Rekap penerimaan per bulan |
| **Laporan Kolektor** | Rincian per kolektor/petugas lapangan |
| **LPP** (Laporan Penerimaan Pembayaran) | Laporan harian penerimaan |
| **RPP** | Laporan Rekonsiliasi Pembayaran |

---

### 12. Piutang

Pelacakan tagihan yang belum terbayar (outstanding receivables).

**Fitur:**
- Daftar piutang per tahun/bulan
- Piutang per kavling
- Analisis umur piutang

---

## Struktur Proyek

```
grandduta/
├── application/
│   ├── config/
│   │   ├── config.php          ← Konfigurasi utama (base URL, dll)
│   │   ├── database.php        ← Koneksi database
│   │   ├── routes.php          ← Routing (default: home)
│   │   └── autoload.php        ← Autoload library & helper
│   ├── controllers/            ← 44+ controller (logika bisnis)
│   │   ├── home.php
│   │   ├── login.php
│   │   ├── master_pelanggan.php
│   │   ├── penagihan.php
│   │   ├── loket.php
│   │   ├── cicilan.php
│   │   ├── laporan_bulanan.php
│   │   ├── cetakspt.php
│   │   └── ... (dan lainnya)
│   ├── models/                 ← 12 model (akses database)
│   │   ├── master_model.php
│   │   ├── penagihan_model.php
│   │   ├── loket_model.php
│   │   ├── cicilan_model.php
│   │   ├── login_model.php
│   │   └── ...
│   ├── views/                  ← Template tampilan HTML
│   │   ├── home/
│   │   ├── master/
│   │   ├── penagihan/
│   │   ├── loket/
│   │   ├── cicilan/
│   │   ├── laporan/
│   │   ├── spt/
│   │   └── ...
│   ├── libraries/
│   │   ├── fpdf/               ← Library PDF
│   │   └── cfpdf.php           ← Custom PDF helper
│   └── helpers/
│       ├── menu_helper.php
│       └── ...
├── system/                     ← Core CodeIgniter
├── database baru/
│   └── granddutadb (2).sql     ← Dump database lengkap
├── index.php                   ← Entry point aplikasi
└── Dev-Log.txt
```

---

## Rancangan Database

**Nama Database:** `granddutadb`  
**Engine:** InnoDB / MyISAM  
**Character Set:** latin1 / utf8

### Diagram Relasi Ringkas

```
user ──────────────────────── level
 │
 ├── tagihan ──── pelanggan ──── cluster
 │       │             │
 │       └── kuitansi  ├── bork (jenis properti)
 │                     ├── huni (status hunian)
 │                     ├── statuspelanggan
 │                     └── kecamatan ── kabupaten
 │
 ├── piutang ─── tagihan
 └── menu (difilter oleh level)
```

---

### Tabel-Tabel Database

#### TABEL REFERENSI / MASTER

---

##### `user` — Data Pengguna Sistem

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| user_id | int(11) PK | ID unik pengguna |
| user_nama | varchar(100) | Nama lengkap |
| user_username | varchar(100) | Username login |
| user_password | varchar(100) | Password (MD5 hash) |
| user_level | int(5) | Level/role pengguna |

---

##### `level` — Level/Role Pengguna

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| level_id | int(5) PK | ID level |
| level_nama | varchar(100) | Nama level |

**Data:**

| ID | Nama |
|----|------|
| 1 | Root |
| 2 | Back Office |
| 3 | Loket |
| 4 | Customer Service |

---

##### `menu` — Menu Aplikasi

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| menu_id | int(11) PK | ID menu |
| menu_nama | varchar(100) | Label menu |
| menu_uri | varchar(100) | URI controller |
| menu_allowed | varchar(100) | Level yang diizinkan (pipe-separated) |

Menu dikontrol berdasarkan level pengguna. Terdapat 23 menu item yang dikonfigurasi per level.

---

##### `cluster` — Data Klaster Perumahan

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| idcluster | varchar(2) PK | Kode klaster |
| namacluster | varchar(20) | Nama klaster |
| tarif | double | Tarif bulanan dasar (Rp) |

---

##### `bork` — Jenis Properti

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| idbork | varchar(1) PK | Kode jenis |
| namabork | varchar(30) | Nama jenis |

**Data:**

| ID | Jenis |
|----|-------|
| B | Bangunan |
| K | Kavling Developer |
| P | Kavling Pelanggan |

---

##### `huni` — Status Hunian

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| idhuni | varchar(1) PK | Kode status |
| namahuni | varchar(15) | Nama status |

**Data:** `1` = Huni, `2` = Kosong

---

##### `carabayar` — Metode Pembayaran

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| idcarabayar | varchar(1) PK | Kode metode |
| namacarabayar | varchar(20) | Nama metode |

**Data:** `c` = Cash, `d` = Debet

---

##### `lewatbayar` — Jalur Pembayaran

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| idlewatbayar | varchar(1) PK | Kode jalur |
| namalewatbayar | varchar(20) | Nama jalur |

---

##### `kenadiskon` — Eligibilitas Diskon

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| idkenadiskon | varchar(2) PK | Kode |
| namakenadiskon | varchar(30) | Deskripsi |

---

##### `kenadenda` — Eligibilitas Denda

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| idkenadenda | varchar(2) PK | Kode |
| namakenadenda | varchar(30) | Deskripsi |

---

##### `kabupaten` — Data Kabupaten/Kota

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| idkabupaten | varchar(2) PK | Kode kabupaten |
| namakabupaten | varchar(30) | Nama kabupaten |

Berisi 20+ wilayah di Indonesia (Bandar Lampung, Pesawaran, Pringsewu, dll).

---

##### `kecamatan` — Data Kecamatan

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| idkecamatan | varchar(3) PK | Kode kecamatan |
| namakecamatan | varchar(50) | Nama kecamatan |

---

##### `golongan` — Golongan Pelanggan

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| idgolongan | varchar(2) PK | Kode golongan |
| namagolongan | varchar(20) | Nama golongan |

---

##### `statuspelanggan` — Status Pelanggan

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| idstatuspelanggan | varchar(2) PK | Kode status |
| namastatuspelanggan | varchar(30) | Nama status |

**Data:** Aktif, Rumah Kosong, Tidak Aktif

---

##### `statustagihan` — Status Tagihan

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| idstatustagihan | varchar(2) PK | Kode status |
| namastatustagihan | varchar(40) | Nama status |

**Data:** `01` = Belum Bayar, `02` = Lunas

---

#### TABEL OPERASIONAL

---

##### `pelanggan` — Data Master Pelanggan

Tabel inti yang menyimpan seluruh data pemilik/penghuni unit properti.

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| idipkl | varchar(5) PK | ID pelanggan unik |
| namapelanggan | varchar(60) | Nama lengkap pelanggan |
| idcluster | varchar(5) FK→cluster | Klaster properti |
| blok | varchar(3) | Blok/baris perumahan |
| nokav | varchar(4) | Nomor kavling/unit |
| idbork | varchar(1) FK→bork | Jenis properti |
| nohp | varchar(14) | Nomor HP |
| notelpon | varchar(14) | Nomor telepon |
| alamatktp | varchar(100) | Alamat sesuai KTP |
| idkecamatan | varchar(3) FK→kecamatan | Kecamatan |
| lb | varchar(6) | Luas bangunan (m²) |
| lt | varchar(6) | Luas tanah (m²) |
| email | varchar(60) | Alamat email |
| tglserahterima | date | Tanggal serah terima |
| idhuni | varchar(1) FK→huni | Status hunian |
| idstatuspelanggan | varchar(2) FK→statuspelanggan | Status pelanggan |
| user_id | varchar(11) FK→user | User yang input data |

---

##### `tagihan` — Tagihan Bulanan

Tabel utama penyimpanan tagihan per pelanggan per bulan.

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| idtagihan | int(11) PK AUTO_INCREMENT | ID tagihan unik |
| idipkl | varchar(5) FK→pelanggan | ID pelanggan |
| tahun | varchar(4) | Tahun tagihan |
| bulan | varchar(2) | Bulan tagihan (01-12) |
| tagihan | double | Nominal tagihan pokok (Rp) |
| tglbayar | datetime | Tanggal dan jam pembayaran |
| idloket | varchar(2) | Kode loket yang memproses |
| user_id | varchar(11) FK→user | Kasir/user yang memproses |
| idstatustagihan | varchar(2) FK→statustagihan | Status tagihan |
| cetakspt | int(11) | Jumlah cetak SPT |
| denda | double | Jumlah denda (Rp) |
| diskon | int(11) | Jumlah diskon (Rp) |
| user_id_aprover | varchar(11) FK→user | User approver |
| kenadiskon | varchar(1) | Flag eligibilitas diskon |
| kenadenda | varchar(1) | Flag eligibilitas denda |
| nokuitansi | varchar(16) FK→kuitansi | Nomor kuitansi |
| ketaproval | varchar(200) | Keterangan approval |

**Statistik:** 10.000+ record tagihan (periode 2012–2025)

---

##### `kuitansi` — Data Kuitansi/Tanda Terima

Tabel penyimpanan kuitansi resmi setiap transaksi pembayaran.

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| nokuitansi | varchar(16) PK | Nomor kuitansi unik |
| idipkl | varchar(5) FK→pelanggan | ID pelanggan |
| tglbayar | datetime | Waktu pembayaran |
| nama | varchar(45) | Nama pelanggan |
| cluster | varchar(25) | Nama klaster |
| blok | varchar(10) | Blok |
| nokavling | varchar(15) | Nomor kavling |
| totaltagihan | double | Total tagihan pokok (Rp) |
| totaldenda | double | Total denda (Rp) |
| jumlahtotal | double | Grand total (Rp) |
| jumlahtagihan | double | Jumlah tagihan yang dibayar |
| loket | varchar(30) | Nama loket |
| kasir | varchar(30) | Nama kasir |
| rincianbulan | varchar(200) | Detail bulan yang dibayar |
| idcarabayar | varchar(1) FK→carabayar | Metode bayar |
| idlewatbayar | varchar(1) FK→lewatbayar | Jalur bayar |

**Statistik:** 1.000+ record kuitansi

---

##### `piutang` — Piutang / Tagihan Belum Lunas

Pelacakan tagihan outstanding (belum terbayar).

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| idpiutang | varchar(30) PK | ID piutang |
| pertahun | varchar(4) | Tahun |
| perbulan | varchar(2) | Bulan |
| idtagihan | varchar(14) FK→tagihan | Referensi tagihan |
| tagihan | double | Jumlah piutang (Rp) |

---

##### `piutang2` — Piutang Varian 2

Tabel pelacakan piutang alternatif dengan struktur serupa `piutang`.

---

##### `piutangkav` — Piutang per Kavling

Tracking piutang dengan pengelompokan per kavling.

---

##### `tagihandeposit` — Tagihan Deposit

Pengelolaan tagihan deposit dengan struktur mirip tabel `tagihan`.

---

##### `nilaitagihankhusus` — Nilai Tagihan Khusus

Tabel penyimpanan tarif/nominal tagihan khusus yang berbeda dari tarif standar klaster.

---

##### `pelangganforupdate` — Tracking Update Pelanggan

Mencatat perubahan data pelanggan untuk keperluan audit.

---

##### `pelanggan_bck` & `pelanggan_bck2` — Backup Data Pelanggan

Tabel cadangan (backup) data master pelanggan.

---

##### `tagihan22` — Tagihan Varian

Tabel tagihan varian untuk keperluan khusus.

---

#### VIEWS (Virtual Tables untuk Query)

| View | Keterangan |
|------|-----------|
| `vw_pelanggan` | View utama data pelanggan dengan join ke referensi |
| `vw_pelanggan2` | View pelanggan varian |
| `vw_pelanggan_bangunan` | Khusus pelanggan tipe Bangunan |
| `vw_pelanggan_kavling` | Khusus pelanggan tipe Kavling |
| `vw_pelanggan_fordetail` | Detail lengkap pelanggan |
| `vw_tagihan_blmlunas` | Tagihan yang belum lunas |
| `vw_tagihan_fordetail` | Detail tagihan dengan join ke pelanggan |

---

#### FUNGSI & STORED PROCEDURE DATABASE

##### Function: `f_denda`

Menghitung jumlah denda berdasarkan:
- **Input:** `tagihannya` (double), `bulannya` (date), `kenadendanya` (varchar)
- **Logika:** Denda 3% dari tagihan, hanya dikenakan jika pembayaran dilakukan setelah tanggal 20 bulan berjalan
- **Output:** Nominal denda (double)

```sql
-- Pseudocode logika f_denda:
IF (hari_bayar > 20) AND (kenadenda = 'YA') THEN
    RETURN tagihan * 0.03
ELSE
    RETURN 0
END IF
```

##### Stored Procedure: `sp_get_tagihan`

Mengambil data tagihan berdasarkan ID pelanggan untuk keperluan pemrosesan di loket.

---

### Statistik Database

| Keterangan | Nilai |
|-----------|-------|
| Jumlah tabel | 34 (termasuk views) |
| Jumlah view | 7 |
| Stored function | 2 |
| Stored procedure | 1 |
| Ukuran dump SQL | ~512 KB |
| Record tagihan | 10.000+ |
| Record kuitansi | 1.000+ |
| Periode data | 2010 – 2025 |

---

## Hak Akses Pengguna

| Level | Nama | Akses |
|-------|------|-------|
| 1 | Root | Akses penuh ke semua fitur |
| 2 | Back Office | Penagihan, laporan, approval, master data |
| 3 | Loket | Transaksi pembayaran, cetak kuitansi |
| 4 | Customer Service | Info pelanggan, info tagihan (read-only) |

Menu dan fitur yang tampil disesuaikan secara otomatis berdasarkan level pengguna yang login.

---

## Cara Instalasi

### Prasyarat

- XAMPP (Apache + MySQL + PHP 5.6+)
- Browser modern (Chrome, Firefox, Edge)

### Langkah Instalasi

1. **Letakkan folder proyek di htdocs:**
   ```
   C:\xampp\htdocs\grandduta\
   ```

2. **Import database:**
   - Buka phpMyAdmin: `http://localhost/phpmyadmin`
   - Buat database baru bernama `granddutadb`
   - Import file: `database baru/granddutadb (2).sql`

3. **Konfigurasi koneksi database** di `application/config/database.php`:
   ```php
   $db['default']['hostname'] = 'localhost';
   $db['default']['username'] = 'root';
   $db['default']['password'] = '';
   $db['default']['database'] = 'granddutadb';
   ```

4. **Sesuaikan base URL** di `application/config/config.php`:
   ```php
   $config['base_url'] = 'http://localhost/grandduta/';
   ```

5. **Akses aplikasi** melalui browser:
   ```
   http://localhost/grandduta/
   ```

6. **Login** dengan salah satu akun yang tersedia (brata, rini, ayu, dll.)

---

## Konfigurasi

### `application/config/config.php`

```php
$config['base_url']      = 'http://localhost/grandduta/';
$config['index_page']    = 'index.php';
$config['uri_protocol']  = 'REQUEST_URI';
$config['charset']       = 'UTF-8';
$config['language']      = 'english';
```

### `application/config/database.php`

```php
$db['default']['hostname']    = 'localhost';
$db['default']['username']    = 'root';
$db['default']['password']    = '';
$db['default']['database']    = 'granddutadb';
$db['default']['dbdriver']    = 'mysql';
$db['default']['char_set']    = 'utf8';
$db['default']['dbcollat']    = 'utf8_general_ci';
$db['default']['active_r']    = TRUE;
```

### `application/config/routes.php`

```phpa  
$route['default_controller'] = 'home';
```

---

## Catatan Pengembangan

- File `Dev-Log.txt` berisi log perubahan dan matriks role fitur
- Tabel `pelanggan_bck` dan `pelanggan_bck2` adalah backup manual data pelanggan
- Folder `database baru/` berisi dump database terbaru untuk keperluan restore
- Sistem menggunakan FPDF untuk generasi PDF — kompatibel dengan PHP 5.6+

---

*Sistem ini dikembangkan untuk keperluan internal manajemen perumahan Grand Duta.*
