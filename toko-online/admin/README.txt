BO ADMIN TOKOKITA

Alamat login: http://localhost/toko-online/admin/login.php
Username awal: admin

Kata sandi awal dibuat acak pada setup. Demi keamanan, password tidak disimpan dalam berkas proyek. Jika belum disalin dari output setup awal, gunakan phpMyAdmin/CLI lokal untuk reset terkontrol atau hubungi pembuat aplikasi untuk panduan pembuatan hash baru.

Tabel admin sudah dibuat dalam database tokokita_shop: admin_users, admin_login_attempts, admin_audit, admin_password_resets.

Sebelum dipublikasikan, ganti kredensial database root kosong dengan user DB khusus hak minimum, gunakan HTTPS, matikan display_errors, dan atur reset password agar mengirim token ke email terverifikasi.