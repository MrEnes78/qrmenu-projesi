<?php
require_once '../includes/db.php';

// Ürün Silme İşlemi
if (isset($_GET['sil'])) {
    $id = intval($_GET['sil']);
    $db->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
    header("Location: urunler.php?durum=silindi");
    exit;
}

// Fiyat Güncelleme İşlemi
if (isset($_POST['fiyat_guncelle'])) {
    $id = $_POST['urun_id'];
    $yeni_fiyat = $_POST['yeni_fiyat'];
    $db->prepare("UPDATE products SET price = ? WHERE id = ?")->execute([$yeni_fiyat, $id]);
    header("Location: urunler.php?durum=guncellendi");
    exit;
}

$urunler = $db->query("SELECT p.*, c.name as cat_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.category_id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ürün Yönetimi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php">⬅ Panele Dön</a>
        <a href="urun_ekle.php" class="btn btn-success btn-sm">+ Yeni Ürün Ekle</a>
    </div>
</nav>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="mb-4">Menüdeki Ürünler</h4>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Ürün Adı</th>
                        <th>Fiyat (TL)</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($urunler as $u): ?>
                    <tr>
                        <td><span class="badge bg-secondary"><?php echo $u['cat_name']; ?></span></td>
                        <td><?php echo $u['name']; ?></td>
                        <td>
                            <form method="POST" class="d-flex gap-2">
                                <input type="hidden" name="urun_id" value="<?php echo $u['id']; ?>">
                                <input type="number" step="0.01" name="yeni_fiyat" class="form-control form-control-sm" value="<?php echo $u['price']; ?>" style="width: 100px;">
                                <button name="fiyat_guncelle" class="btn btn-primary btn-sm">💾</button>
                            </form>
                        </td>
                        <td>
                            <a href="?sil=<?php echo $u['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bu ürünü silmek istediğine emin misin?')">Sil</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>