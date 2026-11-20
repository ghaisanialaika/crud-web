<?php
/**
 * ============================================================================
 * FOOTER TEMPLATE (includes/footer.php)
 * ============================================================================
 * Menutup kontainer utama (<main>), menyediakan elemen Toast Notification,
 * memuat dialog modals, dan memuat script JavaScript.
 */
?>
</main> <!-- Akhir dari Kontainer Konten Utama Fullscreen (<main>) -->

<!-- ---------------------------------------------------------------------------
     TOAST NOTIFICATION (Pemberitahuan mengambang saat aksi berhasil/gagal)
---------------------------------------------------------------------------- -->
<div id="toast-notification" 
     class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 pointer-events-none opacity-0 translate-y-4 transition-all duration-300 flex items-center gap-2.5 px-5 py-3 rounded-full text-xs sm:text-sm font-semibold shadow-2xl bg-brand-dark text-white">
    <span id="toast-icon" class="w-4 h-4 text-emerald-400 flex items-center justify-center">✓</span>
    <span id="toast-message">Aksi berhasil dilakukan.</span>
</div>

<!-- Modals (Dialog Tambah/Ubah & Hapus) -->
<?php require_once __DIR__ . '/modals.php'; ?>

<!-- File JavaScript Utama -->
<script src="assets/js/app.js"></script>
</body>
</html>
