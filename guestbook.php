<?php
require_once 'includes/auth.php';
require_once 'config/database.php';

// ==== PROSES HAPUS DATA ====
if (isset($_GET['hapus_id'])) {
    $id_hapus = intval($_GET['hapus_id']);
    
    // 1. Ambil nama file untuk dihapus fisik
    $stmt_file = $db->prepare("SELECT nama_file FROM file_tamu_naufal_2430511010 WHERE id_tamu = ?");
    $stmt_file->bind_param("i", $id_hapus);
    $stmt_file->execute();
    $result_file = $stmt_file->get_result();
    
    while ($file = $result_file->fetch_assoc()) {
        $path = "uploads/" . $file['nama_file'];
        if (file_exists($path)) {
            unlink($path);
        }
    }
    $stmt_file->close();
    
    // 2. Hapus data dari database (menggunakan Prepared Statements)
    $stmt_del_file = $db->prepare("DELETE FROM file_tamu_naufal_2430511010 WHERE id_tamu = ?");
    $stmt_del_file->bind_param("i", $id_hapus);
    $stmt_del_file->execute();
    $stmt_del_file->close();

    $stmt_del_tamu = $db->prepare("DELETE FROM buku_tamu_naufal_2430511010 WHERE id = ?");
    $stmt_del_tamu->bind_param("i", $id_hapus);
    $stmt_del_tamu->execute();
    $stmt_del_tamu->close();
    
    echo "<script>alert('Data berhasil dihapus!'); window.location='guestbook.php';</script>";
    exit();
}

// ==== PROSES EDIT DATA ====
if (isset($_POST['edit_data'])) {
    $id_edit = intval($_POST['id_edit']);
    $nama_edit = trim($_POST['nama']);
    $status_edit = intval($_POST['status']);
    
    $stmt_edit = $db->prepare("UPDATE buku_tamu_naufal_2430511010 SET nama=?, status=? WHERE id=?");
    $stmt_edit->bind_param("sii", $nama_edit, $status_edit, $id_edit);
    $stmt_edit->execute();
    $stmt_edit->close();
    
    echo "<script>alert('Data berhasil diupdate!'); window.location='guestbook.php';</script>";
    exit();
}

// ==== PROSES SIMPAN DATA ====
if (isset($_POST['simpan_data'])) {
    $nama = trim($_POST['nama']);
    $status = intval($_POST['status']);
    $ttd_base64 = $_POST['ttd_base64'];

    $stmt_tamu = $db->prepare("INSERT INTO buku_tamu_naufal_2430511010 (nama, status, tanda_tangan) VALUES (?, ?, ?)");
    $stmt_tamu->bind_param("sis", $nama, $status, $ttd_base64);
    
    if ($stmt_tamu->execute()) {
        $id_tamu = $stmt_tamu->insert_id;
        $stmt_tamu->close();

        // Proses Upload Multiple File (Aman)
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'];
        $jumlah_file = count($_FILES['files']['name']);

        for ($i = 0; $i < $jumlah_file; $i++) {
            $nama_file = $_FILES['files']['name'][$i];
            $tmp_name = $_FILES['files']['tmp_name'][$i];
            $error = $_FILES['files']['error'][$i];
            $ukuran = $_FILES['files']['size'][$i];
            
            if ($error === 0 && $nama_file != "" && $ukuran > 0) {
                $file_ext = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
                
                if (in_array($file_ext, $allowed_extensions)) {
                    // Beri nama unik agar tidak tertimpa
                    $nama_file_baru = uniqid() . '-' . basename($nama_file);
                    $folder_tujuan = "uploads/" . $nama_file_baru;
                    
                    if (move_uploaded_file($tmp_name, $folder_tujuan)) {
                        $stmt_upload = $db->prepare("INSERT INTO file_tamu_naufal_2430511010 (id_tamu, nama_file) VALUES (?, ?)");
                        $stmt_upload->bind_param("is", $id_tamu, $nama_file_baru);
                        $stmt_upload->execute();
                        $stmt_upload->close();
                    }
                }
            }
        }
        echo "<script>alert('Data berhasil disimpan!'); window.location='guestbook.php';</script>";
    }
}
?>

