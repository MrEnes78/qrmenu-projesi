<?php
require_once '../includes/db.php';

// Kategori Silme
if (isset($_GET['sil'])) {
    $id = intval($_GET['sil']);
    // Önce bu kategoriye ait ürün var mı kontrol etsek iyi olur ama direkt siliyoruz
    $db->prepare("DELETE FROM categories WHERE id = ?")->execute([$id]);
    header("Location: kategoriler.php?durum=silindi");
    exit;
}

// Yeni Kategori Ekleme
if (isset($_POST['kat_ekle'])) {
    $ad = $_POST['kat_adi'];
    if(!empty($ad)){
        $db->prepare("INSERT INTO categories (name) VALUES (?)")->execute([$ad]);
        header("Location: kategoriler.php?durum=eklendi");
        exit;
    }
}

$kategoriler = $db->query("SELECT * FROM categories ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kategori Yönetimi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php">⬅ Panele Dön</a>
        <span class="navbar-text text-white">Kategori Yönetimi</span>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Yeni Kategori Ekle</h5>
                    <form method="POST">
                        <div class="mb-3">
                            <input type="text" name="kat_adi" class="form-control" placeholder="Kategori Adı (Örn: Çorbalar)" required>
                        </div>
                        <button name="kat_ekle" class="btn btn-primary w-100">Ekle</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3">Mevcut Kategoriler</h5>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kategori Adı</th>
                                <th class="text-end">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($kategoriler as $k): ?>
                            <tr>
                                <td>#<?php echo $k['id']; ?></td>
                                <td><strong><?php echo $k['name']; ?></strong></td>
                                <td class="text-end">
                                    <a href="?sil=<?php echo $k['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Bu kategoriyi silersen içindeki ürünler menüde görünmeyebilir. Emin misin?')">Sil</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>