<?php
session_start();
require_once 'includes/db.php';

if (empty($_SESSION['sepet'])) { header("Location: index.php"); exit; }

$toplam = 0;
foreach($_SESSION['sepet'] as $item) { $toplam += ($item['fiyat'] * $item['adet']); }
$masa_no = isset($_SESSION['masa_no']) ? $_SESSION['masa_no'] : "Masa Bilgisi Yok";

if (isset($_POST['odeme_tamamla'])) {
    $odeme_tipi = $_POST['payment_method'];
    $urunler_json = json_encode($_SESSION['sepet'], JSON_UNESCAPED_UNICODE);

    $ekle = $db->prepare("INSERT INTO orders (table_number, items, total_price, payment_method, is_active) VALUES (?, ?, ?, ?, 1)");
    if ($ekle->execute([$masa_no, $urunler_json, $toplam, $odeme_tipi])) {
        unset($_SESSION['sepet']);
        header("Location: odeme.php?sonuc=basarili");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ödeme Sayfası</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 500px;">
    <?php if(isset($_GET['sonuc'])): ?>
        <div class="card p-5 text-center shadow">
            <h1 class="display-1">✅</h1>
            <h3>Siparişiniz Alındı!</h3>
            <p>Mutfak ekibimiz hazırlıklara başladı. Afiyet olsun!</p>
            <a href="index.php" class="btn btn-primary mt-3">Menüye Geri Dön</a>
        </div>
    <?php else: ?>
        <div class="card shadow border-0">
            <div class="card-header bg-dark text-white p-3 text-center"><h5>💳 Ödeme Bilgileri</h5></div>
            <div class="card-body p-4">
                <form method="POST">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Ödeme Yöntemi Seçin:</label>
                        <div class="form-check p-3 border rounded mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" value="Kart" id="cardRadio" checked onchange="toggleCard(true)">
                            <label class="form-check-label w-100" for="cardRadio">Kredi / Banka Kartı</label>
                        </div>
                        <div class="form-check p-3 border rounded">
                            <input class="form-check-input" type="radio" name="payment_method" value="Nakit" id="cashRadio" onchange="toggleCard(false)">
                            <label class="form-check-label w-100" for="cashRadio">Nakit (Garsona Öde)</label>
                        </div>
                    </div>

                    <div id="cardDetails">
                        <div class="mb-3"><label>Kart Üzerindeki İsim</label><input type="text" class="form-control" placeholder="Ad Soyad"></div>
                        <div class="mb-3"><label>Kart Numarası</label><input type="text" class="form-control" placeholder="0000 0000 0000 0000"></div>
                        <div class="row"><div class="col-6"><label>SKT</label><input type="text" class="form-control" placeholder="AA/YY"></div><div class="col-6"><label>CVC</label><input type="text" class="form-control" placeholder="000"></div></div>
                    </div>

                    <hr class="my-4">
                    <div class="d-flex justify-content-between h5 mb-4"><span>Ödenecek:</span><strong><?php echo $toplam; ?> TL</strong></div>
                    <button type="submit" name="odeme_tamamla" class="btn btn-primary w-100 py-3 fw-bold">SİPARİŞİ VE ÖDEMEYİ ONAYLA</button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>
<script>
    function toggleCard(show) { document.getElementById('cardDetails').style.display = show ? 'block' : 'none'; }
</script>
</body>
</html>