<?php require_once 'includes/header.php'; require_once 'includes/navbar.php'; ?>

<main class="container py-5">
    <div class="row g-4">
        <!-- Form Input -->
        <div class="col-lg-4">
            <div class="glass-card p-4 sticky-top" style="top: 100px; z-index: 1;">
                <h4 class="fw-bold text-gradient mb-4">Isi Buku Tamu 📝</h4>
                <form action="guestbook.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Pengunjung</label>
                        <input type="text" class="form-control" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select class="form-select" name="status">
                            <option value="1">Hadir</option>
                            <option value="2">Titip Salam</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Upload Lampiran</label>
                        <input type="file" class="form-control" name="files[]" multiple required accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                        <small class="text-muted d-block mt-1">Bisa pilih lebih dari satu file (JPG, PNG, PDF, DOC).</small>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Tanda Tangan Digital</label>
                        <div class="bg-white rounded-3 overflow-hidden" style="border: 2px dashed #cbd5e1;">
                            <canvas id="canvasTTD" width="100%" height="150" class="w-100"></canvas>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger mt-2 fw-semibold" onclick="clearCanvas()">Hapus TTD</button>
                        <input type="hidden" name="ttd_base64" id="ttd_base64" required>
                    </div>
                    <button type="submit" name="simpan_data" class="btn btn-premium w-100" onclick="saveCanvas()">Simpan Data</button>
                </form>
            </div>
        </div>

        <!-- Data Table -->
        <div class="col-lg-8">
            <div class="glass-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0">Daftar Pengunjung</h4>
                    <a href="export_pdf.php" target="_blank" class="btn btn-danger btn-sm px-3 shadow-sm">
                        <i class="bi bi-file-earmark-pdf"></i> Ekspor PDF
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="tabelTamu">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $query_tampil = $db->query("SELECT * FROM buku_tamu_naufal_2430511010 ORDER BY id DESC");
                            while ($row = $query_tampil->fetch_assoc()) {
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td class="fw-semibold text-dark"><?= htmlspecialchars($row['nama']); ?></td>
                                <td>
                                    <?php if($row['status'] == 1): ?>
                                        <span class="badge bg-success rounded-pill px-3 py-2">Hadir</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary rounded-pill px-3 py-2">Titip Salam</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#modalDetail<?= $row['id']; ?>">Detail</button>
                                        <button class="btn btn-sm btn-warning text-dark" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id']; ?>">Edit</button>
                                        <a href="guestbook.php?hapus_id=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data tamu beserta lampirannya?');">Hapus</a>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Detail -->
                            <div class="modal fade" id="modalDetail<?= $row['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header bg-light">
                                            <h5 class="modal-title fw-bold">Detail Pengunjung</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-center p-4">
                                            <h4 class="fw-bold mb-3"><?= htmlspecialchars($row['nama']); ?></h4>
                                            <div class="mb-4">
                                                <p class="text-muted fw-semibold mb-2">Tanda Tangan</p>
                                                <div class="bg-light p-2 rounded-3 mx-auto" style="width: fit-content; border: 1px solid #e2e8f0;">
                                                    <img src="<?= $row['tanda_tangan']; ?>" alt="TTD" style="max-height: 100px;">
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="text-start">
                                                <p class="text-muted fw-semibold mb-2">Lampiran File:</p>
                                                <ul class="list-group list-group-flush">
                                                    <?php
                                                    $id_tamu_sekarang = $row['id'];
                                                    $stmt_files = $db->prepare("SELECT nama_file FROM file_tamu_naufal_2430511010 WHERE id_tamu = ?");
                                                    $stmt_files->bind_param("i", $id_tamu_sekarang);
                                                    $stmt_files->execute();
                                                    $result_files = $stmt_files->get_result();
                                                    while($file = $result_files->fetch_assoc()){
                                                        echo "<li class='list-group-item bg-transparent px-0'><a href='uploads/".htmlspecialchars($file['nama_file'])."' target='_blank' class='text-decoration-none'>📄 ".htmlspecialchars($file['nama_file'])."</a></li>";
                                                    }
                                                    $stmt_files->close();
                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="modalEdit<?= $row['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content border-0 shadow">
                                        <form action="guestbook.php" method="POST">
                                            <div class="modal-header bg-light">
                                                <h5 class="modal-title fw-bold">Edit Pengunjung</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <input type="hidden" name="id_edit" value="<?= $row['id']; ?>">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Nama Pengunjung</label>
                                                    <input type="text" class="form-control" name="nama" value="<?= htmlspecialchars($row['nama']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Status</label>
                                                    <select class="form-select" name="status">
                                                        <option value="1" <?= $row['status'] == 1 ? 'selected' : ''; ?>>Hadir</option>
                                                        <option value="2" <?= $row['status'] == 2 ? 'selected' : ''; ?>>Titip Salam</option>
                                                    </select>
                                                </div>
                                                <div class="alert alert-light mt-3 mb-0 border" style="font-size: 0.85rem;">
                                                    <i class="bi bi-info-circle me-1"></i> Tanda tangan dan lampiran file tidak dapat diubah di sini.
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pb-4 pe-4">
                                                <button type="submit" name="edit_data" class="btn btn-premium">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>

<!-- Script Khusus Halaman Guestbook -->
<script>
$(document).ready(function() {
    $('#tabelTamu').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' }
    });
});

