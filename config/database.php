<?php
// config/database.php

$host = "localhost";
$username = "ifummiid_kelasa";
$password = "pemweb_db_a";
$database = "ifummiid_kelasa";

// Mengaktifkan exception mode untuk mysqli (Best Practice)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $db = new mysqli($host, $username, $password, $database);
    $db->set_charset("utf8mb4");
} catch (Exception $e) {
    error_log("Database Connection Error: " . $e->getMessage());
    die("Sistem sedang mengalami gangguan, gagal terhubung ke database.");
}
?>
