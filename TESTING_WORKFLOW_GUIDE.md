# 🧪 PANDUAN TESTING WORKFLOW KREDIT KOPERASI

## ✅ **STATUS YANG SUDAH DIPERBAIKI:**

### **🔄 ALUR WORKFLOW LENGKAP:**
```
1. Anggota → Pengajuan Kredit (Status: "Diajukan")
2. Bendahara → Verifikasi Dokumen (Status: "Verifikasi Bendahara") 
3. Appraiser → Penilaian Agunan (Status: "Siap Persetujuan")
4. Ketua → Persetujuan Final (Status: "Disetujui Ketua")
5. Bendahara → Proses Pencairan (Status: "Siap Dicairkan")
6. Bendahara → Pencairan Dana (Status: "Sudah Dicairkan")
```

## 🎯 **PANDUAN TESTING PER ROLE:**

### **👨‍💼 BENDAHARA:**
**URL Dashboard:** `/kredit/pengajuan-untuk-role`
- **Tugas 1:** Status "Diajukan" → Button **"VERIFIKASI DOKUMEN"**
  - Klik button → URL: `/kredit/verifikasi-bendahara/{id}`
  - Form: Catatan + Pilih (Diterima/Ditolak)
  - Submit → Status berubah: "Verifikasi Bendahara"

- **Tugas 2:** Status "Disetujui Ketua" → Button **"PROSES PENCAIRAN"**  
  - Klik button → URL: `/kredit/proses-pencairan/{id}`
  - Form: Catatan + Pilih (Siap Dicairkan/Perlu Review)
  - Submit → Status berubah: "Siap Dicairkan"

### **🏠 APPRAISER:**
**URL Dashboard:** `/kredit/pengajuan-untuk-role`
- **Tugas:** Status "Verifikasi Bendahara" → Button **"NILAI AGUNAN"**
  - Klik button → URL: `/kredit/penilaian-appraiser/{id}`
  - Form: Nilai Taksiran + Catatan + Rekomendasi (Disetujui/Ditolak)
  - Submit → Status berubah: "Siap Persetujuan"

### **👔 KETUA:**
**URL Dashboard:** `/kredit/pengajuan-untuk-role`  
- **Tugas:** Status "Siap Persetujuan" → Button **"PERSETUJUAN FINAL"**
  - Klik button → URL: `/kredit/persetujuan-final/{id}`
  - Form: Keputusan Final + Catatan
  - Submit → Status berubah: "Disetujui Ketua"

## 📋 **CHECKLIST TESTING:**

### **STEP 1: LOGIN SEBAGAI ANGGOTA**
- [ ] Buat pengajuan kredit baru
- [ ] Status harus: "Diajukan" 
- [ ] Pastikan validasi 6 bulan keanggotaan bekerja

### **STEP 2: LOGIN SEBAGAI BENDAHARA**
- [ ] Buka `/kredit/pengajuan-untuk-role`
- [ ] Lihat pengajuan dengan status "Diajukan"
- [ ] Klik button **"VERIFIKASI DOKUMEN"**
- [ ] Isi form verifikasi → Pilih "Diterima" 
- [ ] Submit → Status berubah ke "Verifikasi Bendahara"

### **STEP 3: LOGIN SEBAGAI APPRAISER**  
- [ ] Buka `/kredit/pengajuan-untuk-role`
- [ ] Lihat pengajuan dengan status "Verifikasi Bendahara"
- [ ] Klik button **"NILAI AGUNAN"**
- [ ] Isi nilai taksiran + catatan → Pilih "Disetujui"
- [ ] Submit → Status berubah ke "Siap Persetujuan"

### **STEP 4: LOGIN SEBAGAI KETUA**
- [ ] Buka `/kredit/pengajuan-untuk-role`
- [ ] Lihat pengajuan dengan status "Siap Persetujuan" 
- [ ] Klik button **"PERSETUJUAN FINAL"**
- [ ] Isi catatan → Pilih "Disetujui"
- [ ] Submit → Status berubah ke "Disetujui Ketua"

### **STEP 5: LOGIN SEBAGAI BENDAHARA LAGI**
- [ ] Buka `/kredit/pengajuan-untuk-role`
- [ ] Lihat pengajuan dengan status "Disetujui Ketua"
- [ ] Klik button **"PROSES PENCAIRAN"**
- [ ] Isi catatan → Pilih "Siap Dicairkan"  
- [ ] Submit → Status berubah ke "Siap Dicairkan"

### **STEP 6: PROSES PENCAIRAN**
- [ ] Login bendahara → Buka `/pencairan/new`
- [ ] Pilih kredit dengan status "Siap Dicairkan"
- [ ] Lengkapi form pencairan + upload bukti
- [ ] Submit → Status berubah ke "Sudah Dicairkan"
- [ ] Angsuran otomatis terbuat

## 🚨 **TROUBLESHOOTING:**

### **Jika Button Tidak Muncul:**
1. Cek role user di session
2. Cek status kredit di database  
3. Pastikan kondisi di view sesuai

### **Jika Status Tidak Berubah:**
1. Cek controller method
2. Cek field allowedFields di Model
3. Cek migrasi sudah jalan

### **Jika Error 404:**
1. Cek routes di `app/Config/Routes.php`
2. Pastikan method controller ada
3. Cek permission di routes

## 🔧 **FITUR TAMBAHAN:**

- ✅ **Validasi 6 bulan keanggotaan** otomatis
- ✅ **Perhitungan bunga otomatis** (Flat/Menurun/Efektif)  
- ✅ **Generate angsuran** otomatis setelah pencairan
- ✅ **LTV Calculator** di form appraiser
- ✅ **Audit trail** lengkap dengan timestamp

## 📞 **SUPPORT:**
Jika ada masalah, cek log di `writable/logs/` untuk detail error.