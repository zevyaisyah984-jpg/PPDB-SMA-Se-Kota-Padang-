<?php
/**
 * Portal Home View - Aggregates all sections
 */
?>

<!-- Hero Section -->
<?php view('portal.sections.hero', [
    'stats' => $stats ?? [], 
    'sekolah_list' => $sekolah_list ?? [],
    'is_registration_open' => $is_registration_open ?? false,
    'pendaftaran_jadwal' => $pendaftaran_jadwal ?? []
]); ?>

<!-- Mekanisme Pendaftaran -->
<?php view('portal.sections.mekanisme', ['stats' => $stats ?? []]); ?>

<!-- Fitur Utama -->
<?php view('portal.sections.fitur'); ?>

<!-- Requirements Section -->
<?php view('portal.sections.persyaratan'); ?>

<!-- Main Content (Search & Map) -->
<?php view('portal.sections.search_map'); ?>

<!-- Section Direktori Sekolah -->
<?php view('portal.sections.directory', ['sekolah_list' => $sekolah_list ?? []]); ?>

<!-- Registration Flow Modal -->
<?php view('portal.partials.flow_modal'); ?>

<!-- Additional Styles for placeholders -->
<style>
    .placeholder-white::placeholder { color: rgba(255,255,255,0.7) !important; }
    .backdrop-blur { backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); }
</style>

<!-- Pass PHP data to JavaScript -->
<script>
    window.sekolahData = <?php echo json_encode($sekolah_list ?? []); ?>;
</script>

