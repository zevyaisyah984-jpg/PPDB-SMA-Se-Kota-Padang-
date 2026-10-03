<?php
// Web Routes

// PUBLIC ROUTES
$router->get('/', 'HomeController@index');
$router->get('/kuota', 'HomeController@kuota');
$router->get('/kuota/{id}', 'HomeController@detail');
$router->get('/jadwal', 'HomeController@jadwal');
$router->get('/persyaratan', 'HomeController@persyaratan');
$router->get('/monitoring', 'HomeController@monitoring');
$router->get('/monitoring/hasil-seleksi/{id}', 'HomeController@monitoringDetail'); // New Route
$router->get('/pengumuman', 'HomeController@pengumuman');

// API ROUTES
$router->get('/api/check-updates', 'HomeController@checkUpdates');
$router->post('/api/check-status', 'HomeController@checkStatus'); // New Quick Check API

// AUTH ROUTES
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');
$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');

// SISWA ROUTES (requires login)
$router->get('/dashboard', 'SiswaController@dashboard');
$router->get('/daftar/zonasi', 'SiswaController@formZonasi');
$router->post('/daftar/zonasi', 'SiswaController@submitZonasi');
$router->get('/daftar/afirmasi', 'SiswaController@formAfirmasi');
$router->post('/daftar/afirmasi', 'SiswaController@submitAfirmasi');
$router->get('/daftar/prestasi', 'SiswaController@formPrestasi');
$router->post('/daftar/prestasi', 'SiswaController@submitPrestasi');
$router->get('/daftar/mutasi', 'SiswaController@formMutasi');
$router->post('/daftar/mutasi', 'SiswaController@submitMutasi');
$router->get('/siswa/cetak-pendaftaran', 'SiswaController@cetakPendaftaran');
$router->get('/siswa/cetak-bukti', 'SiswaController@cetakBukti');
$router->get('/siswa/cetak-bukti-ditolak', 'SiswaController@cetakBuktiDitolak');
$router->get('/siswa/cetak-daftar-ulang', 'SiswaController@cetakDaftarUlang');
$router->post('/siswa/mark-announcement-seen', 'SiswaController@markAnnouncementSeen');
$router->get('/siswa/profil', 'SiswaController@profil');
$router->get('/siswa/dokumen', 'SiswaController@dokumen');
$router->post('/siswa/profil/update', 'SiswaController@updateProfil');
$router->post('/siswa/foto/upload', 'SiswaController@uploadFoto');
$router->post('/siswa/password/update', 'SiswaController@updatePassword');
$router->get('/logout/siswa', 'SiswaController@logout');



// ADMIN ROUTES
$router->get('/admin', 'AdminController@dashboard');
$router->get('/admin/login', 'AdminController@showLogin');
$router->get('/admin/sekolah/login', 'AdminController@showLogin'); // Alias for School Admin
$router->post('/admin/sekolah/login', 'AdminController@login'); // Alias for School Admin POST
$router->post('/admin/login', 'AdminController@login');
$router->get('/admin/logout', 'AdminController@logout');

// Sekolah Management
$router->get('/admin/sekolah', 'AdminController@sekolah');
$router->get('/admin/sekolah/tambah', 'AdminController@tambahSekolah');
$router->post('/admin/sekolah/tambah', 'AdminController@storeSekolah');
$router->get('/admin/sekolah/edit/{id}', 'AdminController@editSekolah');
$router->post('/admin/sekolah/update/{id}', 'AdminController@updateSekolah');
$router->get('/admin/sekolah/toggle/{id}', 'AdminController@toggleSekolahStatus');
$router->get('/admin/sekolah/hapus/{id}', 'AdminController@hapusSekolah');
$router->get('/admin/sekolah/auto-sync/{id}', 'AdminController@autoSync'); // Auto Data Enrichment Route


// Pendaftaran Management
$router->get('/admin/pendaftar', 'AdminController@pendaftar');
$router->get('/admin/verifikasi', 'AdminController@verifikasi');
$router->post('/admin/pendaftar/set-verifikasi-fisik/{id}', 'AdminController@setVerifikasiFisik');
$router->get('/admin/verifikasi-fisik/{id}', 'AdminController@detailPendaftar'); // Alias Route fix for User
$router->get('/admin/pendaftar/{id}', 'AdminController@detailPendaftar');
$router->post('/admin/pendaftar/{id}/verify', 'AdminController@verifyPendaftar'); // Unified verify endpoint
$router->post('/admin/pendaftar/{id}/accept', 'AdminController@acceptPendaftar'); // Quick accept
$router->post('/admin/pendaftar/{id}/reject', 'AdminController@rejectPendaftar'); // Reject with reason


// School Admin Specific Features
$router->get('/admin/profil-sekolah', 'AdminController@profilSekolah');
$router->get('/admin/profil-sekolah/edit', 'AdminController@editProfilSekolah');
$router->post('/admin/profil-sekolah/update', 'AdminController@updateProfilSekolah');
$router->get('/admin/statistik', 'AdminController@statistikSekolah');
$router->get('/admin/cetak-bukti', 'AdminController@cetakBukti');
$router->get('/admin/cetak-bukti/{id}', 'AdminController@cetakBuktiDetail');
$router->get('/admin/api/stats', 'AdminController@getRealtimeStats'); // New Realtime Stats API

// Quota Management (Super Admin only)
$router->get('/admin/kuota', 'AdminController@kuota');
$router->post('/admin/kuota/update', 'AdminController@updateKuota');

// Jadwal Management (Super Admin only)
$router->get('/admin/jadwal', 'AdminController@jadwal');
$router->post('/admin/jadwal/update-serentak', 'AdminController@updateJadwalSerentak');
$router->post('/admin/jadwal/update-status', 'AdminController@updateStatusJadwal');
$router->post('/admin/jadwal/update-jalur', 'AdminController@updateJadwalPerJalur');

// Publikasi Pengumuman (Super Admin only)
$router->get('/admin/seleksi/publikasi', 'AdminController@publikasi');
$router->post('/admin/seleksi/publish', 'AdminController@publishPengumuman');

// Seleksi Management (Super Admin only)
$router->get('/admin/seleksi/proses', 'AdminController@seleksiProses');
$router->post('/admin/seleksi/eksekusi', 'AdminController@seleksiEksekusi');
$router->get('/admin/seleksi/hasil', 'AdminController@seleksiHasil');
$router->get('/admin/seleksi/reset', 'AdminController@seleksiResetPage');
$router->post('/admin/seleksi/reset', 'AdminController@seleksiReset');
$router->get('/admin/seleksi/promote', 'AdminController@promoteCadangan');
$router->get('/admin/seleksi/cetak', 'AdminController@seleksiCetak');
$router->get('/admin/seleksi/export', 'AdminController@seleksiExport');
$router->post('/admin/seleksi/update-status', 'AdminController@updateSeleksiStatus');


// User Management (Super Admin only)
$router->get('/admin/users', 'AdminController@users');
$router->get('/admin/users/tambah', 'AdminController@tambahUser');
$router->post('/admin/users/tambah', 'AdminController@storeUser');
$router->post('/admin/users/check-username', 'AdminController@checkUsername');
$router->get('/admin/users/hapus/{id}', 'AdminController@hapusUser');


// Global Settings & Logs (Super Admin only)
$router->get('/admin/settings', 'AdminController@settings');
$router->post('/admin/settings/update', 'AdminController@updateSettings');
$router->get('/admin/logs', 'AdminController@auditLogs');



// 404 Not Found
$router->notFound(function() {
    http_response_code(404);
    view('errors.404');
});
