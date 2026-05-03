<?php
session_start();
require_once 'includes/db.php';

$masa_id = isset($_GET['masa']) ? intval($_GET['masa']) : (isset($_SESSION['masa_id']) ? $_SESSION['masa_id'] : 0);
$_SESSION['masa_id'] = $masa_id;

$cat_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$category = $db->prepare("SELECT name FROM categories WHERE id = ?");
$category->execute([$cat_id]);
$cat_name = $category->fetchColumn();

$products = $db->prepare("SELECT * FROM products WHERE category_id = ? AND is_active = 1");
$products->execute([$cat_id]);
$urunler = $products->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $cat_name; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #f8f9fa; padding-bottom: 80px; }
        .product-card { background: #fff; border-radius: 15px; padding: 15px; margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .add-btn { background: #ffc107; border: none; border-radius: 10px; width: 45px; height: 45px; font-weight: bold; font-size: 24px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: #000; transition: 0.2s; }
        .add-btn:active { transform: scale(0.9); }
        .header { background: #fff; padding: 15px; border-bottom: 1px solid #eee; position: sticky; top: 0; z-index: 100; }
    </style>
</head>
<body>

<div class="header d-flex justify-content-between align-items-center mb-3">
    <a href="index.php?masa=<?php echo $masa_id; ?>" class="btn btn-sm btn-outline-dark fw-bold">⬅ Geri</a>
    <h6 class="mb-0 fw-bold"><?php echo $cat_name; ?></h6>
    <span class="badge bg-danger p-2">Masa <?php echo $masa_id; ?></span>
</div>

<div class="container">
    <?php foreach($urunler as $p): ?>
    <div class="product-card shadow-sm border-0">
        <div>
            <div class="fw-bold text-dark fs-5"><?php echo $p['name']; ?></div>
            <small class="text-muted d-block mb-1"><?php echo $p['description']; ?></small>
            <span class="text-success fw-bold fs-5"><?php echo number_format($p['price'], 2); ?> TL</span>
        </div>
        <a href="islem.php?ekle=<?php echo $p['id']; ?>&masa=<?php echo $masa_id; ?>" class="add-btn">+</a>
    </div>
    <?php endforeach; ?>
</div>

<div class="fixed-bottom p-3 bg-white border-top shadow-lg d-flex justify-content-between align-items-center">
    <div class="fw-bold text-muted">Masa <?php echo $masa_id; ?> Seçili</div>
    <a href="sepet.php?masa=<?php echo $masa_id; ?>" class="btn btn-warning btn-lg fw-bold px-4 rounded-pill shadow">Sepetim 🛒</a>
</div>

<script>
    // URL'de eklendi varsa şık bir toast göster
    if (window.location.search.includes('eklendi=1')) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'bottom-end',
            showConfirmButton: false,
            timer: 1500,
            background: '#212529',
            color: '#fff'
        });
        Toast.fire({ icon: 'success', title: 'Sepete eklendi!' });
    }
</script>
</body>
</html>