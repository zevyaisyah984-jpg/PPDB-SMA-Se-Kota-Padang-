<?php
$waNumber = get_setting('helpdesk_wa', '6281234567890');
$waMessage = urlencode("Halo Admin, saya siswa pendaftar PPDB, saya butuh bantuan terkait...");
$waUrl = "https://wa.me/{$waNumber}?text={$waMessage}";
?>
<div class="wa-floating-btn shadow-lg">
    <a href="<?php echo $waUrl; ?>" target="_blank" class="d-flex align-items-center gap-2 text-decoration-none bg-success text-white px-3 py-2 rounded-pill">
        <i class="bi bi-whatsapp fs-4"></i>
        <span class="fw-semibold d-none d-md-inline">Butuh Bantuan?</span>
    </a>
</div>

<style>
.wa-floating-btn {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 9999;
    transition: transform 0.3s ease;
}
.wa-floating-btn:hover {
    transform: scale(1.05) translateY(-5px);
}
.wa-floating-btn a {
    box-shadow: 0 10px 25px rgba(25, 135, 84, 0.3) !important;
}
@media (max-width: 576px) {
    .wa-floating-btn {
        bottom: 20px;
        right: 20px;
    }
    .wa-floating-btn span {
        display: none !important;
    }
    .wa-floating-btn a {
        padding: 12px !important;
        border-radius: 50% !important;
    }
}
</style>
