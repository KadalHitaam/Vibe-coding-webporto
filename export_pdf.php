<?php
require_once 'includes/auth.php';
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Pengunjung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            @page { margin: 2cm; }
            body { font-family: 'Times New Roman', serif; background-color: white !important; }
            .card { border: none !important; box-shadow: none !important; }
            .no-print { display: none !important; }
        }
        body { font-family: 'Arial', sans-serif; background-color: #f8f9fa; }
    </style>
</head>
<body class="p-4">
    <div class="card shadow-sm border-0 mx-auto no-print-shadow" style="max-width: 900px;">
        <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4">
                <h3 class="fw-bold mb-1">LAPORAN DATA PENGUNJUNG</h3>
                <p class="text-muted mb-0">Buku Tamu Digital MyProfile</p>
                <hr style="border: 2px solid #2c3e50; opacity: 1; margin-top: 15px;">
            </div>

            <table class="table table-bordered border-dark mt-4">
                <thead class="text-center align-middle" style="background-color: #e9ecef;">
                    <tr>
                        <th width="5%">No.</th>
                        <th width="35%">Nama Pengunjung</th>
                        <th width="20%">Status</th>
                        <th width="40%">Tanda Tangan</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    <?php
                    $no = 1;
                    $query_tampil = $db->query("SELECT * FROM buku_tamu_naufal_2430511010 ORDER BY id ASC");
                    while ($row = $query_tampil->fetch_assoc()) {
                    ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['nama']); ?></td>
                        <td class="text-center">
                            <?= ($row['status'] == 1) ? 'Hadir' : 'Titip Salam'; ?>
                        </td>
                        <td class="text-center">
                            <?php if(!empty($row['tanda_tangan'])): ?>
                                <img src="<?= $row['tanda_tangan']; ?>" alt="TTD" style="max-height: 50px; border: 1px solid #ccc; background: #fff; padding: 2px;">
                            <?php else: ?>
                                <i>Tidak ada TTD</i>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            
            <div class="text-end mt-5 text-muted">
                <small>Dicetak pada: <?= date('d M Y, H:i'); ?></small>
            </div>
            
            <div class="text-center mt-4 no-print">
                <button onclick="window.print()" class="btn btn-primary px-4 rounded-pill">Cetak Laporan</button>
                <a href="guestbook.php" class="btn btn-outline-secondary px-4 rounded-pill ms-2">Kembali</a>
            </div>
        </div>
    </div>

    <!-- Script to auto trigger print dialogue -->
    <script>
        window.onload = function() {
            // Uncomment line below to auto-print on load
            // window.print();
        }
    </script>
</body>
</html>
