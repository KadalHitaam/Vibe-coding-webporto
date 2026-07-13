<?php
require_once 'includes/auth.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

<main class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-gradient display-5">Multimedia 🎧</h2>
        <p class="text-muted fs-5">Kepoin lagu dan video favoritku di bawah ini!</p>
    </div>
    
    <div class="row justify-content-center g-4">
        <!-- Video Card -->
        <div class="col-md-6 col-lg-5">
            <div class="glass-card h-100 overflow-hidden">
                <div class="p-4 border-bottom" style="border-color: rgba(0,0,0,0.05) !important;">
                    <h5 class="fw-bold mb-0 text-center">🎬 Kimi No Nawa</h5>
                </div>
                <div class="p-3">
                    <video class="w-100 rounded-3 shadow-sm" controls>
                        <source src="assets/media/kiminonawa.mp4" type="video/mp4">
                        Browser kamu tidak mendukung pemutar video.
                    </video>
                </div>
                <div class="p-4 pt-0 text-center text-muted">
                    <small>Salah satu anime film terbaik dengan visual dan cerita yang luar biasa.</small>
                </div>
            </div>
        </div>

        <!-- Audio Card -->
        <div class="col-md-6 col-lg-5">
            <div class="glass-card h-100 overflow-hidden d-flex flex-column">
                <div class="p-4 border-bottom" style="border-color: rgba(0,0,0,0.05) !important;">
                    <h5 class="fw-bold mb-0 text-center">🎵 RADWIMPS</h5>
                </div>
                <div class="p-4 text-center flex-grow-1 d-flex flex-column justify-content-center">
                    <div class="mb-4">
                        <img src="assets/images/cat.png" alt="Music Cover" class="rounded-circle shadow" style="width: 120px; height: 120px; object-fit: cover; opacity: 0.8;">
                    </div>
                    <audio class="w-100 mb-3" controls>
                        <source src="assets/media/radwimps.mp3" type="audio/mpeg">
                        Browser kamu tidak mendukung pemutar audio.
                    </audio>
                    <small class="text-muted">Band favorit yang selalu menemani saat *coding*.</small>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
