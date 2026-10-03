<?php
// Add this right after line 220 (after the step logic)

// Get open jalur for notification banner
$openJalur = getOpenJalur();
$hasOpenJalur = !empty($openJalur);
?>

<!-- NOTIFICATION BANNER - Add this right after line 301 (before Hero Section) -->
<?php if ($hasOpenJalur): ?>
<div class="alert alert-success alert-dismissible fade show mb-4 rounded-4 border-0 shadow-sm" role="alert">
    <div class="d-flex align-items-start gap-3">
        <i class="bi bi-megaphone-fill fs-3 mt-1"></i>
        <div class="flex-grow-1">
            <h6 class="fw-bold mb-1">📢 Pemberitahuan Penting!</h6>
            <p class="mb-0 small">
                <?php 
                $jalurNames = array_map(function($j) { return '<strong>' . ucfirst($j['jalur']) . '</strong>'; }, $openJalur);
                echo 'Jalur ' . implode(', ', $jalurNames) . ' telah resmi <strong>DIBUKA</strong>. Silakan lengkapi data Anda segera!';
                ?>
            </p>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php elseif (!$sudahDaftar): ?>
<div class="alert alert-warning alert-dismissible fade show mb-4 rounded-4 border-0 shadow-sm" role="alert">
    <div class="d-flex align-items-start gap-3">
        <i class="bi bi-exclamation-triangle-fill fs-3 mt-1"></i>
        <div class="flex-grow-1">
            <h6 class="fw-bold mb-1">⚠️ Pemberitahuan</h6>
            <p class="mb-0 small">Saat ini seluruh jalur pendaftaran sedang <strong>DITUTUP</strong>. Harap tunggu pengumuman resmi dari admin.</p>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php endif; ?>
