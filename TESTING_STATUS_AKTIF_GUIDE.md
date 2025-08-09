# TESTING GUIDE: Status Aktif/Non Aktif Functionality

## Standardisasi Status Fields yang Telah Dilakukan

### 1. Database Schema Standardization ✅
- **tbl_anggota.status_keanggotaan**: ENUM('Menunggu', 'Aktif', 'Tidak Aktif', 'Ditolak')
- **tbl_kredit.status_aktif**: ENUM('Aktif', 'Tidak Aktif')
- **tbl_pencairan.status_aktif**: ENUM('Aktif', 'Tidak Aktif') 
- **tbl_pembayaran_angsuran.status_aktif**: ENUM('Aktif', 'Tidak Aktif')
- **tbl_bunga.status_aktif**: ENUM('Aktif', 'Tidak Aktif')
- **tbl_users.status**: ENUM('Aktif', 'Tidak Aktif') - Diubah dari English ke Indonesian

### 2. Controllers yang Sudah Diperbaiki ✅
- **AnggotaController**: Toggle status keanggotaan (Aktif/Tidak Aktif)
- **KreditController**: Toggle status aktif kredit 
- **PencairanController**: Toggle status aktif pencairan
- **PembayaranAngsuranController**: Toggle status aktif pembayaran
- **UserController**: Menggunakan status Indonesian consistency

### 3. Views yang Sudah Diperbaiki ✅
- **app/Views/user/index.php**: Status dalam bahasa Indonesia
- **app/Views/anggota/index.php**: Toggle status keanggotaan
- **app/Views/kredit/index.php**: Toggle status aktif kredit
- **app/Views/pencairan/index.php**: Toggle status aktif pencairan

## Testing Scenarios

### Test 1: Toggle Status Anggota
1. Login sebagai admin/bendahara
2. Navigate ke /anggota
3. Klik toggle switch untuk mengubah status anggota
4. Verify status berubah dari "Aktif" ke "Tidak Aktif" atau sebaliknya
5. Verify anggota "Tidak Aktif" tidak muncul di dropdown pengajuan kredit

### Test 2: Toggle Status Kredit
1. Login sebagai bendahara
2. Navigate ke /kredit
3. Klik toggle switch untuk mengubah status kredit
4. Verify status berubah dengan benar
5. Verify kredit "Tidak Aktif" tidak muncul di dashboard summary

### Test 3: Toggle Status Pencairan
1. Login sebagai bendahara
2. Navigate ke /pencairan
3. Klik toggle switch untuk mengubah status pencairan
4. Verify status berubah dengan benar

### Test 4: Toggle Status User
1. Login sebagai admin
2. Navigate ke /user
3. Verify status user ditampilkan dalam bahasa Indonesia
4. Check user "Aktif" count di statistics card

### Test 5: Eligibility Check
1. Set anggota status ke "Tidak Aktif"
2. Try to create kredit application for this member
3. Should be blocked with error message
4. Set back to "Aktif" and retry - should succeed

## Expected Results

### ✅ Status Consistency
- All status fields use Indonesian language: "Aktif" / "Tidak Aktif"
- ENUM values are consistent across all tables
- Toggle functionality works in all interfaces

### ✅ Business Logic
- Only "Aktif" members can apply for credit
- Only "Aktif" interest rates are used in calculations
- Inactive records don't appear in active reports/summaries

### ✅ UI/UX
- Toggle switches provide immediate visual feedback
- Status badges use consistent colors (green for Aktif, gray for Tidak Aktif)
- Loading states and error handling work properly

## JavaScript Toggle Function Template
```javascript
function toggleStatus(id, element, entityType) {
    const formData = new FormData();
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
    
    fetch(`/${entityType}/toggle-status/${id}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update UI
            element.checked = !element.checked;
            // Update status text if needed
        } else {
            console.error('Toggle failed:', data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}
```

## Migration Applied ✅
- 2025-08-09-134200_StandardizeStatusFields.php executed successfully
- All existing data updated to use Indonesian status values
- Database schema now enforces ENUM consistency

## Final Verification Commands
```bash
# Check database structure
php spark db:table tbl_anggota
php spark db:table tbl_kredit
php spark db:table tbl_users

# Test routes
curl -X POST http://localhost:8080/anggota/toggle-status/1
curl -X POST http://localhost:8080/kredit/toggle-status/1
```

Status aktif/non aktif functionality is now fully standardized and functional across the entire system! 🎉