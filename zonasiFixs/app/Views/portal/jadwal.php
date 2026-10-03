<div class="bg-gov-light py-5">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Jadwal PPDB</h2>
            <p class="text-muted">Tahun Pelajaran <?php echo date('Y'); ?>/<?php echo (int)date('Y') + 1; ?></p>

        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-body p-4">
                        <div class="timeline">
                            <?php if (!empty($jadwal)): ?>
                                <?php 
                                $colors = ['primary', 'success', 'warning', 'info', 'danger', 'secondary'];
                                foreach ($jadwal as $index => $item): 
                                    $color = $colors[$index % count($colors)];
                                ?>
                                <!-- Item <?php echo $item['urutan']; ?> -->
                                <div class="d-flex mb-4">
                                    <div class="flex-shrink-0">
                                        <div class="bg-<?php echo $color; ?> text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="bi bi-<?php echo ($index + 1); ?>-circle"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-4">
                                        <h5 class="fw-bold"><?php echo e($item['nama_kegiatan']); ?></h5>
                                        <p class="text-<?php echo $color; ?> fw-semibold mb-1">
                                            <?php 
                                            $start = strtotime($item['tanggal_mulai']);
                                            $end = strtotime($item['tanggal_selesai']);
                                            
                                            // Handle time if present (assuming stored as separate columns or DateTime, but current schema has separate time columns? let's check schema again if needed. Schema has 'waktu_mulai' and 'waktu_selesai' separately)
                                            // Actually based on schema: tanggal_mulai, tanggal_selesai
                                            if ($item['tanggal_mulai'] == $item['tanggal_selesai']) {
                                                echo date('d F Y', $start);
                                            } else {
                                                // Check if same month and year
                                                if (date('Y-m', $start) == date('Y-m', $end)) {
                                                    echo date('d', $start) . ' - ' . date('d F Y', $end);
                                                } else {
                                                    echo date('d F Y', $start) . ' - ' . date('d F Y', $end);
                                                }
                                            }
                                            ?>
                                        </p>
                                        <p class="text-muted small mb-0"><?php echo !empty($item['keterangan']) ? e($item['keterangan']) : '-'; ?></p>
                                        <!-- Optional: Status Badge -->
                                        <?php if($item['status'] == 'berlangsung'): ?>
                                            <span class="badge bg-success mt-2">Sedang Berlangsung</span>
                                        <?php elseif($item['status'] == 'selesai'): ?>
                                            <span class="badge bg-secondary mt-2">Selesai</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <img src="<?php echo asset('images/no-data.svg'); ?>" alt="Belum ada jadwal" style="max-width: 150px; opacity: 0.5;">
                                    <p class="mt-3 text-muted">Jadwal PPDB belum tersedia saat ini.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
