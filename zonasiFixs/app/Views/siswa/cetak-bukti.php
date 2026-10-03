<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Keputusan Hasil Seleksi - <?php echo e($siswa['nama']); ?></title>
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
        
        .result-box {
            border: 2px solid #000;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
            background: #f8f9fa;
        }
        .result-status {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        @media print {
            @page { size: A4; margin: 2cm; }
            .no-print { display: none !important; }
            .result-box { background: none !important; } /* Clean background for print */
        }
    </style>
</head>
<body>

    <!-- Print Control -->
    <div class="fixed-top p-3 no-print bg-light border-bottom d-flex justify-content-between align-items-center">
        <div>
            <strong>Pratinjau Hasil Seleksi</strong>
            <small class="d-block text-muted">Dokumen resmi kelulusan.</small>
        </div>
        <div>
            <button onclick="window.print()" class="btn btn-primary btn-sm"><i class="bi bi-printer"></i> Cetak Dokumen</button>
            <a href="<?php echo url('/dashboard'); ?>" class="btn btn-outline-secondary btn-sm">Kembali</a>
        </div>
    </div>

    <!-- Document Content -->
    <div class="container bg-white p-5 mt-5">
        
        <!-- KOP SURAT (Dinamis sesuai sekolah) -->
        <div class="row kop-surat align-items-center">
            <div class="col-2 text-center">
                <!-- Fallback logo -->
                <img src="<?php echo asset('images/logo-sumbar.png'); ?>" alt="Logo" class="kop-logo" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/1/1b/Coat_of_arms_of_West_Sumatra.svg/1200px-Coat_of_arms_of_West_Sumatra.svg.png'">
            </div>
            <div class="col-10 kop-text">
                <h4>PEMERINTAH PROVINSI SUMATERA BARAT</h4>
                <h4>DINAS PENDIDIKAN</h4>
                <h4 class="mt-2 text-decoration-underline"><?php echo strtoupper($sekolah['nama']); ?></h4>
                <p><?php echo $sekolah['alamat']; ?> | <?php echo $sekolah['telepon'] ?? ''; ?></p>
            </div>
        </div>

        <div class="text-center mb-4">
            <h5 class="fw-bold text-decoration-underline">SURAT KEPUTUSAN</h5>
            <p>Nomor: 420/PPDB/<?php echo date('Y'); ?>/Sketch</p>
        </div>

        <p>Berdasarkan hasil Verifikasi Berkas dan Perankingan Sistem Penerimaan Peserta Didik Baru (PPDB) Online Provinsi Sumatera Barat Tahun Pelajaran 2025/2026, Kepala Sekolah memutuskan bahwa:</p>

        <table>
            <tr>
                <td class="label">Nama Lengkap</td>
                <td class="colon">:</td>
                <td class="fw-bold"><?php echo e($siswa['nama']); ?></td>
            </tr>
            <tr>
                <td class="label">NISN</td>
                <td class="colon">:</td>
                <td><?php echo e($siswa['nisn']); ?></td>
            </tr>
            <tr>
                <td class="label">Jalur Pendaftaran</td>
                <td class="colon">:</td>
                <td class="text-uppercase"><?php echo e($pendaftaran['jalur']); ?></td>
            </tr>
            <tr>
                <td class="label">Sekolah Tujuan</td>
                <td class="colon">:</td>
                <td><?php echo e($sekolah['nama']); ?></td>
            </tr>
        </table>

        <!-- RESULT BOX -->
        <div class="result-box">
            <p class="mb-2">Dinyatakan:</p>
            <?php if ($pendaftaran['status'] == 'diterima'): ?>
                <div class="result-status text-success" style="color: black !important; border: 3px double black; display: inline-block; padding: 10px 30px;">
                    LULUS / DITERIMA
                </div>
                <p class="mt-3">Sebagai Peserta Didik Baru di <?php echo e($sekolah['nama']); ?></p>
            <?php else: ?>
                 <div class="result-status text-danger" style="color: black !important; border: 3px double black; display: inline-block; padding: 10px 30px;">
                    TIDAK LOLOS
                </div>
                <p class="mt-3">Mohon maaf, Anda belum memenuhi kriteria penerimaan.</p>
            <?php endif; ?>
        </div>

        <?php if ($pendaftaran['status'] == 'diterima'): ?>
            <div class="mt-4">
                <p class="fw-bold">Catatan Pendaftaran Ulang:</p>
                <ol>
                    <li>Membawa Bukti Kelulusan ini (Dicetak).</li>
                    <li>Membawa Bukti Pendaftaran (Dicetak).</li>
                    <li>Membawa Berkas Asli (KK, Ijazah/SKL, Rapor) untuk verifikasi final.</li>
                    <li>Hadir di sekolah pada tanggal: <strong>11 - 12 Juli 2025</strong>.</li>
                </ol>
            </div>
        <?php else: ?>
            <div class="mt-4">
                <p class="fw-bold">Keterangan:</p>
                <p>Anda dapat mencoba mendaftar kembali pada Tahap 2 (Jalur Zonasi Pemenuhan Kuota) jika masih tersedia, atau mendaftar ke sekolah swasta. Tetap semangat!</p>
            </div>
        <?php endif; ?>

        <!-- Signature -->
        <div class="row mt-5 pt-3">
            <div class="col-6"></div>
            <div class="col-6 text-center">
                <p>Ditetapkan di: Padang</p>
                <p>Pada Tanggal: <?php echo date('d F Y'); ?></p>
                <p>Kepala Sekolah,</p>
                
                <!-- QR TTE -->
                <div class="my-2 mx-auto" style="width: 80px; height: 80px;">
                     <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=TTE-VALID:<?php echo urlencode($sekolah['nama']); ?>" alt="QR TTE" style="width: 100%;">
                </div>
                
                <p class="fw-bold text-decoration-underline"><?php echo $sekolah['kepala_sekolah'] ?? 'NAMA KEPALA SEKOLAH'; ?></p>
                <p>NIP. <?php echo $sekolah['nip_kepala_sekolah'] ?? '......................'; ?></p>
            </div>
        </div>
    </div>

</body>
</html>
