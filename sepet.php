<?php
session_start();
require_once 'includes/db.php';

$toplam = 0;
$masa_no = isset($_SESSION['masa_no']) ? $_SESSION['masa_no'] : "Masa Seçilmedi";

if (empty($_SESSION['sepet'])) {
    $sepet_bos = true;
} else {
    $sepet_bos = false;
    foreach($_SESSION['sepet'] as $item) {
        $toplam += ($item['fiyat'] * $item['adet']);
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sepetim | QR Menü</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4" style="max-width: 600px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>🛒 Sepetiniz</h4>
            <span class="badge bg-dark fs-6"><?php echo $masa_no; ?></span>
        </div>

        <?php if ($sepet_bos): ?>
            <div class="card p-5 text-center shadow-sm">
                <p class="text-muted">Sepetiniz şu an boş.</p>
                <a href="index.php" class="btn btn-primary">Menüye Dön</a>
            </div>
        <?php else: ?>
            <div class="card shadow-sm mb-3">
                <ul class="list-group list-group-flush">
                    <?php foreach($_SESSION['sepet'] as $id => $item): ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <div>
                                <strong><?php echo $item['ad']; ?></strong><br>
                                <small class="text-muted"><?php echo $item['adet']; ?> x <?php echo $item['fiyat']; ?> TL</small>
                            </div>
                            <span class="fw-bold"><?php echo ($item['fiyat'] * $item['adet']); ?> TL</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="card-footer d-flex justify-content-between bg-white py-3">
                    <span class="h5">Toplam:</span>
                    <span class="h5 text-success"><?php echo $toplam; ?> TL</span>
                </div>
            </div>

            <a href="odeme.php" class="btn btn-success w-100 py-3 fw-bold shadow">ÖDEMEYE GEÇ (<?php echo $toplam; ?> TL)</a>
            <a href="sepet_islem.php?bosalt=1" class="btn btn-link text-danger w-100 mt-2">Sepeti Temizle</a>
        <?php endif; ?>
    </div>
</body>
</html>