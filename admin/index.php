<?php 
require_once '../includes/db.php'; 

// 1. Masa Sıfırlama (Ödeme Tamamlandı - Siparişi Arşivle)
if (isset($_GET['masa_sifirla'])) {
    $id = $_GET['masa_sifirla'];
    $guncelle = $db->prepare("UPDATE orders SET is_active = 0, status = 'Tamamlandı' WHERE id = ?");
    $guncelle->execute([$id]);
    header("Location: index.php?durum=sifirlandi");
    exit;
}

// 2. Aktif Siparişleri Çek (is_active = 1 olanlar)
$aktif_siparisler = $db->query("SELECT * FROM orders WHERE is_active = 1 ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masa Yönetimi | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .active-order { border-left: 8px solid #ffc107; background: #fff; }
    </style>
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark mb-4 shadow">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">QR Menü Admin</a>
        <a href="gecmis.php" class="btn btn-outline-info btn-sm">📜 Sipariş Geçmişi</a>
    </div>
</nav>

<div class="container">
    <h3 class="mb-4 text-dark">🔴 Aktif Masalar / Siparişler</h3>
    
    <div class="row">
        <?php if(empty($aktif_siparisler)): ?>
            <div class="col-12"><div class="alert alert-warning text-center">Şu an aktif bir sipariş yok.</div></div>
        <?php else: ?>
            <?php foreach($aktif_siparisler as $siparis): 
                $items = json_decode($siparis['items'], true);
            ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card shadow-sm active-order h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span class="h5 mb-0 text-primary fw-bold"><?php echo $siparis['table_number']; ?></span>
                        <span class="badge bg-dark"><?php echo $siparis['payment_method']; ?></span>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-3">
                            <?php foreach($items as $u): ?>
                                <li class="mb-1 border-bottom pb-1">• <?php echo $u['adet']; ?> x <?php echo $u['ad']; ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="h5 text-success mb-3"><?php echo $siparis['total_price']; ?> TL</div>
                        <a href="index.php?masa_sifirla=<?php echo $siparis['id']; ?>" 
                           class="btn btn-danger w-100 fw-bold" 
                           onclick="return confirm('Ödeme alındı ve masa boşaltıldı mı?')">MASAYI SIFIRLA VE ARŞİVLE</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>