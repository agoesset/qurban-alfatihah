# Qurban Al-Fatihah - Sistem Manajemen Qurban

Aplikasi web untuk mengelola operasional penyembelihan hewan kurban pada Hari Raya Idul Adha, mulai dari penerimaan hewan, proses penyembelihan, hingga distribusi daging kepada penerima.

## 🎯 Fitur Utama

### 1. **Manajemen Kategori Hewan**
- 15 kategori hewan (Domba, Kambing, Sapi)
- Berbagai tipe kualitas (Promo, Tipe A-E, Spesial, Premium, dll)
- Upload gambar untuk setiap kategori

### 2. **Tracking Inventory Hewan**
- Kode unik untuk setiap hewan
- Pencatatan bobot hewan
- Status proses workflow:
  - ✅ Penyembelihan
  - ✅ Pengulitan
  - ✅ Penimbangan
- Timestamp otomatis untuk setiap tahap proses
- Soft delete support

### 3. **Manajemen Distribusi**
- Daftar penerima (beneficiary) dan shohibul qurban (donatur)
- Permintaan bagian daging (Daging, Jeroan, Kepala & Kaki, Buntut)
- Status pengemasan dan distribusi
- Pencatatan alamat penerima

### 4. **Dashboard & Statistik**
- Real-time metrics untuk setiap jenis hewan
- Tracking progress workflow
- Monitoring status distribusi
- Laporan pembungkusan dan distribusi

## 💻 Tech Stack

- **Backend:** PHP 8.1+ with Laravel 10.10+
- **Admin Panel:** Filament 3.2
- **Frontend:** Tailwind CSS 3.4.4 + DaisyUI 4.12.2
- **Build Tool:** Vite 5.0
- **Database:** MySQL
- **Authentication:** Laravel Sanctum

## 📋 Requirements

- PHP >= 8.1
- Composer
- Node.js & npm
- MySQL >= 5.7 atau MariaDB >= 10.3

## 🚀 Installation

### 1. Clone Repository

```bash
git clone <repository-url>
cd qurban-alfatihah
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup

Edit `.env` file dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=qurban_alfatihah
DB_USERNAME=root
DB_PASSWORD=
```

Buat database:

```bash
mysql -u root -p
CREATE DATABASE qurban_alfatihah;
exit;
```

### 5. Run Migrations

```bash
# Run migrations
php artisan migrate

# (Optional) Seed database with sample data
php artisan db:seed
```

### 6. Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 7. Start Development Server

```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## 🗄️ Database Schema

### Tabel Utama

#### `kategoris`
- `id`: Primary key
- `nama_kategori`: Nama kategori (e.g., "Domba Tipe A", "Kambing Promo", "Sapi Jawa Premium")
- `image`: Path gambar kategori
- `created_at`, `updated_at`: Timestamps

#### `list_hewans`
- `id`: Primary key
- `kode_hewan`: Kode unik hewan (unique)
- `kategori_id`: Foreign key ke tabel kategoris
- `bobot`: Bobot hewan (decimal 5,2)
- `penyembelihan`: Boolean status penyembelihan
- `pengulitan`: Boolean status pengulitan
- `penimbangan`: Boolean status penimbangan
- `penyembelihan_updated_at`: Timestamp penyembelihan
- `pengulitan_updated_at`: Timestamp pengulitan
- `penimbangan_updated_at`: Timestamp penimbangan
- `created_at`, `updated_at`, `deleted_at`: Timestamps

#### `list_distribusis`
- `id`: Primary key
- `nama`: Nama penerima
- `shohibul_qurban`: Boolean (true = donatur, false = penerima manfaat)
- `jumlah`: Jumlah bagian yang diterima (integer)
- `request`: JSON array permintaan bagian (Daging, Jeroan, Kepala & Kaki, Buntut)
- `alamat`: Alamat penerima
- `terbungkus`: Boolean status pembungkusan
- `terdistribusi`: Boolean status distribusi
- `created_at`, `updated_at`: Timestamps

### Database Indexes

Untuk performa optimal, aplikasi menggunakan indexes pada:
- Foreign keys
- Kolom yang sering di-filter (kategori_id, status workflow)
- Kolom untuk pencarian (nama, kode_hewan)
- Composite indexes untuk queries kompleks

## 📝 Usage Guide

### Admin Panel

Admin panel dapat diakses melalui `/admin`. Fitur yang tersedia:

1. **Dashboard**: Overview statistik dan progress
2. **Kategori**: Manajemen kategori hewan
3. **List Hewan**: Input dan tracking hewan
4. **Distribusi**: Manajemen distribusi daging

### Helper Class

Class `App\Helpers\Helper` menyediakan berbagai method untuk statistik:

```php
use App\Helpers\Helper;

// Count hewan
Helper::countDomba();
Helper::countKambing();
Helper::countSapi();

// Progress penyembelihan
Helper::sembelihDomba();
Helper::lastUpdatedPenyembelihanDomba();

// Statistik distribusi
Helper::countDaging();
Helper::bungkusDaging();
Helper::distribusiShohibulQurban();

// Calculate progress
Helper::calculateProgress('countDomba', 'sembelihDomba');
```

## 🔐 Validation Rules

Aplikasi menggunakan Form Request untuk validasi:

### ListHewan Validation
- `kode_hewan`: Required, unique, format uppercase alphanumeric
- `kategori_id`: Required, must exist in kategoris table
- `bobot`: Required, numeric, range 0.01-999.99 kg

### ListDistribusi Validation
- `nama`: Required, min 3 chars, max 255 chars
- `jumlah`: Required, integer, range 1-1000
- `request`: Required array, valid options only
- `alamat`: Required, min 5 chars, max 500 chars

### Kategori Validation
- `nama_kategori`: Required, unique, must start with "Domba", "Kambing", or "Sapi"

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test --filter ExampleTest

# Run with coverage
php artisan test --coverage
```

## 📦 Recent Improvements (v2.1)

### Critical Bug Fixes
✅ **Fixed JSON query bugs** in Helper.php (countDaging, countJeroan, dll)
✅ **Fixed hard-coded kategori IDs** - now using dynamic lookup
✅ **Added namespace** to Helper.php
✅ **Replaced deprecated $dates** property with $casts

### Performance Enhancements
✅ **Added database indexes** for better query performance
✅ **Optimized Helper queries** using whereIn instead of whereBetween

### Code Quality
✅ **Added Form Request validation** classes
✅ **Type hints** for all Helper methods
✅ **Consistent return types** (Carbon objects instead of mixed)
✅ **Proper model casts** for type safety

## 🔧 Development

### Code Style

Project ini menggunakan:
- Laravel Pint untuk PHP code formatting
- ESLint untuk JavaScript (jika ada)
- Tailwind CSS untuk styling

```bash
# Format PHP code
./vendor/bin/pint

# Check code style
./vendor/bin/pint --test
```

### Running in Docker (Laravel Sail)

```bash
# Start containers
./vendor/bin/sail up -d

# Run artisan commands
./vendor/bin/sail artisan migrate

# Run npm commands
./vendor/bin/sail npm run dev
```

## 🤝 Contributing

1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License.

## 👥 Authors

- Development Team - Al-Fatihah Organization

## 📞 Support

For issues and questions, please create an issue in the repository.

---

**Note:** This is version 2.1 with critical bug fixes and performance improvements. For production deployment, ensure all migrations are run and environment is properly configured.
