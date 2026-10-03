<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Keterangan Diterima - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .print-container { box-shadow: none !important; margin: 0 !important; }
        }
        body { background: #f0f2f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .print-container { 
            max-width: 800px; 
            margin: 20px auto; 
            background: white; 
            padding: 40px; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        .header-logo { height: 70px; }
        .kop-surat { border-bottom: 3px solid #198754; padding-bottom: 15px; margin-bottom: 25px; }
        .kop-surat h4 { color: #198754; font-weight: 700; margin-bottom: 0; }
        .title-box { 
            background: linear-gradient(135deg, #198754, #0f5132); 
            color: white; 
            padding: 20px; 
            border-radius: 8px; 
            text-align: center; 
            margin-bottom: 25px; 
        }
        .title-box h5 { margin: 0; font-weight: 600; letter-spacing: 1px; }
        .congrats-box {
            background: linear-gradient(135deg, #d1e7dd, #badbcc);
            border: 2px solid #198754;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            margin-bottom: 25px;
        }
        .congrats-icon { font-size: 60px; color: #198754; }
        .info-box { background: #f8f9fa; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
        .section-title { font-size: 14px; font-weight: 600; color: #198754; margin-bottom: 15px; border-left: 4px solid #198754; padding-left: 10px; }
        .data-table { width: 100%; }
        .data-table td { padding: 10px 15px; border: 1px solid #dee2e6; }
        .data-table td:first-child { background: #f8f9fa; width: 180px; font-weight: 500; color: #495057; }
        .highlight-box { background: #198754; color: white; padding: 15px 25px; border-radius: 10px; text-align: center; }
        .highlight-box .label { font-size: 12px; opacity: 0.9; }
        .highlight-box .value { font-size: 20px; font-weight: 700; }
        .footer-note { background: #d1e7dd; border-left: 4px solid #198754; padding: 15px; margin-top: 25px; font-size: 13px; }
        .signature-area { text-align: center; margin-top: 40px; }
        .signature-line { border-bottom: 1px solid #000; width: 200px; margin: 60px auto 5px; }
        .stamp-area { 
            border: 2px dashed #198754; 
            width: 100px; 
            height: 100px; 
            margin: 20px auto; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center;
            color: #198754;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="no-print bg-success py-3 sticky-top">
        <div class="container">
            <div class="d-flex justify-content-center gap-3">
                <button onclick="window.print()" class="btn btn-light">
                    <i class="bi bi-printer me-2"></i>Cetak
                </button>
                <a href="<?php echo url('/dashboard'); ?>" class="btn btn-outline-light">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="print-container">
        <!-- Kop Surat -->
        <div class="kop-surat text-center">
            <div class="d-flex justify-content-center align-items-center gap-3">
                <img src="<?php echo asset('images/logo-padang.png'); ?>" alt="Logo" class="header-logo" onerror="this.style.display='none'">
                <div>
                    <p class="text-muted mb-1">PEMERINTAH KOTA PADANG</p>
                    <h4>DINAS PENDIDIKAN</h4>
                    <p class="text-muted">Jl. Bagindo Aziz Chan No. 8, Padang - Sumatera Barat</p>
                </div>
            </div>
        </div>

        <!-- Title -->
        <div class="title-box">
            <h5><i class="bi bi-award me-2"></i>SURAT KETERANGAN DITERIMA</h5>
            <small>Penerimaan Peserta Didik Baru Tahun Pelajaran 2024/2025</small>
        </div>

        <!-- Congratulations -->
        <div class="congrats-box">
            <div class="congrats-icon"><i class="bi bi-check-circle-fill"></i></div>
            <h3 class="text-success fw-bold mt-2">SELAMAT!</h3>
            <p class="mb-0">Anda dinyatakan <strong>DITERIMA</strong> sebagai Peserta Didik Baru</p>
        </div>

        <!-- Info Boxes -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="highlight-box">
                    <div class="label">NO. PENDAFTARAN</div>
                    <div class="value"><?php echo e($data['no_pendaftaran'] ?? '-'); ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="highlight-box">
                    <div class="label">JALUR</div>
                    <div class="value"><?php echo strtoupper($data['jalur'] ?? '-'); ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="highlight-box">
                    <div class="label">STATUS</div>
                    <div class="value">DITERIMA</div>
                </div>
            </div>
        </div>

        <!-- Data Siswa -->
        <div class="section-title">DATA PESERTA DIDIK BARU</div>
        <table class="data-table mb-4">
            <tr>
                <td>NISN</td>
                <td><?php echo e($data['nisn'] ?? '-'); ?></td>
            </tr>
            <tr>
                <td>Nama Lengkap</td>
                <td><strong><?php echo e($data['nama_siswa'] ?? '-'); ?></strong></td>
            </tr>
            <tr>
                <td>NIK</td>
                <td><?php echo e($data['nik'] ?? '-'); ?></td>
            </tr>
            <tr>
                <td>Tempat, Tgl Lahir</td>
                <td><?php echo e($data['tempat_lahir'] ?? '-'); ?>, <?php echo date('d F Y', strtotime($data['tanggal_lahir'] ?? 'now')); ?></td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td><?php echo ($data['jenis_kelamin'] ?? 'L') == 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td><?php echo e($data['alamat'] ?? '-'); ?></td>
            </tr>
            <tr>
                <td>Sekolah Asal</td>
                <td><?php echo e($data['sekolah_asal'] ?? '-'); ?></td>
            </tr>
        </table>

        <!-- Sekolah Tujuan -->
        <div class="section-title">DITERIMA DI SEKOLAH</div>
        <table class="data-table mb-4">
            <tr>
                <td>Nama Sekolah</td>
                <td><strong class="text-success"><?php echo e($data['nama_sekolah'] ?? '-'); ?></strong></td>
            </tr>
            <tr>
                <td>Alamat Sekolah</td>
                <td><?php echo e($data['alamat_sekolah'] ?? '-'); ?></td>
            </tr>
        </table>

        <!-- Catatan -->
        <div class="footer-note">
            <strong><i class="bi bi-exclamation-triangle me-2"></i>PENTING - Langkah Selanjutnya:</strong>
            <ol class="mb-0 mt-2">
                <li>Lakukan <strong>DAFTAR ULANG</strong> sesuai jadwal yang ditentukan.</li>
                <li>Bawa dokumen asli: Ijazah/SKL, Akta Kelahiran, KK, KTP Orang Tua.</li>
                <li>Siapkan pas foto terbaru ukuran 3x4 sebanyak 4 lembar.</li>
                <li>Datang tepat waktu ke sekolah tujuan untuk proses daftar ulang.</li>
                <li>Jika tidak melakukan daftar ulang sesuai jadwal, dianggap <strong>MENGUNDURKAN DIRI</strong>.</li>
            </ol>
        </div>

        <!-- Tanda Tangan -->
        <div class="row mt-5">
            <div class="col-6">
                <div class="stamp-area">
                    <span>Stempel<br>Sekolah</span>
                </div>
            </div>
            <div class="col-6">
                <div class="signature-area">
                    <p class="mb-0">Padang, <?php 
                        $bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                        echo date('d') . ' ' . $bulan[date('n')-1] . ' ' . date('Y');
                    ?></p>
                    <p class="mb-0">Kepala Sekolah,</p>
                    <div class="signature-line"></div>
                    <p class="fw-bold mb-0">NIP. ........................</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-4 text-muted" style="font-size: 12px;">
            <hr>
            <p class="mb-0">Surat ini dicetak secara otomatis dari sistem <?php echo APP_NAME; ?></p>
            <p class="mb-0">Dicetak pada: <?php echo date('d/m/Y H:i:s'); ?> WIB</p>
        </div>
    </div>
</body>
</html>
