<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Verifikasi - <?php echo e($siswa['nama']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #0052CC; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f0f2f5; padding: 40px; color: #1a1a1a; }
        .print-container { max-width: 800px; margin: 0 auto; background: white; padding: 60px; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); position: relative; }
        .watermark { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg); font-size: 80px; color: rgba(0, 82, 204, 0.05); font-weight: 800; white-space: nowrap; pointer-events: none; }
        .header { text-align: center; border-bottom: 2px solid var(--primary); padding-bottom: 30px; margin-bottom: 40px; position: relative; }
        .header h1 { font-size: 20px; color: var(--primary); letter-spacing: 1px; margin-bottom: 5px; }
        .header p { font-size: 14px; color: #666; font-weight: 500; }
        .status-header { text-align: center; margin-bottom: 40px; }
        .badge { display: inline-block; background: var(--primary); color: white; padding: 12px 30px; border-radius: 50px; font-weight: 800; font-size: 14px; border: 4px solid #e6f0ff; }
        .info-section { margin-bottom: 35px; }
        .section-title { font-size: 12px; font-weight: 800; text-uppercase: uppercase; color: var(--primary); border-bottom: 1px solid #eee; padding-bottom: 8px; margin-bottom: 15px; letter-spacing: 1px; }
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table td { padding: 8px 0; font-size: 14px; }
        .info-table td:first-child { width: 35%; color: #718096; }
        .info-table td:last-child { font-weight: 600; color: #2d3748; }
        .signature-area { margin-top: 60px; display: flex; justify-content: flex-end; }
        .signature-box { text-align: center; width: 250px; }
        .signature-box .city-date { margin-bottom: 80px; }
        .signature-name { font-weight: 700; text-decoration: underline; margin-bottom: 2px; }
        .footer-note { margin-top: 50px; text-align: center; font-size: 11px; color: #a0aec0; border-top: 1px dashed #e2e8f0; padding-top: 20px; }
        @media print {
            body { background: white; padding: 0; }
            .print-container { box-shadow: none; border: 1px solid #eee; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="position: fixed; top: 20px; right: 20px; z-index: 1000;">
        <button onclick="window.print()" style="padding:12px 25px; font-size:14px; font-weight:700; cursor:pointer; background:var(--primary); color:white; border:none; border-radius:50px; box-shadow: 0 4px 15px rgba(0, 82, 204, 0.3);">
            🖨️ CETAK BUKTI VERIFIKASI
        </button>
    </div>

    <div class="print-container">
        <div class="watermark">PPDB ONLINE 2025</div>
        
        <div class="header">
            <h1 style="text-transform:uppercase;">Surat Bukti Verifikasi Data Calon Siswa Baru</h1>
            <p>SMA Negeri Sumatera Barat - Tahun Ajaran 2025/2026</p>
        </div>

        <div class="status-header">
            <span class="badge">DATA BERHASIL DIVERIFIKASI</span>
        </div>

        <div class="info-section">
            <div class="section-title">Informasi Peserta</div>
            <table class="info-table">
                <tr><td>Nama Lengkap</td><td>: <?php echo e($siswa['nama']); ?></td></tr>
                <tr><td>NISN / NIK</td><td>: <?php echo e($siswa['nisn']); ?> / <?php echo e($siswa['nik'] ?? '-'); ?></td></tr>
                <tr><td>Tempat, Tgl Lahir</td><td>: <?php echo e($siswa['tempat_lahir'] ?? '-'); ?>, <?php echo date('d F Y', strtotime($siswa['tanggal_lahir'])); ?></td></tr>
                <tr><td>Asal Sekolah</td><td>: <?php echo e($siswa['sekolah_asal'] ?? '-'); ?></td></tr>
            </table>
        </div>

        <div class="info-section">
            <div class="section-title">Detail Pendaftaran</div>
            <table class="info-table">
                <tr><td>Nomor Pendaftaran</td><td>: <span style="font-family: monospace; font-size: 16px;"><?php echo e($siswa['no_pendaftaran'] ?? '-'); ?></span></td></tr>
                <tr><td>Sekolah Pilihan</td><td>: <?php echo e($siswa['sekolah_nama']); ?></td></tr>
                <tr><td>Jalur / Sub-Jalur</td><td>: <?php echo strtoupper($siswa['jalur']); ?> / <?php echo strtoupper($siswa['sub_jalur'] ?? 'UMUM'); ?></td></tr>
                <tr><td>Tanggal Verifikasi</td><td>: <?php echo date('d F Y, H:i'); ?> WIB</td></tr>
            </table>
        </div>

        <div class="signature-area">
            <div class="signature-box">
                <p class="city-date">Padang, <?php echo date('d F Y'); ?></p>
                <p class="signature-name">Panitia PPDB SMAN Sumbar</p>
                <p style="font-size: 11px;">NIP. ........................................</p>
            </div>
        </div>

        <div class="footer-note">
            <p>Bukti ini merupakan tanda bahwa data anda telah diverifikasi secara fisik oleh panitia.</p>
            <p>Simpan lembar ini dengan baik untuk keperluan daftar ulang jika dinyatakan lulus seleksi.</p>
        </div>
    </div>
</body>
</html>
