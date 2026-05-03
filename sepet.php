<?php
session_start();
require_once 'includes/db.php';

$masa_id = isset($_GET['masa']) ? intval($_GET['masa']) : (isset($_SESSION['masa_id']) ? $_SESSION['masa_id'] : 0);
$toplam = 0;
$sepet = $_SESSION['sepet'] ?? [];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sepetim | QR Menü</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .cart-card { border: none; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .btn-remove { background: #fff5f5; color: #ff4757; border: 1px solid #ffeded; border-radius: 8px; padding: 2px 10px; font-weight: bold; text-decoration: none; font-size: 0.8rem; }
    </style>
</head>
<body>
    <div class="container mt-4" style="max-width: 600px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="index.php?masa=<?php echo $masa_id; ?>" class="btn btn-sm btn-outline-dark">⬅ Menü</a>
            <h4 class="mb-0 fw-bold">🛒 Sepetiniz</h4>
            <span class="badge bg-danger p-2 fs-6">Masa <?php echo $masa_id; ?></span>
        </div>

        <?php if (empty($sepet)): ?>
            <div class="card p-5 text-center cart-card">
                <p class="text-muted">Sepetiniz şu an boş.</p>
                <a href="index.php?masa=<?php echo $masa_id; ?>" class="btn btn-warning fw-bold">Ürün Ekle</a>
            </div>
        <?php else: ?>
            <div class="card cart-card mb-3">
                <ul class="list-group list-group-flush">
                    <?php foreach($sepet as $id => $item): 
                        $ara_toplam = $item['fiyat'] * $item['adet'];
                        $toplam += $ara_toplam;
                    ?>
                        <li class="list-group-item py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong class="d-block"><?php echo $item['ad']; ?></strong>
                                    <small class="text-muted"><?php echo $item['adet']; ?> x <?php echo $item['fiyat']; ?> TL</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-dark mb-1"><?php echo number_format($ara_toplam, 2); ?> TL</div>
                                    <a href="islem.php?sil=<?php echo $id; ?>&masa=<?php echo $masa_id; ?>" class="btn-remove">Kaldır / Azalt</a>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="card-footer d-flex justify-content-between bg-white py-3 border-0">
                    <span class="h5 fw-bold">Toplam:</span>
                    <span class="h5 text-success fw-bold"><?php echo number_format($toplam, 2); ?> TL</span>
                </div>
            </div>

            <a href="odeme.php?masa=<?php echo $masa_id; ?>" class="btn btn-success w-100 py-3 fw-bold shadow rounded-pill fs-5">SİPARİŞİ TAMAMLA</a>
            <div class="text-center mt-3">
                <a href="index.php?masa=<?php echo $masa_id; ?>" class="text-decoration-none text-muted small">← Alışverişe Devam Et</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>