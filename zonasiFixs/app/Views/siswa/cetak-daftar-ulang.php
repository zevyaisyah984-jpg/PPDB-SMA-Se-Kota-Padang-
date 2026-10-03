<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Daftar Ulang - <?php echo APP_NAME; ?></title>
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
        .kop-surat { border-bottom: 3px solid #6f42c1; padding-bottom: 15px; margin-bottom: 25px; }
        .kop-surat h4 { color: #6f42c1; font-weight: 700; margin-bottom: 0; }
        .title-box { 
            background: linear-gradient(135deg, #6f42c1, #59359a); 
            color: white; 
            padding: 20px; 
            border-radius: 8px; 
            text-align: center; 
            margin-bottom: 25px; 
        }
        .title-box h5 { margin: 0; font-weight: 600; letter-spacing: 1px; }
        .verified-box {
            background: linear-gradient(135deg, #e2d9f3, #d1c4e9);
            border: 2px solid #6f42c1;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            margin-bottom: 25px;
        }
        .verified-icon { font-size: 60px; color: #6f42c1; }
        .section-title { font-size: 14px; font-weight: 600; color: #6f42c1; margin-bottom: 15px; border-left: 4px solid #6f42c1; padding-left: 10px; }
        .data-table { width: 100%; }
        .data-table td { padding: 10px 15px; border: 1px solid #dee2e6; }
        .data-table td:first-child { background: #f8f9fa; width: 180px; font-weight: 500; color: #495057; }
        .highlight-box { background: #6f42c1; color: white; padding: 15px 25px; border-radius: 10px; text-align: center; margin-bottom: 15px; }
        .highlight-box .label { font-size: 12px; opacity: 0.9; }
        .highlight-box .value { font-size: 18px; font-weight: 700; }
        .checklist-box { background: #f8f9fa; border-radius: 8px; padding: 20px; }
        .checklist-item { display: flex; align-items: center; padding: 8px 0; border-bottom: 1px dashed #dee2e6; }
        .checklist-item:last-child { border-bottom: none; }
        .checklist-icon { width: 30px; height: 30px; background: #6f42c1; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; }
        .footer-note { background: #e2d9f3; border-left: 4px solid #6f42c1; padding: 15px; margin-top: 25px; font-size: 13px; }
        .signature-area { text-align: center; margin-top: 40px; }
        .signature-line { border-bottom: 1px solid #000; width: 200px; margin: 60px auto 5px; }
        .nis-box { 
            background: linear-gradient(135deg, #6f42c1, #59359a);
            color: white;
            padding: 20px 30px;
            border-radius: 10px;
            text-align: center;
            margin: 20px 0;
        }
        .nis-box .label { font-size: 14px; opacity: 0.9; }
        .nis-box .nis { font-size: 32px; font-weight: 700; letter-spacing: 3px; }
    </style>
</head>
<body>
    <div class="no-print py-3 sticky-top" style="background: #6f42c1;">
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
                    <h4><?php echo e($data['nama_sekolah'] ?? 'SMA NEGERI KOTA PADANG'); ?></h4>
                    <p class="text-muted"><?php echo e($data['alamat_sekolah'] ?? 'Kota Padang - Sumatera Barat'); ?></p>
                </div>
            </div>
        </div>

        <!-- Title -->
        <div class="title-box">
            <h5><i class="bi bi-patch-check me-2"></i>BUKTI DAFTAR ULANG</h5>
            <small>Penerimaan Peserta Didik Baru Tahun Pelajaran 2024/2025</small>
        </div>

        <!-- Verified Box -->
        <div class="verified-box">
            <div class="verified-icon"><i class="bi bi-patch-check-fill"></i></div>
            <h3 class="fw-bold mt-2" style="color: #6f42c1;">DAFTAR ULANG BERHASIL</h3>
            <p class="mb-0">Anda telah resmi terdaftar sebagai Peserta Didik Baru</p>
        </div>

        <!-- NIS Box -->
        <div class="nis-box">
            <div class="label">NOMOR INDUK SISWA (NIS)</div>
            <div class="nis"><?php echo e($data['nis'] ?? '2024' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT)); ?></div>
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
                    <div class="label">TANGGAL DAFTAR ULANG</div>
                    <div class="value"><?php echo date('d/m/Y'); ?></div>
                </div>
            </div>
        </div>

        <!-- Data Siswa -->
        <div class="section-title">DATA PESERTA DIDIK</div>
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
        </table>

        <!-- Dokumen Terverifikasi -->
        <div class="section-title">DOKUMEN TERVERIFIKASI</div>
        <div class="checklist-box mb-4">
            <div class="checklist-item">
                <div class="checklist-icon"><i class="bi bi-check"></i></div>
                <span>Ijazah/Surat Keterangan Lulus (SKL) Asli</span>
            </div>
            <div class="checklist-item">
                <div class="checklist-icon"><i class="bi bi-check"></i></div>
                <span>Akta Kelahiran Asli</span>
            </div>
            <div class="checklist-item">
                <div class="checklist-icon"><i class="bi bi-check"></i></div>
                <span>Kartu Keluarga (KK) Asli</span>
            </div>
            <div class="checklist-item">
                <div class="checklist-icon"><i class="bi bi-check"></i></div>
                <span>KTP Orang Tua/Wali</span>
            </div>
            <div class="checklist-item">
                <div class="checklist-icon"><i class="bi bi-check"></i></div>
                <span>Pas Foto 3x4 (4 Lembar)</span>
            </div>
        </div>

        <!-- Catatan -->
        <div class="footer-note">
            <strong><i class="bi bi-info-circle me-2"></i>Informasi Penting:</strong>
            <ul class="mb-0 mt-2">
                <li>Simpan bukti daftar ulang ini sebagai bukti sah penerimaan.</li>
                <li>Ikuti pengumuman jadwal orientasi siswa baru (MPLS) melalui website sekolah.</li>
                <li>Siapkan perlengkapan sekolah sesuai ketentuan yang berlaku.</li>
                <li>Hubungi pihak sekolah jika ada pertanyaan lebih lanjut.</li>
            </ul>
        </div>

        <!-- Tanda Tangan -->
        <div class="row mt-5">
            <div class="col-6">
                <div class="signature-area">
                    <p class="mb-0">Peserta Didik,</p>
                    <div class="signature-line"></div>
                    <p class="fw-bold mb-0"><?php echo e($data['nama_siswa'] ?? '-'); ?></p>
                </div>
            </div>
            <div class="col-6">
                <div class="signature-area">
                    <p class="mb-0">Padang, <?php 
                        $bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                        echo date('d') . ' ' . $bulan[date('n')-1] . ' ' . date('Y');
                    ?></p>
                    <p class="mb-0">Petugas Daftar Ulang,</p>
                    <div class="signature-line"></div>
                    <p class="fw-bold mb-0">NIP. ........................</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-4 text-muted" style="font-size: 12px;">
            <hr>
            <p class="mb-0">Dokumen ini dicetak secara otomatis dari sistem <?php echo APP_NAME; ?></p>
            <p class="mb-0">Dicetak pada: <?php echo date('d/m/Y H:i:s'); ?> WIB</p>
        </div>
    </div>
</body>
</html>
