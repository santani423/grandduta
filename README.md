# GRANDDUTA — Sistem Manajemen Properti & Penagihan

Sistem informasi manajemen tagihan dan penagihan untuk perumahan **Grand Duta**. Dibangun di atas framework **CodeIgniter (PHP MVC)** dengan database **MySQL**, sistem ini mengelola seluruh siklus hidup data properti: dari pencatatan pelanggan, penerbitan tagihan bulanan, pemrosesan pembayaran di loket, manajemen cicilan dan piutang, hingga pelaporan keuangan dan pencetakan dokumen resmi.

---

## Daftar Isi

1. [Gambaran Umum Sistem](#gambaran-umum-sistem)
2. [Teknologi & Stack](#teknologi--stack)
3. [Arsitektur Aplikasi](#arsitektur-aplikasi)
4. [Alur Sistem & Logika Bisnis](#alur-sistem--logika-bisnis)
   - [A. Autentikasi & Otorisasi](#a-autentikasi--otorisasi)
   - [B. Master Data Pelanggan](#b-master-data-pelanggan)
   - [C. Master Data Klaster](#c-master-data-klaster)
   - [D. Siklus Penagihan Bulanan](#d-siklus-penagihan-bulanan)
   - [E. Pemrosesan Pembayaran di Loket](#e-pemrosesan-pembayaran-di-loket)
   - [F. Manajemen Cicilan](#f-manajemen-cicilan)
   - [G. Pelunasan Mundur](#g-pelunasan-mundur)
   - [H. Pembatalan Transaksi (Reversal)](#h-pembatalan-transaksi-reversal)
   - [I. Modul Informasi & Inquiry](#i-modul-informasi--inquiry)
   - [J. Pencetakan Dokumen PDF](#j-pencetakan-dokumen-pdf)
   - [K. Laporan Keuangan](#k-laporan-keuangan)
   - [L. Manajemen Piutang](#l-manajemen-piutang)
5. [Struktur Proyek Lengkap](#struktur-proyek-lengkap)
6. [Rancangan Database Detail](#rancangan-database-detail)
7. [Hak Akses Pengguna (RBAC)](#hak-akses-pengguna-rbac)
8. [Cara Instalasi](#cara-instalasi)
9. [Konfigurasi Sistem](#konfigurasi-sistem)
10. [Rekomendasi Fitur Pengembangan](#rekomendasi-fitur-pengembangan)

---

## Gambaran Umum Sistem

**GRANDDUTA** adalah aplikasi web berbasis PHP/MySQL yang dirancang khusus untuk manajemen operasional perumahan Grand Duta — sebuah kompleks perumahan berskala besar yang terdiri dari **14 klaster**, ratusan unit properti, dan berbagai tipe kepemilikan (bangunan, kavling developer, kavling pelanggan).

Sistem berperan sebagai **back-end operasional** yang menghubungkan empat peran utama: administrator (Root), staf back office, operator loket, dan customer service — masing-masing dengan akses dan kemampuan yang berbeda.

### Lingkup Sistem

```
┌─────────────────────────────────────────────────────────────┐
│                    GRANDDUTA SYSTEM                         │
│                                                             │
│  [Master Data] → [Penagihan] → [Loket] → [Laporan]         │
│       ↓               ↓           ↓           ↓            │
│  Pelanggan      Tagihan Bulanan  Kuitansi  PDF Report       │
│  Klaster        Tagihan Khusus   SPT/SPK   Excel/Print      │
│  Properti       Tagihan Mundur   Cicilan   Piutang          │
│                 Approval Flow    Reversal  Kolektor         │
└─────────────────────────────────────────────────────────────┘
```

---

## Teknologi & Stack

| Komponen | Detail |
|----------|--------|
| **Framework** | CodeIgniter 2.x (PHP MVC) |
| **Bahasa** | PHP 5.6+ |
| **Database** | MySQL 5.0.7+ / MariaDB 10.1.31 |
| **Nama DB** | `granddutadb` |
| **Frontend** | HTML5, CSS, JavaScript, Bootstrap |
| **PDF** | FPDF Library + Custom CFPDF Wrapper |
| **Autentikasi** | Session-based, password MD5 |
| **Server** | Apache via XAMPP |
| **Charset** | UTF-8 |

### Library & Dependency Utama

| Library | Fungsi |
|---------|--------|
| `fpdf/` | Generasi PDF (base library) |
| `cfpdf.php` | Wrapper custom FPDF untuk templating |
| `FPDF_AutoWrapTable` | Tabel PDF dengan auto-wrap teks |
| `auth.php` | Autentikasi & sesi pengguna |
| `template.php` | Wrapper tampilan (layout engine) |
| `menu_helper.php` | Generator menu dinamis berdasarkan role |
| `datecbo_helper.php` | Dropdown pemilih tanggal/bulan/tahun |
| `notify_helper.php` | Flash message notifikasi |
| `pagination` | Paginasi data tabel |
| `form_validation` | Validasi form input |

---

## Arsitektur Aplikasi

Sistem mengikuti pola **MVC (Model-View-Controller)** standar CodeIgniter:

```
Browser Request
      │
      ▼
  index.php  ← Entry point tunggal
      │
      ▼
  Router (config/routes.php)
      │  default controller: home
      ▼
  Controller (application/controllers/)
      │
      ├── load Model → query/mutasi database
      ├── validasi input / otorisasi
      ├── proses logika bisnis
      └── load View → render HTML ke browser
                │
                ├── template.php  ← layout wrapper
                ├── header + sidebar menu
                ├── content view (per fitur)
                └── footer
```

### Library yang Di-autoload

Setiap request secara otomatis memuat:

**Libraries:** `session`, `database`, `encrypt`, `form_validation`, `auth`, `table`, `parser`, `template`, `pagination`

**Helpers:** `form`, `html`, `url`, `file`, `dir2array`, `autochrumb`, `datecbo`, `notify`

---

## Alur Sistem & Logika Bisnis

Bagian ini menjelaskan alur lengkap dari setiap modul utama — mulai dari input pengguna, logika di controller dan model, hingga output ke database atau dokumen.

---

### A. Autentikasi & Otorisasi

#### Alur Login

```
Pengguna buka /login
      │
      ▼
login_view.php → form username + password
      │
      ▼ POST /login/proses_login
      │
      ▼
login.php (Controller)
      │
      ▼
login_model.php::cek_login($username, $password)
      │  Query: SELECT * FROM user
      │         WHERE user_username = '$username'
      │         AND user_password = MD5('$password')
      │
      ├─[Tidak ditemukan]→ flash error "Username/password salah"
      │                    redirect back ke /login
      │
      └─[Ditemukan]→
              │
              ▼
        auth.php::do_login()
              │  Set session:
              │    user_id, nama, username,
              │    password (MD5), level
              │
              ▼
        redirect ke /home
```

#### Alur Otorisasi Menu (RBAC)

```
Setiap request ke controller:
      │
      ▼
auth.php::restrict()
      │  Cek: $this->session->userdata('user_id')
      │
      ├─[Tidak ada session]→ redirect /login
      │
      └─[Ada session]→ lanjut eksekusi controller
              │
              ▼
        (Optional) auth.php::cek_menu($idmenu)
              │  Query: SELECT menu_allowed FROM menu
              │         WHERE menu_id = $idmenu
              │  Cek apakah level user ada di pipe-separated list
              │
              ├─[Tidak diizinkan]→ redirect /home dengan pesan
              └─[Diizinkan]→ lanjut proses
```

#### Struktur Level & Akses

| Level | Nama | Kemampuan |
|-------|------|-----------|
| 1 | **Root** | Akses penuh — semua fitur + approval akhir + manajemen user |
| 2 | **Back Office** | Penagihan, approval, laporan, master data, piutang |
| 3 | **Loket** | Transaksi pembayaran, cetak kuitansi, cetak SPT/SPK |
| 4 | **Customer Service** | Read-only: info pelanggan, info tagihan |

#### Menu Dinamis

Menu yang muncul di sidebar ditentukan oleh query:

```php
// usermodel.php::get_menu_for_level($level)
SELECT * FROM menu
WHERE FIND_IN_SET('$level', REPLACE(menu_allowed, '|', ','))
ORDER BY menu_urut
```

Data `menu_allowed` disimpan dalam format pipe-separated: `"1|2|3"` artinya level 1, 2, dan 3 boleh akses.

---

### B. Master Data Pelanggan

#### Alur Tambah Pelanggan Baru

```
Back Office / Root buka /master_pelanggan
      │
      ▼
master_pelanggan.php (Controller) → index()
      │  load: vw_pelanggan (daftar semua pelanggan)
      │  tampil: master/list_pelanggan.php
      │
      ▼ Klik "Tambah Pelanggan"
      │
      ▼
form_tambah_pelanggan.php
      │  Field wajib: ID (5 char), nama, klaster, blok, nokav, jenis properti
      │  Field opsional: HP, telpon, alamat KTP, kecamatan, LB, LT, email,
      │                  tgl serah terima, status hunian, status pelanggan
      │
      ▼ POST /master_pelanggan/simpan
      │
      ▼
Controller::simpan()
      │
      ├─ form_validation: required fields check
      ├─ cek duplikat: SELECT idipkl FROM pelanggan WHERE idipkl = '$id'
      │
      ├─[Duplikat]→ error "ID sudah digunakan"
      │
      └─[Unik]→
              │
              ▼
        master_model.php::insert_pelanggan($data)
              │  INSERT INTO pelanggan VALUES (...)
              │
              ▼
        redirect → daftar pelanggan + flash "Berhasil ditambahkan"
```

#### Alur Konversi Kavling ke Bangunan

Fitur khusus `kavling_ke_bangunan.php` untuk mengubah tipe properti `K` (Kavling Developer) menjadi `B` (Bangunan) setelah unit selesai dibangun:

```
Pilih pelanggan dengan idbork = 'K'
      │
      ▼
UPDATE pelanggan SET idbork = 'B',
                     tglserahterima = NOW()
WHERE idipkl = '$id'
```

#### Validasi ID Pelanggan

- Panjang tepat **5 karakter**
- Unik di seluruh tabel `pelanggan`
- Format bebas alphanumerik (biasanya: `DO001`, `GA012`, dll.)

---

### C. Master Data Klaster

#### Struktur Klaster

14 klaster yang dikelola, masing-masing memiliki **tarif bulanan** yang menjadi dasar penghitungan tagihan:

| ID | Nama Klaster | Keterangan |
|----|-------------|------------|
| DO | Dolomite | — |
| GA | Garnet | — |
| JA | Jade | — |
| AM | Amber | — |
| DI | Diamond | — |
| EM | Emerald | — |
| AL | Alexandrite | — |
| BE | Beryl | — |
| CH | Chrysocolla | — |
| RK | Ruko (berbagai varian) | Properti komersial |

#### Pengelolaan Tarif

```
master_cluster.php → form edit tarif
      │
      ▼ POST update tarif
      │
      ▼
master_cluster_model.php::update_tarif()
      │  UPDATE cluster SET tarif = '$tarif'
      │  WHERE idcluster = '$id'
      │
      ▼
Tagihan bulan BERIKUTNYA otomatis menggunakan tarif baru
(tagihan yang sudah diterbitkan TIDAK berubah retroaktif)
```

---

### D. Siklus Penagihan Bulanan

Ini adalah alur inti sistem. Penagihan mengikuti siklus bulanan yang terdiri dari 4 fase:

```
┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────┐
│  SIAPKAN │ → │ APPROVAL │ → │PENERBITAN│ → │PEMBAYARAN│
│ TAGIHAN  │    │BACK OFFICE│   │(Aktif di │    │ DI LOKET │
│          │    │ / ROOT   │    │  loket)  │    │          │
└──────────┘    └──────────┘    └──────────┘    └──────────┘
```

#### Fase 1: Penyiapan Tagihan (`penyiapan_tagihan.php`)

```
Back Office pilih: Tahun + Bulan tagihan
      │
      ▼
penyiapan_tagihan.php (Controller) → proses()
      │
      ▼
penagihan_model.php::get_pelanggan_aktif()
      │  SELECT idipkl, idcluster FROM pelanggan
      │  WHERE idstatuspelanggan = 'AK' (Aktif)
      │  -- Pelanggan "Rumah Kosong" dan "Tidak Aktif" dilewati
      │
      ▼ Untuk setiap pelanggan aktif:
      │
      ├─ Ambil tarif: SELECT tarif FROM cluster
      │               WHERE idcluster = '$idcluster'
      │
      ├─ Cek tagihan sudah ada:
      │   SELECT COUNT(*) FROM tagihan
      │   WHERE idipkl = '$id' AND tahun = '$thn' AND bulan = '$bln'
      │
      ├─[Sudah ada]→ skip (tidak buat duplikat)
      │
      └─[Belum ada]→
              │
              ▼
        INSERT INTO tagihan
          (idipkl, tahun, bulan, tagihan, idstatustagihan,
           kenadiskon, kenadenda, user_id)
        VALUES
          ('$id', '$thn', '$bln', '$tarif', '01',
           'TDK', 'YA', '$user_id')
        -- Status '01' = Belum Bayar
        -- kenadenda 'YA' = akan dikenai denda jika terlambat
```

#### Fase 1b: Penyiapan Tagihan Khusus (`penyiapan_tagihan_khusus.php`)

Untuk pelanggan dengan nominal tagihan berbeda dari tarif standar klaster:

```
Input: ID Pelanggan + Nominal Khusus + Periode
      │
      ▼
Ambil nilai dari nilaitagihankhusus
WHERE idipkl = '$id' AND tahun = '$thn' AND bulan = '$bln'
      │
      ▼
INSERT tagihan dengan nominal = nilaikhusus (bukan tarif cluster)
```

#### Fase 1c: Penyiapan Tagihan Mundur (`penyiapan_tagihan_mundur.php`)

Untuk menerbitkan tagihan pada periode bulan-bulan yang sudah lampau:

```
Input: ID Pelanggan + Periode Mundur (mis. Jan 2023)
      │
      ▼
Validasi: tagihan periode tersebut belum ada
      │
      ▼
INSERT tagihan dengan tahun/bulan historis
Denda dihitung berdasarkan hari ini vs. jatuh tempo historis
```

#### Fase 2: Approval Tagihan (`aproval_penagihan.php`)

```
Back Office / Root buka daftar tagihan "menunggu approval"
      │
      ▼
Tampil: tagihan dengan status '01' yang belum pernah dibayar
dan belum di-approve
      │
      ▼ Klik "Approve" + isi keterangan
      │
      ▼
UPDATE tagihan
SET user_id_aprover = '$approver_id',
    ketaproval = '$keterangan'
WHERE idtagihan = '$id'
-- Setelah approve, tagihan AKTIF bisa dibayar di loket
```

#### Logika Penghitungan Denda

Denda dihitung oleh fungsi database `f_denda()`:

```sql
-- Function f_denda(tagihannya DOUBLE, bulannya DATE, kenadendanya VARCHAR)
-- Dipanggil saat pembayaran, BUKAN saat pembuatan tagihan

IF DAY(NOW()) > 20        -- Pembayaran setelah tanggal 20
   AND kenadendanya = 'YA'  -- Pelanggan eligible denda
THEN
    RETURN tagihannya * 0.03  -- Denda 3% dari tagihan pokok
ELSE
    RETURN 0
END IF
```

**Catatan penting:** Denda bersifat **dinamis** — dihitung pada saat transaksi pembayaran terjadi, bukan saat tagihan dibuat. Artinya tagihan yang sama bisa: denda=0 jika dibayar tanggal 1-20, denda=3% jika dibayar tanggal 21-31.

---

### E. Pemrosesan Pembayaran di Loket

Ini adalah alur transaksi utama yang dilakukan operator loket setiap hari:

```
Operator Loket buka /loket
      │
      ▼
loket.php::index() → tampil form pencarian pelanggan
      │
      ▼ Input: ID Pelanggan → Tekan "Cari"
      │
      ▼
loket.php::cari_tagihan()
      │
      ▼
loket_model.php::get_tagihan_belum_lunas($idipkl)
      │  Query dari vw_tagihan_blmlunas:
      │  SELECT t.*, p.namapelanggan, c.namacluster,
      │         p.blok, p.nokav
      │  FROM tagihan t
      │  JOIN pelanggan p ON t.idipkl = p.idipkl
      │  JOIN cluster c ON p.idcluster = c.idcluster
      │  WHERE t.idipkl = '$id'
      │  AND t.idstatustagihan = '01'  -- Belum Bayar
      │  ORDER BY t.tahun, t.bulan
      │
      ▼
Tampil: daftar tagihan belum bayar per periode
        dengan preview total + denda yang akan dikenakan
      │
      ▼ Operator pilih tagihan (bisa lebih dari 1) +
        pilih cara bayar (Cash/Debit) + input nama kasir + kode loket
      │
      ▼ Klik "Bayar"
      │
      ▼
loket.php::proses_bayar()
      │
      ├── Hitung denda per tagihan:
      │     CALL/SELECT f_denda(tagihan, bulannya, kenadenda)
      │
      ├── Hitung total:
      │     totaltagihan = SUM(tagihan per baris)
      │     totaldenda   = SUM(denda per baris)
      │     jumlahtotal  = totaltagihan + totaldenda
      │
      ├── Generate nomor kuitansi:
      │     Format: GD.YYYY.MM.NNNNNN (16 karakter)
      │     Contoh: GD.2025.06.000123
      │     Logic: SELECT MAX(nokuitansi) FROM kuitansi
      │            WHERE tahun = YEAR(NOW()) AND bulan = MONTH(NOW())
      │            → increment counter
      │
      ├── INSERT INTO kuitansi
      │     (nokuitansi, idipkl, tglbayar, nama, cluster, blok,
      │      nokavling, totaltagihan, totaldenda, jumlahtotal,
      │      jumlahtagihan, loket, kasir, rincianbulan,
      │      idcarabayar, idlewatbayar)
      │
      ├── UPDATE tagihan SET
      │     idstatustagihan = '02',  -- Lunas
      │     tglbayar = NOW(),
      │     nokuitansi = '$nokuitansi',
      │     denda = '$denda',
      │     user_id = '$kasir_id',
      │     idloket = '$loket'
      │   WHERE idtagihan IN ($selected_ids)
      │
      └──▼
         Print kuitansi otomatis (PDF via CFPDF)
         → tampil halaman cetak kuitansi
```

#### Format Nomor Kuitansi

```
GD . YYYY . MM . NNNNNN
│    │      │    └── Counter 6 digit (reset per bulan)
│    │      └─────── Bulan (2 digit)
│    └────────────── Tahun (4 digit)
└─────────────────── Prefix "GD" = Grand Duta

Contoh: GD.2025.06.000001
```

---

### F. Manajemen Cicilan

Untuk pelanggan yang tidak mampu membayar sekaligus (terutama tunggakan multiple bulan):

```
Back Office / Loket buka /cicilan
      │
      ▼
cicilan.php::index() → form input cicilan
      │
      ▼ Input: ID Pelanggan + Nominal Cicilan + Tanggal
      │
      ▼
cicilan.php::simpan()
      │
      ▼
cicilan_model.php::insert_cicilan($data)
      │
      ├── INSERT INTO cicilan (idipkl, nominal, tglcicil, user_id)
      │
      ├── Catatan: cicilan TIDAK langsung melunasi tagihan tertentu
      │   — cicilan dicatat sebagai kredit pelanggan
      │   — Back Office secara manual mengalokasikan ke tagihan mana
      │
      └──▼
         Update saldo cicilan pelanggan
```

#### Laporan Cicilan (`penagihan_cicilan.php`)

```
Filter: ID Pelanggan + Periode
      │
      ▼
SELECT c.*, p.namapelanggan
FROM cicilan c
JOIN pelanggan p ON c.idipkl = p.idipkl
WHERE c.idipkl = '$id'
ORDER BY c.tglcicil
      │
      ▼
Tampil: tabel riwayat cicilan + total yang sudah dibayar
        vs. total piutang = sisa tunggakan
```

---

### G. Pelunasan Mundur

Untuk memproses pembayaran tagihan periode lampau yang masih outstanding:

```
Back Office buka /pelunasan_mundur
      │
      ▼ Input: ID Pelanggan + Periode yang akan dilunasi
      │
      ▼
pelunasan_mundur.php::proses()
      │
      ├── Ambil tagihan lama: SELECT * FROM tagihan
      │   WHERE idipkl = '$id' AND tahun = '$thn' AND bulan = '$bln'
      │   AND idstatustagihan = '01'
      │
      ├── Hitung denda akumulatif (berdasarkan hari ini,
      │   bukan tanggal tagihan) → biasanya lebih besar
      │
      ├── Buat kuitansi khusus "pelunasan mundur"
      │
      └── UPDATE tagihan SET idstatustagihan = '02'
```

---

### H. Pembatalan Transaksi (Reversal)

Untuk membatalkan pembayaran yang sudah terjadi (salah input, dll.):

#### Alur Pembatalan Multi-Level Approval

```
Operator Loket buka /prosesbatal
      │
      ▼ Input: Nomor Kuitansi yang akan dibatalkan + Alasan
      │
      ▼
prosesbatal.php::ajukan_batal()
      │
      ▼
batal_model.php::insert_pengajuan_batal($nokuitansi, $alasan)
      │  INSERT INTO pengajuan_batal
      │    (nokuitansi, alasan, user_id, status_batal='PENDING')
      │
      └──▼
         [STATUS: PENDING — menunggu approval]

─────────────────────────────────────────────

Back Office / Root buka daftar pengajuan batal
      │
      ▼
Tampil: daftar pembatalan PENDING
      │
      ▼ Klik "Setujui" atau "Tolak"
      │
      ├─[TOLAK]→
      │    UPDATE pengajuan_batal SET status = 'DITOLAK'
      │    Notifikasi ke operator loket
      │
      └─[SETUJUI]→
              │
              ▼
        batal_model.php::proses_batal($nokuitansi)
              │
              ├── UPDATE tagihan SET
              │     idstatustagihan = '01',  -- Kembali Belum Bayar
              │     tglbayar = NULL,
              │     nokuitansi = NULL,
              │     denda = 0,
              │     user_id = NULL
              │   WHERE nokuitansi = '$nokuitansi'
              │
              ├── DELETE FROM kuitansi
              │   WHERE nokuitansi = '$nokuitansi'
              │   -- Atau: UPDATE status menjadi 'BATAL'
              │
              └── Log audit di tabel pembatalan
                  → Laporan pembatalan (lapbatal.php)
```

#### Laporan Pembatalan (`lapbatal.php`)

Menampilkan seluruh riwayat pembatalan dengan filter periode dan status — berfungsi sebagai **audit trail** transaksi yang dibatalkan.

---

### I. Modul Informasi & Inquiry

Read-only module untuk Customer Service dan pengecekan data:

#### Info Pelanggan (`info_pelanggan.php` / `infoplg.php`)

```
Input: Nama / Klaster / Blok / No. Kavling (flexible search)
      │
      ▼
info_model.php::cari_pelanggan($keyword)
      │  SELECT * FROM vw_pelanggan_fordetail
      │  WHERE namapelanggan LIKE '%$keyword%'
      │     OR idcluster = '$cluster'
      │     OR (blok = '$blok' AND nokav = '$kav')
      │
      ▼
Tampil: detail pelanggan — nama, alamat, klaster, blok,
        kavling, status hunian, status pelanggan, kontak
```

#### Info Tagihan (`info_tagihan.php` / `infotag.php`)

```
Input: ID Pelanggan
      │
      ▼
info_model.php::get_riwayat_tagihan($idipkl)
      │  SELECT t.tahun, t.bulan, t.tagihan,
      │         t.denda, t.idstatustagihan,
      │         k.nokuitansi, k.tglbayar, k.kasir
      │  FROM tagihan t
      │  LEFT JOIN kuitansi k ON t.nokuitansi = k.nokuitansi
      │  WHERE t.idipkl = '$id'
      │  ORDER BY t.tahun DESC, t.bulan DESC
      │
      ▼
Tampil: tabel riwayat tagihan per bulan —
        status (Lunas/Belum Bayar), tanggal bayar, kasir
```

---

### J. Pencetakan Dokumen PDF

Seluruh dokumen dihasilkan menggunakan **FPDF** dengan wrapper **CFPDF**:

#### SPT — Surat Pembayaran Tunai (`cetakspt.php`)

```
Input: Nomor Kuitansi
      │
      ▼
spt_model.php::get_data_spt($nokuitansi)
      │  SELECT kuitansi.*, pelanggan.*, cluster.*
      │  FROM kuitansi
      │  JOIN pelanggan ON kuitansi.idipkl = pelanggan.idipkl
      │  JOIN cluster ON pelanggan.idcluster = cluster.idcluster
      │  WHERE nokuitansi = '$nokuitansi'
      │
      ▼
cfpdf.php::generate_spt($data)
      │  - Header: Logo + nama perusahaan
      │  - Nomor SPT, tanggal cetak
      │  - Identitas pelanggan (nama, klaster, blok, kavling)
      │  - Rincian tagihan per bulan
      │  - Total + denda + grand total
      │  - Tanda tangan kasir
      │  - Footer: nomor kuitansi
      │
      ▼
UPDATE tagihan SET cetakspt = cetakspt + 1  -- Counter berapa kali dicetak
      │
      ▼
Output: PDF inline (buka di browser) atau download
```

#### SPK — Surat Pemberitahuan Kredit (`cetakspk.php`)

```
Invoice/tagihan yang dikirim ke pelanggan sebelum pembayaran.
Berisi: rincian tagihan bulan berjalan + instruksi pembayaran.
Format: mirip SPT tetapi status "Belum Lunas"
```

#### Rekap Tagihan & Laporan (`cetak_rekap_tagihan.php`, dll.)

```
Input: Filter (Periode + Klaster + Tipe Properti)
      │
      ▼
Aggregasi data per kelompok → tabel rekap per baris
      │
      ▼
FPDF_AutoWrapTable::render($headers, $data, $widths)
      │  - Auto-wrap teks panjang
      │  - Halaman otomatis break jika overflow
      │  - Total di baris terakhir
      │
      ▼
Output PDF multi-halaman
```

---

### K. Laporan Keuangan

#### Laporan Bulanan (`laporan_bulanan.php`)

```
Input: Tahun + Bulan
      │
      ▼
Query agregasi per klaster:
      │
      │  SELECT c.namacluster,
      │         COUNT(t.idtagihan) as jml_tagihan,
      │         SUM(t.tagihan) as total_pokok,
      │         SUM(t.denda) as total_denda,
      │         SUM(CASE WHEN t.idstatustagihan='02'
      │                  THEN t.tagihan+t.denda ELSE 0 END) as total_lunas,
      │         SUM(CASE WHEN t.idstatustagihan='01'
      │                  THEN t.tagihan ELSE 0 END) as total_belum
      │  FROM tagihan t
      │  JOIN pelanggan p ON t.idipkl = p.idipkl
      │  JOIN cluster c ON p.idcluster = c.idcluster
      │  WHERE t.tahun = '$thn' AND t.bulan = '$bln'
      │  GROUP BY c.idcluster
      │
      ▼
Tampil tabel + cetak PDF:
  - Per klaster: jumlah tagihan, total pokok, denda, lunas, belum
  - Baris TOTAL di akhir
  - Persentase collection rate
```

#### LPP — Laporan Penerimaan Pembayaran (`lpp.php`)

```
Input: Tanggal (harian)
      │
      ▼
SELECT k.*, p.namapelanggan, c.namacluster
FROM kuitansi k
JOIN pelanggan p ON k.idipkl = p.idipkl
JOIN cluster c ON p.idcluster = c.idcluster
WHERE DATE(k.tglbayar) = '$tanggal'
ORDER BY k.tglbayar
      │
      ▼
Daftar seluruh transaksi pembayaran pada tanggal tersebut
  - Per baris: nomor kuitansi, nama, klaster, total, kasir, loket
  - Grand total di akhir
  - Breakdown per loket / per kasir
```

#### RPP — Rekonsiliasi Pembayaran (`rpp.php`)

```
Membandingkan:
  - Total tagihan yang seharusnya diterima (dari tabel tagihan)
  - Total yang benar-benar diterima (dari tabel kuitansi)
  - Selisih = piutang yang masih outstanding
```

#### Laporan Kolektor (`laporan_collector.php`)

```
SELECT user_id, user_nama,
       COUNT(*) as jml_transaksi,
       SUM(jumlahtotal) as total_dikumpulkan
FROM kuitansi k
JOIN user u ON k.kasir = u.user_nama
WHERE MONTH(tglbayar) = '$bulan'
  AND YEAR(tglbayar) = '$tahun'
GROUP BY user_id
      │
      ▼
Performance per kolektor/kasir dalam satu periode
```

---

### L. Manajemen Piutang

#### Piutang Bulanan (`piutang` table)

```
Sistem mencatat tagihan yang belum dibayar sebagai piutang:

Sumber piutang:
  - Tagihan yang melewati akhir bulan dengan status '01' (Belum Bayar)
  - Di-snapshot ke tabel piutang pada awal bulan berikutnya

SELECT tahun, bulan,
       COUNT(*) as jml_pelanggan_nunggak,
       SUM(tagihan) as total_piutang,
       SUM(denda) as total_denda_pending
FROM tagihan
WHERE idstatustagihan = '01'
GROUP BY tahun, bulan
ORDER BY tahun, bulan
```

#### Analisis Umur Piutang

```
Kategorisasi piutang berdasarkan usia:
  - < 30 hari (bulan berjalan)
  - 30-60 hari (1 bulan tunggak)
  - 60-90 hari (2 bulan tunggak)
  - > 90 hari (3+ bulan tunggak — risiko tinggi)
```

---

## Struktur Proyek Lengkap

```
grandduta/
├── index.php                        ← Entry point aplikasi
├── README.md                        ← Dokumentasi ini
├── Dev-Log.txt                      ← Log perubahan & catatan dev
│
├── application/
│   ├── config/
│   │   ├── config.php               ← Base URL, charset, session
│   │   ├── database.php             ← Koneksi MySQL
│   │   ├── routes.php               ← Default controller: home
│   │   ├── autoload.php             ← Library & helper yang di-load otomatis
│   │   └── ...
│   │
│   ├── controllers/                 ← 43 Controller
│   │   ├── home.php                 ← Dashboard utama
│   │   ├── login.php                ← Login / logout
│   │   ├── ubah_password.php        ← Ganti password
│   │   ├── dashboard.php            ← Widget dashboard
│   │   ├── nextapp.php              ← Interface aplikasi berikutnya
│   │   │
│   │   │── [MASTER DATA]
│   │   ├── master_pelanggan.php     ← CRUD data pelanggan
│   │   ├── master_cluster.php       ← CRUD data klaster & tarif
│   │   ├── master.php               ← Master data umum
│   │   ├── kavling_ke_bangunan.php  ← Konversi tipe properti
│   │   │
│   │   │── [PENAGIHAN]
│   │   ├── penagihan.php            ← Manajemen tagihan
│   │   ├── penyiapan_tagihan.php    ← Generate tagihan bulanan
│   │   ├── penyiapan_tagihan_khusus.php  ← Tagihan nominal khusus
│   │   ├── penyiapan_tagihan_mundur.php  ← Tagihan periode mundur
│   │   ├── input_tagihan.php        ← Input manual tagihan
│   │   ├── update_tagihan.php       ← Update tagihan existing
│   │   ├── aproval_penagihan.php    ← Approval flow tagihan
│   │   ├── createtagihan.php        ← Utility pembuatan tagihan
│   │   │
│   │   │── [PEMBAYARAN]
│   │   ├── loket.php                ← Transaksi loket/counter
│   │   ├── cicilan.php              ← Input cicilan
│   │   ├── penagihan_cicilan.php    ← Laporan cicilan
│   │   ├── pelunasan_mundur.php     ← Pelunasan tagihan lampau
│   │   │
│   │   │── [PEMBATALAN]
│   │   ├── prosesbatal.php          ← Proses reversal
│   │   ├── lapbatal.php             ← Laporan pembatalan
│   │   │
│   │   │── [LAPORAN & CETAK]
│   │   ├── laporan_bulanan.php      ← Laporan keuangan bulanan
│   │   ├── laporan_collector.php    ← Laporan kolektor
│   │   ├── lpp.php                  ← Laporan penerimaan harian
│   │   ├── rpp.php                  ← Rekonsiliasi pembayaran
│   │   ├── cetak_master_pelanggan.php
│   │   ├── cetak_tagihan.php
│   │   ├── cetak_tagihan_perplg.php
│   │   ├── cetak_rekap_tagihan.php
│   │   ├── cetak_rekap_cluster.php
│   │   ├── cetak_rekap_kavling.php
│   │   ├── cetak_tidakangkut.php
│   │   ├── cetak_ulang_rekening.php
│   │   ├── cetakspt.php             ← Cetak SPT
│   │   ├── cetakspk.php             ← Cetak SPK
│   │   │
│   │   │── [INFORMASI]
│   │   ├── info_pelanggan.php       ← Lookup data pelanggan
│   │   ├── info_tagihan.php         ← Lookup riwayat tagihan
│   │   ├── infoplg.php              ← Alternatif info pelanggan
│   │   ├── infotag.php              ← Alternatif info tagihan
│   │   │
│   │   └── cron.php                 ← Scheduled task handler
│   │
│   ├── models/                      ← 12 Model
│   │   ├── login_model.php          ← Autentikasi user
│   │   ├── master_model.php         ← Operasi CRUD pelanggan
│   │   ├── master_cluster_model.php ← Operasi klaster
│   │   ├── penagihan_model.php      ← Engine penagihan bulanan
│   │   ├── loket_model.php          ← Transaksi pembayaran
│   │   ├── cicilan_model.php        ← Manajemen cicilan
│   │   ├── info_model.php           ← Query informasi
│   │   ├── usermodel.php            ← Menu RBAC
│   │   ├── spt_model.php            ← Data dokumen SPT/SPK
│   │   ├── batal_model.php          ← Pembatalan transaksi
│   │   ├── home_model.php           ← Agregat data dashboard
│   │   └── next_model.php           ← Interface sistem lanjutan
│   │
│   ├── views/                       ← 122 Template HTML
│   │   ├── template.php             ← Layout wrapper utama
│   │   ├── templatelogin.php        ← Layout halaman login
│   │   ├── footer.php               ← Footer global
│   │   ├── menu_level*.php          ← 8 file menu per role
│   │   ├── login/                   ← 2 view
│   │   ├── home/                    ← 5 view (dashboard, help, about)
│   │   ├── master/                  ← 13 view
│   │   ├── penagihan/               ← 30 view
│   │   ├── loket/                   ← 4 view
│   │   ├── cicilan/                 ← 3 view
│   │   ├── info/                    ← 6 view
│   │   ├── laporan/                 ← 12 view
│   │   ├── spt/                     ← 2 view
│   │   ├── batal/                   ← 2 view
│   │   └── administrasi/            ← 4 view
│   │
│   ├── libraries/
│   │   ├── auth.php                 ← Autentikasi & session
│   │   ├── template.php             ← View wrapper
│   │   ├── cfpdf.php                ← Custom PDF wrapper
│   │   └── fpdf/                    ← FPDF base library + extensions
│   │       ├── fpdf.php
│   │       ├── FPDF_AutoWrapTable.php
│   │       └── font/
│   │
│   └── helpers/
│       ├── menu_helper.php
│       ├── datecbo_helper.php
│       ├── notify_helper.php
│       ├── autochrumb_helper.php
│       ├── dir2array_helper.php
│       └── pdf_helper.php
│
├── system/                          ← CodeIgniter core (jangan diubah)
│
└── database baru/
    └── granddutadb (2).sql          ← Dump database lengkap
```

---

## Rancangan Database Detail

**Nama Database:** `granddutadb`  
**Engine:** InnoDB / MyISAM  
**Charset:** latin1 / utf8

### Diagram Relasi Entitas (ERD)

```
┌──────────┐        ┌───────────┐        ┌─────────┐
│   user   │        │ pelanggan │        │ cluster │
│──────────│        │───────────│        │─────────│
│ user_id  │◄───┐   │ idipkl PK │───────►│idcluster│
│ user_nama│    │   │ namaplg   │        │namaclust│
│ username │    │   │ idcluster │        │ tarif   │
│ password │    │   │ blok      │        └─────────┘
│ user_lvl │    │   │ nokav     │
└──────────┘    │   │ idbork    │◄────── bork (B/K/P)
      │         │   │ nohp      │
      │         │   │ idhuni    │◄────── huni (1/2)
      ▼         │   │ idstatplg │◄────── statuspelanggan
┌──────────┐    │   └───────────┘
│  level   │    │         │
│──────────│    │         │ 1:M
│ level_id │    │         ▼
│level_nama│    │   ┌───────────┐        ┌────────────┐
└──────────┘    │   │  tagihan  │        │  kuitansi  │
                │   │───────────│        │────────────│
┌──────────┐    │   │idtagihan  │   ┌───►│nokuitansi  │
│   menu   │    │   │ idipkl    │   │    │ idipkl     │
│──────────│    │   │ tahun     │   │    │ tglbayar   │
│ menu_id  │    └───│ user_id   │   │    │ totaltaghn │
│ menu_uri │        │idstatustag│   │    │ totaldenda │
│ allowed  │        │ tagihan   │   │    │ jumlahtotal│
└──────────┘        │ denda     │   │    │ loket      │
                    │ diskon    │   │    │ kasir      │
                    │nokuitansi │───┘    │ idcarabayar│
                    └───────────┘        └────────────┘
                          │
                          │ M:1
                          ▼
                    ┌───────────┐
                    │  piutang  │
                    │───────────│
                    │ idpiutang │
                    │pertahun   │
                    │perbulan   │
                    │ idtagihan │
                    │ tagihan   │
                    └───────────┘
```

### Tabel Referensi (Lookup Tables)

#### `user` — Pengguna Sistem

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| user_id | int(11) PK AUTO | ID unik |
| user_nama | varchar(100) | Nama lengkap |
| user_username | varchar(100) | Username login |
| user_password | varchar(100) | Hash MD5 password |
| user_level | int(5) FK→level | Role pengguna |

**Pengguna terdaftar:** brata, rini, ayu, gista, umum, ratu, beben

---

#### `level` — Role/Hak Akses

| ID | Nama |
|----|------|
| 1 | Root |
| 2 | Back Office |
| 3 | Loket |
| 4 | Customer Service |

---

#### `menu` — Item Menu Aplikasi

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| menu_id | int(11) PK | ID menu |
| menu_nama | varchar(100) | Label menu sidebar |
| menu_uri | varchar(100) | Controller URI target |
| menu_allowed | varchar(100) | Level yang boleh akses (pipe-separated) |
| menu_urut | int | Urutan tampil di sidebar |

**Jumlah menu:** 23 item, difilter per role saat login.

---

#### `cluster` — Data Klaster

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| idcluster | varchar(2) PK | Kode 2 huruf klaster |
| namacluster | varchar(20) | Nama klaster |
| tarif | double | Tarif bulanan (Rp) |

---

#### Tabel Referensi Lainnya

| Tabel | Isi |
|-------|-----|
| `bork` | Jenis properti: B=Bangunan, K=Kavling Dev, P=Kavling Pelanggan |
| `huni` | Status hunian: 1=Huni, 2=Kosong |
| `carabayar` | Metode bayar: c=Cash, d=Debit |
| `lewatbayar` | Jalur pembayaran |
| `kenadiskon` | Flag eligibilitas diskon |
| `kenadenda` | Flag eligibilitas denda keterlambatan |
| `statuspelanggan` | Aktif, Rumah Kosong, Tidak Aktif |
| `statustagihan` | 01=Belum Bayar, 02=Lunas |
| `kabupaten` | 20+ kota/kabupaten (Lampung area) |
| `kecamatan` | Kecamatan per kabupaten |
| `golongan` | Golongan pelanggan |

---

### Tabel Operasional (Data Utama)

#### `pelanggan` — Master Data Pelanggan

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| idipkl | varchar(5) PK | ID unik 5 karakter |
| namapelanggan | varchar(60) | Nama lengkap |
| idcluster | varchar(5) FK | Klaster properti |
| blok | varchar(3) | Blok perumahan |
| nokav | varchar(4) | Nomor kavling |
| idbork | varchar(1) FK | Jenis properti (B/K/P) |
| nohp | varchar(14) | Nomor HP |
| notelpon | varchar(14) | Nomor telepon |
| alamatktp | varchar(100) | Alamat KTP |
| idkecamatan | varchar(3) FK | Kecamatan |
| lb | varchar(6) | Luas bangunan (m²) |
| lt | varchar(6) | Luas tanah (m²) |
| email | varchar(60) | Email |
| tglserahterima | date | Tanggal serah terima unit |
| idhuni | varchar(1) FK | Status hunian |
| idstatuspelanggan | varchar(2) FK | Status (Aktif/dll.) |
| user_id | varchar(11) FK | User penginput |

---

#### `tagihan` — Tagihan Bulanan

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| idtagihan | int(11) PK AUTO | ID tagihan |
| idipkl | varchar(5) FK | Pelanggan |
| tahun | varchar(4) | Tahun tagihan |
| bulan | varchar(2) | Bulan tagihan (01-12) |
| tagihan | double | Nominal pokok (Rp) |
| tglbayar | datetime | Waktu pembayaran |
| idloket | varchar(2) | Kode loket pemroses |
| user_id | varchar(11) FK | Kasir pemroses |
| idstatustagihan | varchar(2) FK | Status (01/02) |
| cetakspt | int(11) | Counter cetak SPT |
| denda | double | Denda keterlambatan (Rp) |
| diskon | int(11) | Diskon (Rp) |
| user_id_aprover | varchar(11) FK | Approver tagihan |
| kenadiskon | varchar(1) | Flag diskon (Y/T) |
| kenadenda | varchar(1) | Flag denda (YA/TDK) |
| nokuitansi | varchar(16) FK | Referensi kuitansi |
| ketaproval | varchar(200) | Catatan approval |

**Volume data:** 10.000+ record (periode 2012–2025)

---

#### `kuitansi` — Tanda Terima Pembayaran

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| nokuitansi | varchar(16) PK | No. unik format GD.YYYY.MM.NNNNNN |
| idipkl | varchar(5) FK | Pelanggan |
| tglbayar | datetime | Waktu transaksi |
| nama | varchar(45) | Nama pelanggan (snapshot) |
| cluster | varchar(25) | Nama klaster (snapshot) |
| blok | varchar(10) | Blok (snapshot) |
| nokavling | varchar(15) | No. kavling (snapshot) |
| totaltagihan | double | Total nominal pokok |
| totaldenda | double | Total denda |
| jumlahtotal | double | Grand total dibayar |
| jumlahtagihan | double | Jumlah tagihan yang dilunasi |
| loket | varchar(30) | Nama/kode loket |
| kasir | varchar(30) | Nama kasir |
| rincianbulan | varchar(200) | Detail bulan yang dibayar |
| idcarabayar | varchar(1) FK | Cash/Debit |
| idlewatbayar | varchar(1) FK | Jalur pembayaran |

**Volume data:** 1.000+ record kuitansi

---

#### Tabel Piutang

| Tabel | Keterangan |
|-------|-----------|
| `piutang` | Tagihan outstanding utama |
| `piutang2` | Varian tracking piutang |
| `piutangkav` | Piutang dikelompokkan per kavling |
| `tagihandeposit` | Tagihan deposit tersendiri |

---

#### Tabel Pendukung

| Tabel | Keterangan |
|-------|-----------|
| `nilaitagihankhusus` | Nominal khusus di luar tarif klaster |
| `pelangganforupdate` | Tracking perubahan data pelanggan |
| `pelanggan_bck` | Backup manual data pelanggan |
| `pelanggan_bck2` | Backup kedua data pelanggan |
| `tagihan22` | Tabel tagihan varian keperluan khusus |

---

### Database Views (7 Views)

| View | Keterangan |
|------|-----------|
| `vw_pelanggan` | JOIN pelanggan + cluster + bork + huni + status |
| `vw_pelanggan2` | Varian view pelanggan |
| `vw_pelanggan_bangunan` | Filter hanya tipe B (Bangunan) |
| `vw_pelanggan_kavling` | Filter hanya tipe K/P (Kavling) |
| `vw_pelanggan_fordetail` | Detail lengkap dengan alamat + kecamatan |
| `vw_tagihan_blmlunas` | Tagihan status '01' (Belum Bayar) |
| `vw_tagihan_fordetail` | JOIN tagihan + pelanggan + cluster |

---

### Fungsi & Stored Procedure

#### `f_denda(tagihannya, bulannya, kenadendanya)`

```sql
-- Menghitung denda keterlambatan
-- Input:
--   tagihannya   DOUBLE  — nominal tagihan
--   bulannya     DATE    — periode tagihan
--   kenadendanya VARCHAR — flag ('YA'/'TDK')
-- Output: DOUBLE (nominal denda)

IF DAY(NOW()) > 20 AND kenadendanya = 'YA' THEN
    RETURN tagihannya * 0.03
ELSE
    RETURN 0
END IF
```

#### `sp_get_tagihan(p_idipkl)`

```sql
-- Stored procedure: ambil tagihan belum lunas per pelanggan
-- Digunakan oleh modul loket untuk menampilkan daftar tagihan
CALL sp_get_tagihan('DO001')
```

---

### Statistik Database

| Metrik | Nilai |
|--------|-------|
| Total tabel + view | 34 |
| Views | 7 |
| Stored functions | 2 |
| Stored procedures | 1 |
| Record tagihan | 10.000+ |
| Record kuitansi | 1.000+ |
| Periode data | 2012 – 2025 |
| Jumlah klaster | 14 |

---

## Hak Akses Pengguna (RBAC)

### Matriks Fitur per Role

| Fitur / Modul | Root | Back Office | Loket | CS |
|---------------|:----:|:-----------:|:-----:|:--:|
| Login | ✓ | ✓ | ✓ | ✓ |
| Dashboard | ✓ | ✓ | ✓ | ✓ |
| Ganti Password | ✓ | ✓ | ✓ | ✓ |
| Master Pelanggan (CRUD) | ✓ | ✓ | — | — |
| Master Klaster (CRUD) | ✓ | ✓ | — | — |
| Penyiapan Tagihan | ✓ | ✓ | — | — |
| Tagihan Khusus | ✓ | ✓ | — | — |
| Tagihan Mundur | ✓ | ✓ | — | — |
| Approval Tagihan | ✓ | ✓ | — | — |
| Loket / Bayar | ✓ | ✓ | ✓ | — |
| Cicilan | ✓ | ✓ | ✓ | — |
| Pelunasan Mundur | ✓ | ✓ | — | — |
| Pembatalan (Ajukan) | ✓ | ✓ | ✓ | — |
| Pembatalan (Approve) | ✓ | ✓ | — | — |
| Info Pelanggan | ✓ | ✓ | ✓ | ✓ |
| Info Tagihan | ✓ | ✓ | ✓ | ✓ |
| Laporan Bulanan | ✓ | ✓ | — | — |
| Laporan Kolektor | ✓ | ✓ | — | — |
| LPP / RPP | ✓ | ✓ | — | — |
| Cetak SPT | ✓ | ✓ | ✓ | — |
| Cetak SPK | ✓ | ✓ | ✓ | — |
| Cetak Rekap | ✓ | ✓ | — | — |
| Piutang | ✓ | ✓ | — | — |
| Manajemen User | ✓ | — | — | — |

---

## Cara Instalasi

### Prasyarat

- XAMPP (Apache + MySQL + PHP 5.6+)
- Browser modern (Chrome, Firefox, Edge)

### Langkah Instalasi

**1. Salin folder proyek ke htdocs:**
```
C:\xampp\htdocs\grandduta\
```

**2. Import database:**
- Buka phpMyAdmin: `http://localhost/phpmyadmin`
- Buat database baru bernama `granddutadb`
- Import file: `database baru/granddutadb (2).sql`

**3. Konfigurasi koneksi database** — edit `application/config/database.php`:
```php
$db['default']['hostname'] = 'localhost';
$db['default']['username'] = 'root';
$db['default']['password'] = '';
$db['default']['database'] = 'granddutadb';
```

**4. Sesuaikan base URL** — edit `application/config/config.php`:
```php
$config['base_url'] = 'http://localhost/grandduta/';
```

**5. Akses aplikasi:**
```
http://localhost/grandduta/
```

**6. Login** dengan salah satu akun yang tersedia (brata, rini, ayu, gista, umum, ratu, beben)

---

## Konfigurasi Sistem

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
$db['default']['hostname'] = 'localhost';
$db['default']['username'] = 'root';
$db['default']['password'] = '';
$db['default']['database'] = 'granddutadb';
$db['default']['dbdriver'] = 'mysql';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['active_r'] = TRUE;
```

### `application/config/routes.php`

```php
$route['default_controller'] = 'home';
```

---

## Rekomendasi Fitur Pengembangan

Berdasarkan analisa alur sistem yang ada, berikut fitur-fitur yang direkomendasikan untuk meningkatkan efisiensi operasional:

---

### 1. Notifikasi WhatsApp / SMS Otomatis ⭐ Prioritas Tinggi

**Masalah saat ini:** Pelanggan harus datang ke kantor atau dihubungi manual untuk mengetahui tagihan.

**Solusi:** Integrasi API WhatsApp Business (Fonnte / WA Gateway) atau SMS Gateway untuk mengirim notifikasi otomatis:

```
Trigger notifikasi:
- Awal bulan: "Tagihan bulan [Bulan] Rp [nominal] sudah terbit"
- H-5 jatuh tempo: "Pengingat: tagihan belum dibayar"
- Setelah bayar: "Konfirmasi pembayaran Rp [total] diterima. No. Kuitansi: GD.YYYY.MM.XXXXX"
- Tagihan mundur: "Tunggakan bulan [X] belum lunas"
```

**Implementasi:** Tambah controller `notif.php` + tabel `antrian_notif` + cron job harian.

---

### 2. Dashboard Analytics & KPI ⭐ Prioritas Tinggi

**Masalah saat ini:** Dashboard hanya menampilkan data statis.

**Solusi:** Dashboard interaktif dengan grafik dan KPI real-time:

```
Widget yang direkomendasikan:
- Collection Rate bulan ini (%) — tagihan lunas / total tagihan
- Total pendapatan bulan berjalan vs. bulan lalu (grafik bar)
- Top 10 pelanggan tunggak terlama
- Distribusi pembayaran per klaster (pie chart)
- Tren piutang 12 bulan terakhir (line chart)
- Jumlah transaksi per kasir hari ini
```

**Implementasi:** Integrasi library Chart.js atau ApexCharts, tambah query agregat di `home_model.php`.

---

### 3. Pembayaran Via Transfer Bank / Virtual Account ⭐ Prioritas Tinggi

**Masalah saat ini:** Pembayaran hanya bisa di loket (cash/debit langsung).

**Solusi:** Integrasi payment gateway (Midtrans/Xendit) dengan Virtual Account:

```
Alur:
1. Pelanggan minta Virtual Account (dari web/WhatsApp)
2. Sistem generate VA number per pelanggan per periode
3. Pelanggan transfer ke VA number
4. Callback otomatis dari payment gateway
5. Sistem update status tagihan → Lunas
6. Kuitansi dikirim via WhatsApp/email otomatis
```

**Keuntungan:** Loket tidak perlu antri panjang, pembayaran 24/7.

---

### 4. Portal Pelanggan (Self-Service Web) ⭐ Prioritas Menengah

**Masalah saat ini:** Pelanggan tidak bisa cek tagihan mandiri tanpa datang/menelepon.

**Solusi:** Sub-domain portal pelanggan `pelanggan.grandduta.com`:

```
Fitur portal:
- Login dengan ID pelanggan + nomor HP (OTP)
- Lihat tagihan bulan ini dan tunggakan
- Riwayat pembayaran (download PDF kuitansi)
- Update nomor HP dan email mandiri
- Request cetak ulang kuitansi
- Notifikasi tagihan baru
```

---

### 5. Ekspor Laporan ke Excel (XLSX) ⭐ Prioritas Menengah

**Masalah saat ini:** Laporan hanya tersedia dalam PDF — tidak bisa diolah lebih lanjut.

**Solusi:** Tambahkan tombol "Export Excel" di setiap halaman laporan menggunakan library `PHPSpreadsheet`:

```
Laporan yang perlu ekspor Excel:
- Laporan Bulanan per Klaster
- Daftar Piutang (untuk analisis umur piutang)
- Laporan Kolektor
- Daftar Master Pelanggan
- Rekap Tagihan Bulanan
```

---

### 6. Sistem Notifikasi Internal (In-App) ⭐ Prioritas Menengah

**Masalah saat ini:** Tidak ada notifikasi antar pengguna di dalam sistem.

**Solusi:** Bell notification di navbar untuk:

```
Notifikasi untuk Back Office/Root:
- Pengajuan pembatalan baru dari loket menunggu approval
- Tagihan batch baru menunggu approval
- Pelanggan dengan tunggakan > 3 bulan

Notifikasi untuk Loket:
- Pengajuan pembatalan di-approve/ditolak
- Reminder pelanggan yang perlu dihubungi
```

**Implementasi:** Tabel `notifikasi_internal` + polling AJAX tiap 60 detik.

---

### 7. Manajemen Pengguna (User Management) ⭐ Prioritas Menengah

**Masalah saat ini:** Tidak ada UI untuk tambah/hapus/edit pengguna — harus langsung query DB.

**Solusi:** Controller `manajemen_user.php` (Level Root only):

```
Fitur:
- Daftar semua user + level + status aktif
- Tambah user baru (dengan generate password temporary)
- Reset password
- Nonaktifkan user (tanpa hapus, untuk audit trail)
- Log aktivitas per user (login/logout history)
```

---

### 8. Audit Trail & Activity Log ⭐ Prioritas Menengah

**Masalah saat ini:** Tidak ada catatan siapa mengubah apa dan kapan.

**Solusi:** Tabel `audit_log` yang merekam setiap aksi penting:

```sql
CREATE TABLE audit_log (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  user_id     INT,
  aksi        VARCHAR(50),   -- 'INSERT', 'UPDATE', 'DELETE', 'LOGIN'
  tabel       VARCHAR(30),   -- 'pelanggan', 'tagihan', 'kuitansi'
  record_id   VARCHAR(20),   -- ID record yang diubah
  data_lama   TEXT,          -- JSON snapshot sebelum perubahan
  data_baru   TEXT,          -- JSON snapshot sesudah perubahan
  ip_address  VARCHAR(20),
  created_at  DATETIME
);
```

**Hook:** Ditambahkan di setiap fungsi INSERT/UPDATE/DELETE di model.

---

### 9. Backup Database Otomatis ⭐ Prioritas Menengah

**Masalah saat ini:** Backup dilakukan manual (file SQL di folder `database baru/`).

**Solusi:** Tambah controller `backup.php` (Level Root only):

```
Fitur:
- Backup manual on-demand → download file .sql.gz
- Jadwal backup otomatis via cron (harian jam 23:00)
- Simpan backup ke folder lokal + opsional upload ke Google Drive
- Retensi: simpan 30 backup terakhir, hapus yang lebih lama
- Email notifikasi jika backup gagal
```

---

### 10. Cetak Tagihan Massal (Bulk Print SPK) ⭐ Prioritas Rendah

**Masalah saat ini:** SPK dicetak satu per satu per pelanggan.

**Solusi:** Batch print SPK untuk seluruh klaster atau blok tertentu:

```
Input: Klaster + Blok + Periode
      │
      ▼
Generate PDF multi-halaman (1 halaman = 1 pelanggan)
Urut per blok → nokav untuk memudahkan distribusi fisik
      │
      ▼
Output: 1 file PDF berisi semua SPK sesuai filter
```

---

### 11. Blacklist / Watchlist Pelanggan ⭐ Prioritas Rendah

**Solusi:** Tandai pelanggan dengan tunggakan kritis:

```
Auto-flag:
- Tunggakan > 3 bulan: status KUNING
- Tunggakan > 6 bulan: status MERAH
- Tampil di dashboard sebagai "Perlu Perhatian"
- Notifikasi otomatis ke manajemen
```

---

### 12. Integrasi E-Faktur / Laporan Pajak ⭐ Prioritas Rendah (Jangka Panjang)

Ekspor data transaksi dalam format yang kompatibel dengan aplikasi e-Faktur DJP untuk pelaporan PPN jika diperlukan secara regulasi.

---

### Roadmap Pengembangan yang Disarankan

| Fase | Fitur | Estimasi |
|------|-------|---------|
| **Fase 1** (Segera) | Ekspor Excel, User Management, Audit Trail | 2-4 minggu |
| **Fase 2** (Jangka Pendek) | Notifikasi WhatsApp, Dashboard Analytics | 1-2 bulan |
| **Fase 3** (Jangka Menengah) | Payment Gateway/VA, Portal Pelanggan | 2-4 bulan |
| **Fase 4** (Jangka Panjang) | Mobile App, Integrasi E-Faktur | 4-6 bulan |

---

## Catatan Pengembangan

- File `Dev-Log.txt` berisi log perubahan dan matriks role fitur per versi
- Tabel `pelanggan_bck` dan `pelanggan_bck2` adalah backup manual data pelanggan
- Folder `database baru/` berisi dump database terbaru untuk keperluan restore
- Sistem menggunakan FPDF untuk generasi PDF — kompatibel PHP 5.6+
- Password disimpan dalam MD5 — disarankan upgrade ke `password_hash()` (bcrypt) untuk keamanan lebih baik
- Tidak ada CSRF token protection — disarankan aktifkan `csrf_protection` di `config.php`

---

*Sistem ini dikembangkan untuk keperluan internal manajemen perumahan Grand Duta.*
