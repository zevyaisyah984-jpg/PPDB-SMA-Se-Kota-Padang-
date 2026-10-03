<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Pendaftaran - <?php echo e($siswa['nama']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Times New Roman', serif; color: #000; }
        .kop-surat { border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-logo { width: 80px; }
        .kop-text { text-align: center; }
        .kop-text h4 { margin: 0; font-weight: bold; text-transform: uppercase; }
        .kop-text p { margin: 0; font-size: 14px; }
        .document-title { text-align: center; text-transform: uppercase; text-decoration: underline; margin-bottom: 30px; }
        table { width: 100%; margin-bottom: 1rem; }
        td { padding: 5px; vertical-align: top; }
        .label { width: 180px; font-weight: bold; }
        .colon { width: 10px; }
        .qr-placeholder { width: 100px; height: 100px; border: 1px solid #000; display: flex; align-items: center; justify-content: center; font-size: 10px; text-align: center; }
        
        @media print {
            @page { size: A4; margin: 2cm; }
            .no-print { display: none !important; }
            body { background: #fff; }
        }
    </style>
</head>
<body>

    <!-- Print Control -->
    <div class="fixed-top p-3 no-print bg-light border-bottom d-flex justify-content-between align-items-center">
        <div>
            <strong>Pratinjau Cetak</strong>
            <small class="d-block text-muted">Gunakan kertas A4 untuk hasil terbaik.</small>
        </div>
        <div>
            <button onclick="window.print()" class="btn btn-primary btn-sm"><i class="bi bi-printer"></i> Cetak Dokumen</button>
            <a href="<?php echo url('/dashboard'); ?>" class="btn btn-outline-secondary btn-sm">Kembali</a>
        </div>
    </div>

    <!-- Document Content -->
    <div class="container bg-white p-5 mt-5">
        
        <!-- KOP SURAT -->
        <div class="row kop-surat align-items-center">
            <div class="col-2 text-center">
                <img src="<?php echo asset('images/logo-sumbar.png'); ?>" alt="Logo" class="kop-logo" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/1/1b/Coat_of_arms_of_West_Sumatra.svg/1200px-Coat_of_arms_of_West_Sumatra.svg.png'">
            </div>
            <div class="col-10 kop-text">
                <h4>PEMERINTAH PROVINSI SUMATERA BARAT</h4>
                <h4>DINAS PENDIDIKAN</h4>
                <p>Jl. Jenderal Sudirman No. 51, Padang Pasir, Padang Barat, Kota Padang, Sumatera Barat 25112</p>
                <p>Website: disdik.sumbarprov.go.id | Email: dinas@disdik.sumbarprov.go.id</p>
            </div>
        </div>

        <h5 class="document-title fw-bold">TANDA BUKTI PENDAFTARAN PPDB ONLINE 2025</h5>

        <p>Berdasarkan data yang masuk ke sistem PPDB Online Provinsi Sumatera Barat Tahun Ajaran 2025/2026, dengan ini menerangkan bahwa:</p>

        <table>
            <tr>
                <td class="label">Nomor Pendaftaran</td>
                <td class="colon">:</td>
                <td class="fw-bold fs-5"><?php echo 'REG-' . date('Y') . '-' . str_pad($pendaftaran['id'], 6, '0', STR_PAD_LEFT); ?></td>
            </tr>
            <tr>
                <td class="label">Tanggal Daftar</td>
                <td class="colon">:</td>
                <td><?php echo date('d F Y H:i', strtotime($pendaftaran['tanggal_daftar'])); ?> WIB</td>
            </tr>
        </table>

        <div class="border-top border-bottom py-3 my-3">
            <h6 class="fw-bold mb-3">DATA CALON PESERTA DIDIK</h6>
            <table>
                <tr>
                    <td class="label">Nama Lengkap</td>
                    <td class="colon">:</td>
                    <td><?php echo e($siswa['nama']); ?></td>
                </tr>
                <tr>
                    <td class="label">NISN</td>
                    <td class="colon">:</td>
                    <td><?php echo e($siswa['nisn']); ?></td>
                </tr>
                <tr>
                    <td class="label">Asal Sekolah</td>
                    <td class="colon">:</td>
                    <td><?php echo e($siswa['sekolah_asal']); ?></td>
                </tr>
                <tr>
                    <td class="label">Jalur Pendaftaran</td>
                    <td class="colon">:</td>
                    <td class="text-uppercase"><?php echo e($pendaftaran['jalur']); ?></td>
                </tr>
            </table>
        </div>

        <div class="mb-4">
            <h6 class="fw-bold mb-3">PILIHAN SEKOLAH TAJUAN</h6>
            <table class="table table-bordered table-sm w-100">
                <thead class="table-light">
                    <tr>
                        <th>Nama Sekolah</th>
                        <th>Jarak Rumah - Sekolah</th>
                        <th>Status Verifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo e($sekolah['nama']); ?></td>
                        <td><?php echo number_format($pendaftaran['jarak_km'] ?? ($pendaftaran['jarak_meter']/1000), 2); ?> KM</td>
                        <td class="text-uppercase fw-bold"><?php echo e($pendaftaran['status'] == 'pending' ? 'DALAM PROSES' : $pendaftaran['status']); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer / Signature -->
        <div class="row mt-5 pt-3">
            <div class="col-4 text-center">
                <p>QR Code Validasi</p>
                <div class="qr-placeholder mx-auto">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php echo urlencode('VALID-REG:' . $pendaftaran['id'] . '-' . $siswa['nisn']); ?>" alt="QR Code" style="width: 100%; height: 100%;">
                </div>
                <small class="text-muted d-block mt-2">Scan untuk cek keaslian</small>
            </div>
            <div class="col-4"></div>
            <div class="col-4 text-center">
                <p>Padang, <?php echo date('d F Y'); ?></p>
                <p>Siswa Pendaftar,</p>
                <div style="height: 60px;"></div>
                <p class="fw-bold text-decoration-underline"><?php echo e($siswa['nama']); ?></p>
                <p>NISN. <?php echo e($siswa['nisn']); ?></p>
            </div>
        </div>

        <div class="mt-5 small text-muted fst-italic border-top pt-2">
            * Dokumen ini digenerate secara otomatis oleh Sistem PPDB Online Sumbar. <br>
            * Simpan dokumen ini sebagai bukti pendaftaran yang sah. <br>
            * Segala bentuk pemalsuan data akan dikenakan sanksi sesuai aturan yang berlaku.
        </div>
    </div>

</body>
</html>
