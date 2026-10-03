<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Hasil Seleksi - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .print-container { box-shadow: none !important; margin: 0 !important; width: 100% !important; padding: 0 !important; }
        }
        body { background: #f0f2f5; font-family: 'Times New Roman', Times, serif; }
        .print-container { 
            max-width: 800px; 
            margin: 20px auto; 
            background: white; 
            padding: 50px; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .header-logo { height: 80px; }
        .kop-surat { 
            border-bottom: 4px double #000; 
            padding-bottom: 5px; 
            margin-bottom: 30px; 
            text-align: center;
        }
        .kop-surat h4 { font-weight: bold; margin: 0; font-family: Arial, sans-serif; }
        .kop-surat h5 { font-weight: bold; margin: 0; font-family: Arial, sans-serif; }
        .kop-surat p { margin: 0; font-size: 14px; font-family: Arial, sans-serif; }
        
        .surat-title { text-align: center; margin-bottom: 30px; }
        .surat-title h4 { text-decoration: underline; font-weight: bold; margin-bottom: 5px; }
        .surat-title p { margin: 0; }

        .content { font-size: 12pt; line-height: 1.6; text-align: justify; }
        .table-data { width: 100%; margin-left: 20px; margin-bottom: 20px; }
        .table-data td { padding: 3px 0; vertical-align: top; }
        .table-data td:first-child { width: 180px; }
        .table-data td:nth-child(2) { width: 20px; }
        .table-data td:nth-child(3) { font-weight: bold; }

        .status-box {
            border: 2px solid #dc3545;
            background-color: #f8d7da;
            color: #842029;
            padding: 15px;
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            margin: 20px 0;
            border-radius: 5px;
        }

        .signature {
            float: right;
            width: 250px;
            text-align: left;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="no-print py-3 sticky-top" style="background: #dc3545;">
        <div class="container">
            <div class="d-flex justify-content-center gap-3">
                <button onclick="window.print()" class="btn btn-light fw-bold text-danger">
                    <i class="bi bi-printer me-2"></i>Cetak Dokumen
                </button>
                <a href="<?php echo url('/dashboard'); ?>" class="btn btn-outline-light">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="print-container">
        <!-- Kop Surat -->
        <div class="kop-surat">
            <div class="d-flex align-items-center justify-content-center gap-4">
                <img src="<?php echo asset('images/logo-padang.png'); ?>" alt="Logo" class="header-logo" onerror="this.style.display='none'">
                <div class="text-center">
                    <h5>PEMERINTAH PROVINSI SUMATERA BARAT</h5>
                    <h4>DINAS PENDIDIKAN</h4>
                    <h4><?php echo strtoupper($data['nama_sekolah'] ?? 'SMA NEGERI'); ?></h4>
                    <p><?php echo $data['alamat_sekolah'] ?? 'Alamat Sekolah'; ?></p>
                </div>
            </div>
        </div>

        <!-- Judul Surat -->
        <div class="surat-title">
            <h4>SURAT PENGUMUMAN HASIL SELEKSI</h4>
            <p>NOMOR: 421/002/PPDB/<?php echo date('Y'); ?></p>
        </div>

        <div class="content">
            <p>Kepala <?php echo $data['nama_sekolah']; ?>, dengan ini menerangkan bahwa:</p>

            <table class="table-data">
                <tr>
                    <td>Nama Lengkap</td>
                    <td>:</td>
                    <td><?php echo strtoupper($data['nama_siswa'] ?? '-'); ?></td>
                </tr>
                <tr>
                    <td>NISN</td>
                    <td>:</td>
                    <td><?php echo $data['nisn'] ?? '-'; ?></td>
                </tr>
                <tr>
                    <td>NIK</td>
                    <td>:</td>
                    <td><?php echo $data['nik'] ?? '-'; ?></td>
                </tr>
                <tr>
                    <td>Tempat, Tgl Lahir</td>
                    <td>:</td>
                    <td><?php echo $data['tempat_lahir'] ?? '-'; ?>, <?php echo isset($data['tanggal_lahir']) ? date('d F Y', strtotime($data['tanggal_lahir'])) : '-'; ?></td>
                </tr>
                <tr>
                    <td>Asal Sekolah</td>
                    <td>:</td>
                    <td><?php echo $data['sekolah_asal'] ?? '-'; ?></td>
                </tr>
                <tr>
                    <td>Jalur Pendaftaran</td>
                    <td>:</td>
                    <td><?php echo strtoupper($data['jalur'] ?? '-'); ?></td>
                </tr>
            </table>

            <p>Berdasarkan hasil seleksi Penerimaan Peserta Didik Baru (PPDB) Tahun Pelajaran <?php echo date('Y'); ?>/<?php echo date('Y')+1; ?>, peserta didik tersebut dinyatakan:</p>

            <div class="status-box">
                TIDAK LULUS / TIDAK DITERIMA
            </div>

            <p>Mohon maaf, Anda belum dapat diterima di <strong><?php echo $data['nama_sekolah']; ?></strong> pada periode ini. Keputusan panitia seleksi bersifat mutlak dan tidak dapat diganggu gugat.</p>
            
            <p>Kami menyarankan agar Anda dapat mendaftar ke sekolah lain yang masih memiliki kuota atau melalui jalur pendaftaran lainnya jika memungkinkan. Tetap semangat dan jangan putus asa dalam menuntut ilmu.</p>

            <div class="signature">
                <p>Padang, <?php echo isset($data['published_at']) ? date('d F Y', strtotime($data['published_at'])) : date('d F Y'); ?></p>
                <p>Kepala Sekolah,</p>
                <br><br><br>
                <p><strong><u><?php echo $data['kepala_sekolah'] ?? '...........................'; ?></u></strong></p>
                <p>NIP. <?php echo $data['nip_kepala_sekolah'] ?? '...................'; ?></p>
            </div>
        </div>
        
        <div style="clear: both; margin-top: 100px; font-size: 10px; color: #666; border-top: 1px solid #ddd; padding-top: 10px;">
            <i>Dokumen ini dicetak otomatis dari Sistem PPDB Online Prov. Sumatera Barat pada <?php echo date('d/m/Y H:i:s'); ?>.</i>
        </div>
    </div>
</body>
</html>
