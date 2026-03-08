<?php 
session_start(); // Sepet sayısını göstermek için session başlattık
require_once 'includes/db.php'; 

// 1. URL'den kategori ID'sini al
$kat_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 2. Kategori adını çek
$kat_sorgu = $db->prepare("SELECT name FROM categories WHERE id = ?");
$kat_sorgu->execute([$kat_id]);
$kategori = $kat_sorgu->fetch(PDO::FETCH_ASSOC);

if (!$kategori) {
    header("Location: index.php");
    exit;
}

// 3. Bu kategoriye ait ürünleri çek
$urunler = $db->prepare("SELECT * FROM products WHERE category_id = ? AND is_active = 1");
$urunler->execute([$kat_id]);
$urun_listesi = $urunler->fetchAll(PDO::FETCH_ASSOC);

// Sepette kaç ürün var? (Üstteki buton için)
$sepet_adet = 0;
if(isset($_SESSION['sepet'])) {
    foreach($_SESSION['sepet'] as $item) {
        $sepet_adet += $item['adet'];
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $kategori['name']; ?> | QR Menü</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .product-card { 
            border: none; border-radius: 15px; 
            box-shadow: 0 4px 10px rgba(0,0,0,0.05); 
            margin-bottom: 20px;
        }
        .price-badge { 
            background-color: #2ecc71; color: white; 
            padding: 5px 15px; border-radius: 50px; font-weight: bold; 
        }
        .sticky-header {
            position: sticky; top: 0; z-index: 1000;
            background: white; padding: 15px 0; border-bottom: 1px solid #eee;
        }
    </style>
</head>
<body>

<div class="sticky-header shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="index.php" class="btn btn-outline-dark btn-sm">⬅ Geri</a>
        <h5 class="mb-0 fw-bold"><?php echo $kategori['name']; ?></h5>
        <a href="sepet.php" class="btn btn-warning btn-sm position-relative">
            🛒 Sepet
            <?php if($sepet_adet > 0): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <?php echo $sepet_adet; ?>
                </span>
            <?php endif; ?>
        </a>
    </div>
</div>

<div class="container mt-4">
    <div class="row">
        <?php if (count($urun_listesi) > 0): ?>
            <?php foreach($urun_listesi as $urun): ?>
                <div class="col-12 col-md-6">
                    <div class="card product-card p-2">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div style="max-width: 65%;">
                                <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($urun['name']); ?></h6>
                                <p class="text-muted small mb-0"><?php echo htmlspecialchars($urun['description']); ?></p>
                            </div>
                            <div class="text-end">
                                <div class="price-badge mb-2"><?php echo number_format($urun['price'], 2, ',', '.'); ?> TL</div>
                                <a href="sepet_islem.php?ekle=<?php echo $urun['id']; ?>" class="btn btn-dark btn-sm rounded-pill w-100">Ekle +</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted">Bu kategoride henüz ürün bulunmuyor.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>