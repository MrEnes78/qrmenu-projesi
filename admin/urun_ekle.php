<?php
require_once '../includes/db.php';

if (isset($_POST['urun_kaydet'])) {
    $ad = $_POST['name'];
    $cat_id = $_POST['category_id'];
    $fiyat = $_POST['price'];
    $aciklama = $_POST['description'];
    // Görsel eklemek istersen veritabanına 'image' sütunu ekleyip buraya yazabilirsin

    $ekle = $db->prepare("INSERT INTO products (category_id, name, price, description, is_active) VALUES (?, ?, ?, ?, 1)");
    $ekle->execute([$cat_id, $ad, $fiyat, $aciklama]);
    header("Location: urunler.php?durum=eklendi");
    exit;
}

$kategoriler = $db->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
<div class="container" style="max-width: 600px;">
    <div class="card shadow">
        <div class="card-body">
            <h4>Yeni Ürün Ekle</h4>
            <form method="POST">
                <div class="mb-3">
                    <label>Kategori</label>
                    <select name="category_id" class="form-select" required>
                        <?php foreach($kategoriler as $k): ?>
                            <option value="<?php echo $k['id']; ?>"><?php echo $k['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Ürün Adı</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Fiyat (TL)</label>
                    <input type="number" step="0.01" name="price" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Açıklama</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>
                <button name="urun_kaydet" class="btn btn-success w-100">Ürünü Menüye Ekle</button>
            </form>
            <a href="urunler.php" class="d-block text-center mt-3 text-secondary text-decoration-none">Vazgeç</a>
        </div>
    </div>
</div>
</body>
</html>