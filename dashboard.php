<?php
require_once 'includes/auth.php'; // Proteksi halaman otomatis
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="glass-card p-5">
                <div class="d-flex align-items-center mb-4">
                    <img src="assets/images/cat.png" alt="Profile Mascot" class="rounded-circle shadow-sm me-4" style="width: 100px; height: 100px; object-fit: cover; border: 3px solid var(--primary-color);">
                    <div>
                        <h2 class="fw-bold text-gradient mb-1">Halo, Selamat Datang! 👋</h2>
                        <p class="text-muted fs-5 mb-0">Ini adalah dashboard utamaku.</p>
                    </div>
                </div>
                <hr style="border-color: rgba(0,0,0,0.1);">
                
                <h4 class="fw-bold mt-4 mb-3">Sekilas Tentangku</h4>
                <p class="fs-5 lh-lg" style="color: #4b5563;">
                    Hai, namaku <strong>Naufal</strong>! Aku adalah mahasiswa <strong>Teknik Informatika</strong> di Universitas Muhammadiyah Sukabumi.
                    Aku sangat antusias dalam mempelajari pengembangan web, merancang antarmuka pengguna yang menarik (UI/UX), serta mengeksplorasi teknologi-teknologi baru yang dapat membawa dampak positif.
                </p>
                
                <div class="mt-5 text-center">
                    <a href="guestbook.php" class="btn btn-premium me-3">Isi Buku Tamu 📝</a>
                    <a href="multimedia.php" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold shadow-sm bg-white">Lihat Multimedia 🎧</a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
