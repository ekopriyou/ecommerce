# TokoKita — toko online dan BO admin

Aplikasi PHP + MariaDB/MySQL untuk XAMPP. Jalankan dengan Apache dan MySQL aktif; buka `http://localhost/toko-online/`.

## Fitur toko
- Katalog, kategori, pencarian, detail, sesi keranjang, checkout dan persediaan.
- Pencatatan pesanan dengan snapshot harga/nama item, transaksi database, pemeriksaan stok terkunci.
- Akun pelanggan opsional: daftar/masuk, kelola beberapa alamat, pilih alamat tersimpan saat checkout, simpan alamat baru dan lihat pesanan milik akun. Checkout tamu tetap didukung.

## BO administrator
Alamat: `http://localhost/toko-online/admin/login.php`
- Ringkasan transaksi/omzet, daftar pesanan, pencarian dan filter status.
- Detail alamat/kontak/item, ubah status dengan audit log.
- Pembatalan mengembalikan stok; mengaktifkan ulang pesanan batal memeriksa stok dalam transaksi.
- Login dengan password_hash/password_verify, session ID rotation, cookie HttpOnly/SameSite, CSRF, session timeout, pembatasan percobaan login berbasis DB.
- Audit event login/logout dan perubahan status; token reset sekali pakai hashed di DB.
- Dari detail pesanan, cetak Invoice pelanggan atau lembar produksi/packing. Dokumen ramah cetak A4 dan bisa disimpan sebagai PDF melalui dialog cetak browser; lembar packing berisi checklist picking dan kolom tanda tangan.

Database aktif `tokokita_shop`, migrasi `migrations/001_admin.sql` dan `migrations/002_customers.sql` sudah diterapkan. Ada tabel admin dan pelanggan, alamat tersimpan serta login attempts.

## Akses awal admin
Username: `admin`. Kata sandi awal acak diberikan kepada operator saat instalasi; bila hilang, reset langsung hash password melalui SQL/PHP CLI lokal dan pastikan hash dibuat oleh PHP `password_hash()`. Jangan menyimpan kata sandi di repositori atau berkas publik. Reset di instalasi lokal menampilkan token; jangan gunakan fitur lokal ini di internet sebelum diubah mengirim token via email terverifikasi.

u : admin 
p : onm_KXx6ggurW5MyGE10pUMWFi0A1!

## Keamanan / deployment
- Prepared statements dan escaping HTML; semua mutasi dilindungi CSRF.
- `.htaccess` melarang listing dan akses ekstensi SQL/log/backup/konfigurasi. Perlu `AllowOverride All` dan `mod_headers` Apache.
- Jangan jalankan di internet dengan akun DB root/password kosong, HTTP tanpa TLS, atau `display_errors` aktif. Buat akun DB khusus dengan hak minimum, pakai HTTPS, limitasi akses admin bila memungkinkan, email reset, retensi log, backup terenkripsi, serta monitoring.
- Toko publik menampilkan status dan identitas pesanan via nomor order. Untuk penggunaan sensitif/produksi, ubah halaman sukses menjadi akses berbasis secret/token pemilik atau autentikasi.
- Pembayaran dan ongkir belum terintegrasi. Jangan ubah status menjadi diproses tanpa verifikasi pembayaran manual.
- Ini bukan audit keamanan menyeluruh; perlu pengujian threat-model dan review pihak independen sebelum produksi.