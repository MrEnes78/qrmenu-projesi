<?php 
require_once 'includes/db.php'; 

// 1. Dükkan ayarlarını çek
$sorgu = $db->query("SELECT * FROM settings WHERE id = 1");
$ayar = $sorgu->fetch(PDO::FETCH_ASSOC);

// 2. Dükkan Kapalıysa Kontrolü
if ($ayar['shop_status'] == 0) {
    echo "<!DOCTYPE html><html lang='tr text-center'><head><meta charset='UTF-8'><link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'></head><body class='bg-light'>";
    echo "<div class='container mt-5 text-center'>";
    echo "    <div class='alert alert-danger py-5 shadow-sm'>";
    echo "        <h1 class='display-4'>🏠 " . $ayar['closed_message'] . "</h1>";
    echo "        <p class='lead text-muted'>Lütfen çalışma saatlerimizde tekrar deneyiniz.</p>";
    echo "    </div>";
    echo "</div></body></html>";
    exit; // Kodun devamını çalıştırma
}

// 3. Kategorileri çek
$kategoriler = $db->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Menü Sistemi | Hoş Geldiniz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; }
        .hero-section { background: #343a40; color: white; padding: 40px 0; margin-bottom: 30px; text-align: center; }
        .category-card { 
            border: none; 
            border-radius: 15px; 
            transition: 0.3s; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.1); 
        }
        .category-card:hover { transform: translateY(-5px); box-shadow: 0 8px 15px rgba(0,0,0,0.2); }
        .category-link { text-decoration: none; color: inherit; }
    </style>
</head>
<body>

    <div class="hero-section">
        <div class="container">
            <h1>🍕 Lezzet Durağı QR Menü</h1>
            <p class="lead">Lütfen bir kategori seçiniz</p>
        </div>
    </div>

    <div class="container">
        <div class="row g-4">
            
            <?php foreach($kategoriler as $kat): ?>
            <div class="col-6 col-md-4">
                <a href="kategori.php?id=<?php echo $kat['id']; ?>" class="category-link">
                    <div class="card category-card text-center p-4 h-100">
                        <div class="card-body">
                            <h5 class="card-title fw-bold text-uppercase"><?php echo $kat['name']; ?></h5>
                            <span class="btn btn-sm btn-outline-dark mt-2">Görüntüle</span>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>

        </div>

        <footer class="mt-5 text-center text-muted pb-4">
            <small>&copy; 2024 QR Menü Sistemi - Tüm Hakları Saklıdır.</small>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>