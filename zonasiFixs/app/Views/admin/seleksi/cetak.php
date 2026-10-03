<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Seleksi PPDB - <?php echo $sekolah_nama; ?></title>
    <style>
        body { font-family: 'Times New Roman', serif; padding: 40px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px double #000; padding-bottom: 20px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        .header h3 { margin: 5px 0; font-weight: normal; }
        .meta { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        .footer { margin-top: 50px; text-align: right; }
        @media print {
            @page { size: A4; margin: 2cm; }
            .no-print { display: none; }
        }
    </style>
</head>
<onload="window.print()">
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Cetak Dokumen</button>
        <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer;">Tutup</button>
    </div>

    <div class="header">
        <h2>PEMERINTAH PROVINSI SUMATERA BARAT</h2>
        <h3>DINAS PENDIDIKAN</h3>
        <h2><?php echo strtoupper($sekolah_nama); ?></h2>
    </div>

    <div class="meta">
        <strong>Laporan Hasil Seleksi PPDB Tahun <?php echo date('Y'); ?></strong><br>
        Tanggal Cetak: <?php echo date('d F Y'); ?>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">No. Daftar</th>
                <th>Nama Siswa</th>
                <th style="width: 15%;">NISN</th>
                <th style="width: 15%;">Jalur</th>
                <th style="width: 10%;">Skor/Jarak</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($hasil)): ?>
                <tr><td colspan="6" style="text-align: center;">Tidak ada data siswa diterima.</td></tr>
            <?php else: ?>
                <?php $no = 1; foreach ($hasil as $row): ?>
                <tr>
                    <td style="text-align: center;"><?php echo $no++; ?></td>
                    <td><?php echo $row['no_pendaftaran']; ?></td>
                    <td><?php echo strtoupper($row['nama_siswa']); ?></td>
                    <td><?php echo $row['nisn']; ?></td>
                    <td><?php echo ucfirst($row['jalur']); ?></td>
                    <td><?php echo !empty($row['jarak']) ? $row['jarak'] . ' KM' : ($row['skor_akhir'] ?? '-'); ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        Padang, <?php echo date('d F Y'); ?><br>
        Kepala Sekolah,<br>
        <br><br><br>
        _________________________
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
