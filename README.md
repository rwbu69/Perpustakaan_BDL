# Sistem Manajemen Perpustakaan - Light Novel & Manga

Sistem manajemen perpustakaan yang berfokus pada koleksi Light Novel dan Manga Jepang dengan fitur peminjaman, manajemen pengguna, dan sistem denda otomatis.

## 📋 Daftar Isi

- [Deskripsi Proyek](#deskripsi-proyek)
- [Entity Relationship Diagram (ERD)](#entity-relationship-diagram-erd)
- [Flowchart Sistem](#flowchart-sistem)
- [Software Development Life Cycle (SDLC)](#software-development-life-cycle-sdlc)
- [Fitur Utama](#fitur-utama)
- [Teknologi yang Digunakan](#teknologi-yang-digunakan)
- [Panduan Instalasi Windows (XAMPP)](#panduan-instalasi-windows-xampp)
- [Penggunaan](#penggunaan)
- [Struktur Database](#struktur-database)
- [Kontribusi](#kontribusi)

## 🎯 Deskripsi Proyek

Sistem Manajemen Perpustakaan ini dikembangkan khusus untuk mengelola koleksi Light Novel dan Manga Jepang. Sistem ini memungkinkan administrator untuk mengelola buku, pengguna, dan peminjaman, sementara pengguna reguler dapat mencari, meminjam, dan mengembalikan buku dengan mudah.

### Tujuan Proyek:
- Digitalisasi sistem perpustakaan tradisional
- Otomatisasi proses peminjaman dan pengembalian buku
- Sistem denda otomatis untuk keterlambatan
- Interface yang mudah digunakan dalam Bahasa Indonesia
- Fokus khusus pada literatur Jepang (Light Novel & Manga)

## 📊 Entity Relationship Diagram (ERD)

```
┌─────────────────┐       ┌─────────────────┐       ┌─────────────────┐
│      USERS      │       │   PEMINJAMAN    │       │      BUKU       │
├─────────────────┤       ├─────────────────┤       ├─────────────────┤
│ id (PK)         │◄──────┤ user_id (FK)    │──────►│ id (PK)         │
│ name            │       │ buku_id (FK)    │       │ title           │
│ username        │       │ tanggal_pinjam  │       │ author          │
│ email           │       │ tanggal_kembali │       │ isbn            │
│ password        │       │ tanggal_aktual  │       │ type            │
│ role            │       │ status          │       │ description     │
│ phone           │       │ denda           │       │ publisher       │
│ address         │       │ created_at      │       │ publication_date│
│ created_at      │       │ updated_at      │       │ total_copies    │
│ updated_at      │       └─────────────────┘       │ available_copies│
└─────────────────┘                                 │ price           │
                                                    │ cover_image     │
                                                    │ is_active       │
                                                    │ created_at      │
                                                    │ updated_at      │
                                                    └─────────────────┘
```

### Relasi Antar Tabel:

1. **Users - Peminjaman**: One-to-Many
   - Satu pengguna dapat memiliki banyak peminjaman
   - Setiap peminjaman hanya milik satu pengguna

2. **Buku - Peminjaman**: One-to-Many
   - Satu buku dapat dipinjam berkali-kali (berbeda waktu)
   - Setiap peminjaman hanya untuk satu buku

## 🔄 Flowchart Sistem

### Alur Autentikasi
```
┌─────────────┐
│    Start    │
└─────┬───────┘
      │
      v
┌─────────────┐    Tidak   ┌─────────────┐
│  Akses URL  ├───────────►│   Login     │
└─────┬───────┘            └─────┬───────┘
      │                          │
      │ Sudah Login              │
      v                          v
┌─────────────┐                ┌─────────────┐
│  Dashboard  │                │  Validasi   │
└─────┬───────┘                └─────┬───────┘
      │                              │
      │                              │ Valid
      v                              v
┌─────────────┐                ┌─────────────┐
│ Cek Role    │                │  Dashboard  │
└─────┬───────┘                └─────────────┘
      │
      ├─ Admin ──► Admin Dashboard
      │
      └─ User ───► User Dashboard
```

### Alur Peminjaman Buku
```
┌─────────────┐
│  User Login │
└─────┬───────┘
      │
      v
┌─────────────┐
│ Browse Buku │
└─────┬───────┘
      │
      v
┌─────────────┐    Tidak   ┌─────────────┐
│ Pilih Buku  ├───────────►│ Buku Tidak  │
└─────┬───────┘ Tersedia   │ Tersedia    │
      │                    └─────────────┘
      │ Tersedia
      v
┌─────────────┐
│ Cek Status  │
│ Pengguna    │
└─────┬───────┘
      │
      │ Belum Pinjam Buku Ini
      v
┌─────────────┐
│ Proses      │
│ Peminjaman  │
└─────┬───────┘
      │
      v
┌─────────────┐
│ Update      │
│ Database    │
└─────┬───────┘
      │
      v
┌─────────────┐
│ Konfirmasi  │
│ Berhasil    │
└─────────────┘
```

### Alur Pengembalian Buku
```
┌─────────────┐
│ User Login  │
└─────┬───────┘
      │
      v
┌─────────────┐
│ My Borrowing│
└─────┬───────┘
      │
      v
┌─────────────┐
│ Pilih Buku  │
│ Dikembalikan│
└─────┬───────┘
      │
      v
┌─────────────┐
│ Hitung Denda│
│ (Jika Ada)  │
└─────┬───────┘
      │
      v
┌─────────────┐
│ Update      │
│ Status      │
└─────┬───────┘
      │
      v
┌─────────────┐
│ Tambah      │
│ Available   │
│ Copies      │
└─────┬───────┘
      │
      v
┌─────────────┐
│ Konfirmasi  │
│ Selesai     │
└─────────────┘
```

## 🔄 Software Development Life Cycle (SDLC)

Proyek ini dikembangkan menggunakan metodologi **Waterfall** dengan tahapan sebagai berikut:

### 1. 📋 Planning (Perencanaan)
**Durasi: 1 Minggu**
- **Analisis Kebutuhan**: Identifikasi kebutuhan sistem perpustakaan untuk Light Novel & Manga
- **Studi Kelayakan**: Evaluasi teknologi Laravel, MySQL, dan resource yang diperlukan
- **Estimasi Waktu**: Perencanaan timeline pengembangan
- **Tim & Resource**: Alokasi developer dan tools yang dibutuhkan

**Deliverables:**
- Dokumen requirement specification
- Project timeline
- Resource allocation plan

### 2. 🎨 Analysis & Design (Analisis & Desain)
**Durasi: 1-2 Minggu**
- **System Architecture**: Desain arsitektur aplikasi web berbasis MVC
- **Database Design**: Pembuatan ERD dan normalisasi database
- **UI/UX Design**: Wireframe dan mockup interface dalam Bahasa Indonesia
- **API Design**: Perencanaan routing dan controller structure

**Deliverables:**
- Entity Relationship Diagram (ERD)
- Database schema
- UI/UX mockups
- System architecture diagram
- API documentation

### 3. 💻 Implementation (Implementasi)
**Durasi: 3-4 Minggu**

#### Week 1: Foundation Setup
- Setup Laravel project
- Database migration creation
- Basic authentication system
- User role management

#### Week 2: Core Features
- CRUD operations untuk buku
- Sistem peminjaman
- Dashboard admin dan user

#### Week 3: Advanced Features
- Sistem denda otomatis
- Search dan filter functionality
- File upload untuk cover buku
- Validation dan error handling

#### Week 4: Finalization
- Interface translation ke Bahasa Indonesia
- Styling dan responsive design
- Testing dan bug fixes
- Documentation

**Deliverables:**
- Working application
- Source code dengan clean coding standards
- Database dengan sample data

### 4. 🧪 Testing (Pengujian)
**Durasi: 1 Minggu**
- **Unit Testing**: Testing individual functions dan methods
- **Integration Testing**: Testing integrasi antar modules
- **User Acceptance Testing**: Testing oleh end-user
- **Performance Testing**: Testing load dan response time
- **Security Testing**: Testing authentication dan authorization

**Test Cases:**
- Login/Register functionality
- CRUD operations untuk semua entities
- Business logic (denda calculation, availability check)
- File upload functionality
- Role-based access control

**Deliverables:**
- Test reports
- Bug tracking dan resolution
- Performance metrics

### 5. 🚀 Deployment (Deployment)
**Durasi: 3-5 Hari**
- **Production Environment Setup**: Konfigurasi server production
- **Database Migration**: Deploy database ke production
- **Application Deployment**: Deploy source code
- **SSL Configuration**: Setup keamanan
- **Monitoring Setup**: Setup logging dan monitoring

**Deliverables:**
- Live application
- Deployment documentation
- User manual
- Admin guide

### 6. 🔧 Maintenance (Pemeliharaan)
**Ongoing Process**
- **Bug Fixes**: Perbaikan issues yang ditemukan
- **Feature Updates**: Penambahan fitur baru sesuai feedback
- **Security Updates**: Update keamanan framework dan dependencies
- **Performance Optimization**: Optimasi database dan aplikasi
- **Backup Management**: Backup data reguler

**Aktivitas Maintenance:**
- Weekly monitoring dan health checks
- Monthly security updates
- Quarterly feature reviews
- Yearly major updates

## ✨ Fitur Utama

### 👨‍💼 Admin Features:
- **Dashboard Komprehensif**: Statistik lengkap sistem
- **Manajemen Buku**: CRUD lengkap untuk koleksi buku
- **Manajemen Peminjaman**: Monitoring semua aktivitas peminjaman
- **Manajemen Pengguna**: Lihat semua pengguna terdaftar
- **Laporan Keterlambatan**: Tracking buku yang terlambat dikembalikan
- **Sistem Denda**: Kalkulasi otomatis denda keterlambatan

### 👤 User Features:
- **Browse Koleksi**: Pencarian dan filter buku berdasarkan jenis
- **Peminjaman Mandiri**: Pinjam buku secara langsung
- **Riwayat Peminjaman**: Lihat history peminjaman pribadi
- **Status Real-time**: Cek status peminjaman dan denda
- **Notifikasi Denda**: Informasi denda jika terlambat mengembalikan

### 🔧 System Features:
- **Autentikasi Ganda**: Login dengan username atau email
- **Role-Based Access**: Pembedaan akses admin dan user
- **Responsive Design**: Compatible untuk desktop dan mobile
- **File Upload**: Upload cover buku dengan validasi
- **Search & Filter**: Pencarian advanced dengan multiple criteria
- **Pagination**: Navigasi data yang optimal

## 🛠 Teknologi yang Digunakan

### Backend:
- **Framework**: Laravel 11
- **Language**: PHP 8.2+
- **Database**: MySQL 8.0+
- **Authentication**: Laravel Sanctum
- **File Storage**: Laravel Storage

### Frontend:
- **Template Engine**: Blade
- **Styling**: Custom CSS (Responsive)
- **JavaScript**: Vanilla JS untuk interaktivity

### Development Tools:
- **Dependency Manager**: Composer
- **Asset Building**: Vite
- **Version Control**: Git
- **Testing**: PHPUnit

### Server Requirements:
- **Web Server**: Apache/Nginx
- **PHP**: >= 8.2
- **Extensions**: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath, Fileinfo
- **Database**: MySQL 8.0+ / PostgreSQL 13+

## 🚀 Panduan Instalasi Windows (XAMPP)

### Prerequisites:
- Windows 10/11
- XAMPP (Apache, MySQL, PHP 8.2+)
- Git for Windows
- Composer

### Langkah 1: Install XAMPP
1. Download XAMPP dari [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Pilih versi dengan PHP 8.2 atau lebih tinggi
3. Install XAMPP di `C:\xampp`
4. Jalankan XAMPP Control Panel sebagai Administrator
5. Start Apache dan MySQL services

### Langkah 2: Install Composer
1. Download Composer dari [https://getcomposer.org/download/](https://getcomposer.org/download/)
2. Install Composer secara global
3. Verifikasi instalasi dengan membuka Command Prompt dan jalankan:
   ```bash
   composer --version
   ```

### Langkah 3: Install Git (Optional)
1. Download Git dari [https://git-scm.com/download/win](https://git-scm.com/download/win)
2. Install dengan konfigurasi default
3. Verifikasi dengan:
   ```bash
   git --version
   ```

### Langkah 4: Setup Database
1. Buka browser dan akses `http://localhost/phpmyadmin`
2. Login dengan:
   - Username: `root`
   - Password: (kosong/blank)
3. Buat database baru:
   ```sql
   CREATE DATABASE perpustakaan_bdl CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

### Langkah 5: Clone/Download Project
```bash
# Jika menggunakan Git
git clone https://github.com/rwbu69/Perpustakaan_BDL.git C:\xampp\htdocs\perpustakaan

# Atau download ZIP dan extract ke C:\xampp\htdocs\perpustakaan
```

### Langkah 6: Install Dependencies
1. Buka Command Prompt sebagai Administrator
2. Navigate ke folder project:
   ```bash
   cd C:\xampp\htdocs\perpustakaan
   ```
3. Install PHP dependencies:
   ```bash
   composer install
   ```

### Langkah 7: Environment Configuration
1. Copy file environment:
   ```bash
   copy .env.example .env
   ```
2. Edit file `.env` dengan notepad atau text editor:
   ```env
   APP_NAME="Sistem Manajemen Perpustakaan"
   APP_ENV=local
   APP_KEY=
   APP_DEBUG=true
   APP_URL=http://localhost/perpustakaan/public

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=perpustakaan_bdl
   DB_USERNAME=root
   DB_PASSWORD=
   ```

### Langkah 8: Generate Application Key
```bash
php artisan key:generate
```

### Langkah 9: Database Migration & Seeding
```bash
# Jalankan migrasi database
php artisan migrate

# Jalankan seeder untuk data contoh
php artisan db:seed
```

### Langkah 10: Storage Link
```bash
php artisan storage:link
```

### Langkah 11: Set Permissions (Opsional untuk Windows)
Pastikan folder `storage` dan `bootstrap/cache` memiliki write permissions.

### Langkah 12: Testing Installation
1. Buka browser dan akses: `http://localhost/perpustakaan/public`
2. Anda akan diarahkan ke halaman login
3. Gunakan akun default:
   - **Admin**:
     - Username: `admin`
     - Password: `password`
   - **User**: 
     - Username: `takeshi`
     - Password: `password`

### Troubleshooting Umum:

#### Error: "Class 'PDO' not found"
1. Buka `C:\xampp\php\php.ini`
2. Uncomment line: `extension=pdo_mysql`
3. Restart Apache

#### Error: "The only supported ciphers are AES-128-CBC and AES-256-CBC"
```bash
php artisan key:generate
```

#### Error: Database Connection
1. Pastikan MySQL service di XAMPP sudah running
2. Verifikasi kredensial database di file `.env`
3. Test koneksi database melalui phpMyAdmin

#### Error: File Permissions
1. Berikan write permission ke folder `storage` dan `bootstrap/cache`
2. Di Windows, klik kanan → Properties → Security → Edit → Full Control

#### Error: "Route not found"
Pastikan mengakses aplikasi melalui: `http://localhost/perpustakaan/public`

### Konfigurasi Apache Virtual Host (Opsional)
Untuk akses yang lebih mudah (tanpa `/public`):

1. Edit `C:\xampp\apache\conf\extra\httpd-vhosts.conf`
2. Tambahkan:
   ```apache
   <VirtualHost *:80>
       DocumentRoot "C:/xampp/htdocs/perpustakaan/public"
       ServerName perpustakaan.local
       <Directory "C:/xampp/htdocs/perpustakaan/public">
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```
3. Edit `C:\Windows\System32\drivers\etc\hosts` (sebagai Administrator)
4. Tambahkan: `127.0.0.1 perpustakaan.local`
5. Restart Apache
6. Akses via: `http://perpustakaan.local`

## 📖 Penggunaan

### Untuk Administrator:
1. **Login** dengan akun admin
2. **Dashboard** - Lihat statistik sistem secara keseluruhan
3. **Kelola Buku** - Tambah, edit, hapus buku dari koleksi
4. **Kelola Peminjaman** - Monitor dan kelola semua peminjaman
5. **Lihat Laporan** - Cek buku yang terlambat dan denda

### Untuk Pengguna:
1. **Registrasi/Login** - Buat akun atau masuk ke sistem
2. **Browse Buku** - Cari dan lihat koleksi yang tersedia
3. **Pinjam Buku** - Pinjam buku yang diinginkan
4. **Monitor Status** - Cek status peminjaman dan denda
5. **Kembalikan Buku** - Proses pengembalian buku

### Aturan Peminjaman:
- **Durasi Standard**: 7 hari
- **Denda Keterlambatan**: Rp 7.000 per hari
- **Maksimal Peminjaman**: Sesuai ketersediaan buku
- **Perpanjangan**: Tidak tersedia (harus dikembalikan dulu)

## 📊 Struktur Database

### Tabel Users
```sql
- id: Primary Key (Auto Increment)
- name: Nama lengkap pengguna
- username: Username unik untuk login
- email: Email pengguna (nullable)
- password: Password yang di-hash
- role: 'admin' atau 'user'
- phone: Nomor telepon
- address: Alamat lengkap
- created_at, updated_at: Timestamps
```

### Tabel Buku
```sql
- id: Primary Key (Auto Increment)
- title: Judul buku
- author: Penulis buku
- isbn: Nomor ISBN (nullable, unique)
- type: 'light_novel' atau 'manga'
- description: Deskripsi buku (nullable)
- publisher: Penerbit (nullable)
- publication_date: Tanggal terbit (nullable)
- total_copies: Total eksemplar
- available_copies: Eksemplar tersedia
- price: Harga buku (nullable)
- cover_image: Path gambar cover (nullable)
- is_active: Status aktif (boolean)
- created_at, updated_at: Timestamps
```

### Tabel Peminjaman
```sql
- id: Primary Key (Auto Increment)
- user_id: Foreign Key ke tabel users
- buku_id: Foreign Key ke tabel buku
- tanggal_pinjam: Tanggal peminjaman
- tanggal_kembali_rencana: Tanggal jatuh tempo
- tanggal_kembali_aktual: Tanggal pengembalian aktual (nullable)
- status: 'dipinjam', 'dikembalikan', 'terlambat'
- denda: Jumlah denda (default 0)
- created_at, updated_at: Timestamps
```
