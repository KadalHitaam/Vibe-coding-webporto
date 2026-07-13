<?php
// includes/auth.php

// Pastikan sesi hanya dimulai jika belum ada sesi aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function check_auth() {
    if (!isset($_SESSION['status']) || $_SESSION['status'] !== "sudah_login") {
        header("Location: login.php?pesan=belum_login");
        exit();
    }
}

// Otomatis jalankan check auth jika file ini di-include
check_auth();
?>
