<?php
/**
 * Notification Banner Component
 * Shows announcement when results are published
 */

// Check if user has published results
if (isset($_SESSION['user_id'])) {
    $publishedResult = getPublishedResultForUser($_SESSION['user_id']);
    
    // Show banner if result is published and not dismissed
    if ($publishedResult && !isset($_SESSION['announcement_seen_' . $publishedResult['id']])):
?>
<div class="alert alert-success alert-dismissible fade show mb-4 rounded-4 border-0 shadow-lg" role="alert" id="announcementBanner" style="animation: slideDown 0.5s ease-out;">
    <div class="d-flex align-items-start gap-3">
        <div class="bg-success bg-opacity-10 rounded-circle p-3">
            <i class="bi bi-megaphone-fill text-success fs-3"></i>
        </div>
        <div class="flex-grow-1">
            <h5 class="fw-bold mb-2">
                🎉 Pengumuman Resmi!
            </h5>
            <p class="mb-2">
                Hasil Seleksi <strong>Jalur <?php echo ucfirst($publishedResult['jalur']); ?></strong> 
                untuk <strong><?php echo htmlspecialchars($publishedResult['nama_sekolah']); ?></strong> 
                telah resmi diumumkan!
            </p>
            <p class="mb-0 small text-muted">
                <i class="bi bi-calendar-check me-1"></i>
                Dipublikasikan pada: <?php echo date('d M Y, H:i', strtotime($publishedResult['published_at'])); ?> WIB
            </p>
            <div class="mt-3">
                <a href="<?php echo url('/pengumuman'); ?>" class="btn btn-success btn-sm rounded-pill px-4">
                    <i class="bi bi-arrow-right-circle me-2"></i>
                    Lihat Status Kelulusan Saya
                </a>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" onclick="markAnnouncementSeen(<?php echo $publishedResult['id']; ?>)"></button>
    </div>
</div>

<style>
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
function markAnnouncementSeen(id) {
    // Mark as seen in session
    fetch('<?php echo url('/siswa/mark-announcement-seen'); ?>', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'announcement_id=' + id
    }).catch(err => console.log('Failed to mark announcement'));
}
</script>
<?php 
    endif;
}
?>
