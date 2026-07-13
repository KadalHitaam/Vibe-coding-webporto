<?php
session_start();
require_once 'config/database.php';

// Jika sudah login, arahkan ke dashboard
if (isset($_SESSION['status']) && $_SESSION['status'] === "sudah_login") {
    header("Location: dashboard.php");
    exit();
}

$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (!empty($username) && !empty($password)) {
        // Menggunakan Prepared Statement untuk mencegah SQL Injection
        $stmt = $db->prepare("SELECT id, username, password FROM users_naufal_2430511010 WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verifikasi hash password
            if (password_verify($password, $user['password'])) {
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['status'] = "sudah_login";
                
                header("Location: dashboard.php");
                exit();
            } else {
                $error_msg = "Password yang dimasukkan salah!";
            }
        } else {
            $error_msg = "Username tidak ditemukan!";
        }
        $stmt->close();
    } else {
        $error_msg = "Harap isi username dan password.";
    }
}
?>

<?php require_once 'includes/header.php'; ?>
<main class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="glass-card p-4 p-md-5" style="max-width: 400px; width: 90%;">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-gradient">Login Dulu Okey😺</h2>
            <p class="text-muted">Silakan masuk untuk melanjutkan</p>
        </div>
        
        <?php if (!empty($error_msg)): ?>
            <div class="alert alert-danger rounded-3 text-center py-2">
                <?= htmlspecialchars($error_msg) ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['pesan']) && $_GET['pesan'] == "belum_login"): ?>
            <div class="alert alert-warning rounded-3 text-center py-2">
                Anda harus login untuk mengakses halaman tersebut.
            </div>
        <?php endif; ?>
        
        <form action="" method="POST">
            <div class="mb-3">
                <label class="form-label fw-semibold text-muted">Username</label>
                <input type="text" name="username" class="form-control form-control-lg" placeholder="Masukkan username" required>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold text-muted">Password</label>
                <input type="password" name="password" class="form-control form-control-lg" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn btn-premium btn-lg w-100">Login Sekarang</button>
        </form>
    </div>
</main>
<?php require_once 'includes/footer.php'; ?>
