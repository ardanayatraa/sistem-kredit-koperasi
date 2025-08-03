# Dokumentasi Sistem User-Anggota Linking
## Sistem Kredit Koperasi - CodeIgniter 4

### Deskripsi Umum
Sistem User-Anggota Linking adalah fitur yang memastikan setiap user dengan level "Anggota" memiliki dan wajib melengkapi data anggota yang terkait. Sistem ini mengimplementasikan hubungan 1:1 antara User dan Data Anggota melalui field `id_anggota_ref` di tabel users.

---

## Arsitektur Sistem

### 1. Database Schema

#### Tabel Users (tbl_users)
```sql
- id_user (Primary Key)
- nama_lengkap
- username  
- email
- password
- level (Bendahara, Ketua Koperasi, Anggota, Penilai)
- no_hp
- status (Aktif/Nonaktif)
- id_anggota_ref (Foreign Key ke tbl_anggota) -- Field baru untuk linking
- created_at
- updated_at
```

#### Tabel Anggota (tbl_anggota)  
```sql
- id_anggota (Primary Key)
- nik
- tempat_lahir
- tanggal_lahir
- alamat
- pekerjaan
- tanggal_pendaftaran
- status_keanggotaan
- dokumen_ktp
- dokumen_kk
- dokumen_slip_gaji
- created_at
- updated_at
```

### 2. Relasi Database
- **User → Anggota**: 1:1 (Optional) melalui `users.id_anggota_ref`
- Hanya user dengan level "Anggota" yang wajib memiliki data anggota
- User level lain (Bendahara, Ketua Koperasi, Penilai) tidak memerlukan data anggota

---

## Komponen Implementasi

### 1. Middleware - AnggotaDataFilter

**File**: [`app/Filters/AnggotaDataFilter.php`](app/Filters/AnggotaDataFilter.php)

```php
class AnggotaDataFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $userLevel = session()->get('level');
        $userIdAnggotaRef = session()->get('id_anggota_ref');
        
        // Hanya cek untuk user dengan level 'Anggota'
        if ($userLevel === 'Anggota' && empty($userIdAnggotaRef)) {
            return redirect()->to('/profile/complete-anggota-data');
        }
    }
}
```

**Fungsi**: 
- Memastikan user dengan level "Anggota" sudah memiliki data anggota yang terkait
- Redirect ke halaman completion jika belum lengkap
- Filter ini diterapkan secara global kecuali untuk route completion dan auth

### 2. Controller Methods - UserController

**File**: [`app/Controllers/UserController.php`](app/Controllers/UserController.php)

#### Method: `completeAnggotaData()`
- Menampilkan form untuk melengkapi data anggota
- Hanya bisa diakses oleh user level "Anggota"
- Validasi level user sebelum menampilkan form

#### Method: `saveAnggotaData()`
- Memproses penyimpanan data anggota baru
- Upload dan validasi dokumen (KTP, KK, Slip Gaji)
- Membuat record anggota baru
- Update user dengan `id_anggota_ref`
- Redirect ke dashboard setelah berhasil

#### Method: `updateProfileAnggota()`
- Update data user dan anggota secara bersamaan
- Khusus untuk user dengan level "Anggota" 
- Handle update dokumen (optional)
- Validasi unique constraint untuk NIK

#### Method: `profile()` (Updated)
- Load data anggota jika user memiliki `id_anggota_ref`
- Pass data anggota ke view untuk ditampilkan

### 3. Routes Configuration

**File**: [`app/Config/Routes.php`](app/Config/Routes.php)

```php
// Rute untuk Profile
$routes->group('profile', ['filter' => 'role:view_profile'], function($routes) {
    $routes->get('/', 'UserController::profile');
    $routes->post('update', 'UserController::updateProfile');
    $routes->get('complete-anggota-data', 'UserController::completeAnggotaData');
    $routes->post('save-anggota-data', 'UserController::saveAnggotaData');
    $routes->post('update-anggota', 'UserController::updateProfileAnggota');
});
```

### 4. Filter Registration

**File**: [`app/Config/Filters.php`](app/Config/Filters.php)

```php
public $aliases = [
    // ... existing filters
    'anggota_data' => AnggotaDataFilter::class,
];

public $globals = [
    'before' => [
        // ... existing filters
        'anggota_data' => ['except' => [
            'login', 'register', 'logout', 'attemptLogin', 'attemptRegister',
            'profile/complete-anggota-data', 'profile/save-anggota-data'
        ]],
    ],
];
```

---

## View Templates

### 1. Complete Anggota Data Form

**File**: [`app/Views/profile/complete_anggota_data.php`](app/Views/profile/complete_anggota_data.php)

**Fitur**:
- Form input lengkap untuk data anggota
- Upload dokumen (KTP, KK, Slip Gaji)
- Validasi client-side dan server-side
- UI yang user-friendly dengan Tailwind CSS
- Informasi panduan pengisian

### 2. Enhanced Profile View  

**File**: [`app/Views/profile/index.php`](app/Views/profile/index.php)