// Canvas Tanda Tangan
const canvas = document.getElementById('canvasTTD');
// Set actual canvas resolution to match its displayed size for crisp drawing
canvas.width = canvas.offsetWidth || 300; 
canvas.height = canvas.offsetHeight || 150;
const ctx = canvas.getContext('2d');
let isDrawing = false;

// Set default background to white (so it's not transparent in image)
ctx.fillStyle = "#ffffff";
ctx.fillRect(0, 0, canvas.width, canvas.height);

function startPosition(e) {
    isDrawing = true;
    draw(e);
}
function endPosition() {
    isDrawing = false;
    ctx.beginPath();
}
function draw(e) {
    if(!isDrawing) return;
    
    // Get correct mouse coordinates relative to canvas
    const rect = canvas.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;

    ctx.lineWidth = 2;
    ctx.lineCap = "round";
    ctx.strokeStyle = "#000000";

    ctx.lineTo(x, y);
    ctx.stroke();
    ctx.beginPath();
    ctx.moveTo(x, y);
}

canvas.addEventListener("mousedown", startPosition);
canvas.addEventListener("mouseup", endPosition);
canvas.addEventListener("mousemove", draw);

// Touch support for mobile
canvas.addEventListener("touchstart", function(e) {
    e.preventDefault();
    const touch = e.touches[0];
    const mouseEvent = new MouseEvent("mousedown", {
        clientX: touch.clientX,
        clientY: touch.clientY
    });
    canvas.dispatchEvent(mouseEvent);
}, { passive: false });

canvas.addEventListener("touchend", function(e) {
    e.preventDefault();
    const mouseEvent = new MouseEvent("mouseup", {});
    canvas.dispatchEvent(mouseEvent);
}, { passive: false });

canvas.addEventListener("touchmove", function(e) {
    e.preventDefault();
    const touch = e.touches[0];
    const mouseEvent = new MouseEvent("mousemove", {
        clientX: touch.clientX,
        clientY: touch.clientY
    });
    canvas.dispatchEvent(mouseEvent);
}, { passive: false });

function clearCanvas() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = "#ffffff";
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    document.getElementById('ttd_base64').value = "";
}

function saveCanvas() {
    // Only save if there's actually a drawing
    document.getElementById('ttd_base64').value = canvas.toDataURL("image/png");
}
</script>