**Fitur**:
- Conditional rendering berdasarkan level user
- Warning jika data anggota belum lengkap
- Form terpadu untuk user + anggota data
- Update dokumen dengan preview file existing
- Handling untuk user level "Anggota" vs level lainnya

---

## Database Seeders

### 1. MainSeeder Enhancement

**File**: [`app/Database/Seeds/MainSeeder.php`](app/Database/Seeds/MainSeeder.php)

**Fitur**:
- User-anggota linking otomatis saat seeding
- Update `id_anggota_ref` untuk user dengan level "Anggota"
- Data demo yang konsisten dan terintegrasi

### 2. AdminSeeder Updates

**File**: [`app/Database/Seeds/AdminSeeder.php`](app/Database/Seeds/AdminSeeder.php)

**Fitur**:
- Include field `id_anggota_ref` dalam user creation
- Support untuk linking dengan data anggota existing

---

## Flow Sistem

### 1. User Registration/Login Flow
1. User register/login dengan level "Anggota"
2. Sistem cek `id_anggota_ref` di session
3. Jika kosong → Redirect ke `/profile/complete-anggota-data`
4. Jika sudah ada → Akses normal ke dashboard

### 2. Data Completion Flow
1. User mengisi form data anggota lengkap
2. Upload dokumen pendukung (KTP, KK, Slip Gaji)
3. Sistem validasi data dan file
4. Create record anggota baru
5. Update user dengan `id_anggota_ref`
6. Update session dengan data anggota
7. Redirect ke dashboard

### 3. Profile Management Flow
1. User level "Anggota" akses profile
2. Sistem load data user + anggota
3. Form terpadu untuk edit user dan anggota
4. Update kedua tabel secara atomic
5. Handle file update (optional)

---

## Validasi dan Keamanan

### 1. Middleware Validation
- Cek level user sebelum enforce data completion
- Exclude route auth dan completion dari filter
- Session-based validation

### 2. Form Validation  
- NIK unique constraint
- File upload validation (size, type)
- Required field validation
- Email unique constraint

### 3. Authorization
- Level-based access control
- Method-level permission check
- Route protection dengan filter

---

## Konfigurasi File Upload

### 1. Upload Directory
```php
$uploadPath = WRITEPATH . 'uploads/anggota';
```

### 2. File Validation Rules
```php
'dokumen_ktp' => 'uploaded[dokumen_ktp]|max_size[dokumen_ktp,2048]|ext_in[dokumen_ktp,pdf,jpg,jpeg,png]'
'dokumen_kk' => 'uploaded[dokumen_kk]|max_size[dokumen_kk,2048]|ext_in[dokumen_kk,pdf,jpg,jpeg,png]'  
'dokumen_slip_gaji' => 'uploaded[dokumen_slip_gaji]|max_size[dokumen_slip_gaji,2048]|ext_in[dokumen_slip_gaji,pdf,jpg,jpeg,png]'
```

### 3. File Handling
- Random filename generation
- Old file cleanup pada update
- Directory auto-creation

---

## Testing & Verification

### 1. Test Data
- User: `anggota_demo` / `anggota123`
- Sudah terhubung dengan data anggota ID 1
- Data anggota lengkap dengan dokumen

### 2. Test Scenarios
✅ **User dengan data anggota lengkap**: Langsung akses dashboard  
✅ **User tanpa data anggota**: Redirect ke completion form  
✅ **Profile management**: Edit data user + anggota bersamaan  
✅ **File upload**: Upload dan update dokumen  
✅ **Validation**: Form validation dan error handling  

### 3. Browser Testing Result
- Login berhasil dengan user `anggota_demo`  
- Langsung masuk dashboard (tidak redirect completion)
- Menunjukkan middleware bekerja dengan benar
- User sudah memiliki `id_anggota_ref` yang valid

---

## Troubleshooting

### 1. Common Issues

**Issue**: User selalu redirect ke completion meskipun sudah ada data
**Solution**: Cek session `id_anggota_ref`, pastikan terupdate saat login

**Issue**: File upload gagal
**Solution**: Pastikan directory `writable/uploads/anggota` ada dan writable

**Issue**: Validation error saat update profile
**Solution**: Cek unique constraint dengan exclude current ID

### 2. Debug Commands
```bash
# Cek data user-anggota linking
php spark migrate:status
php spark db:seed MainSeeder

# Debug session data
var_dump(session()->get());
```

---

## Kesimpulan

Sistem User-Anggota Linking berhasil diimplementasikan dengan:

✅ **Middleware enforcement** untuk data completion mandatory  
✅ **Integrated profile management** untuk user + anggota data  
✅ **File upload system** untuk dokumen pendukung  
✅ **Comprehensive validation** dan error handling  
✅ **Database seeding** dengan linking otomatis  
✅ **Testing verification** melalui browser dan seeder  

Sistem ini memastikan setiap user dengan level "Anggota" memiliki data anggota yang lengkap dan terkait dengan baik dalam database, memberikan integritas data yang diperlukan untuk sistem kredit koperasi.