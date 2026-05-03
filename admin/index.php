<?php
require_once '../includes/db.php';

// Siparişi Kapat İşlemi
if(isset($_GET['kapat'])){
    $db->prepare("UPDATE orders SET is_active=0 WHERE id=?")->execute([$_GET['kapat']]);
    header("Location: index.php");
    exit;
}

$siparisler = $db->query("SELECT * FROM orders WHERE is_active=1 ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$cagrilar = $db->query("SELECT * FROM waiter_calls ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

$toplam_bildirim = count($siparisler) + count($cagrilar);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Canlı Sipariş Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-dark { background-color: #000 !important; }
        .payment-badge { font-size: 0.75rem; padding: 4px 8px; border-radius: 4px; }
        .card { border: none; border-radius: 12px; }
        .alert-danger { border-radius: 10px; border: none; }
    </style>
</head>
<body class="bg-dark text-white">

<audio id="notifSound"><source src="bildirim.mp3" type="audio/mpeg"></audio>
<audio id="doneSound"><source src="onay.mp3" type="audio/mpeg"></audio>

<nav class="navbar navbar-expand-lg navbar-dark shadow mb-4">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-warning" href="index.php">QR CANLI TAKİP</a>
        <div class="d-flex gap-2 align-items-center">
            <span class="badge bg-danger me-2 p-2">Aktif Bildirim: <?php echo $toplam_bildirim; ?></span>
            <a href="kategoriler.php" class="btn btn-sm btn-warning fw-bold text-dark">📁 Kategoriler</a>
            <a href="urunler.php" class="btn btn-sm btn-info fw-bold">🍔 Ürünler</a>
            <a href="gecmis.php" class="btn btn-sm btn-success fw-bold">📜 Ciro</a>
        </div>
    </div>
</nav>

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-md-3 mb-4">
            <h5 class="text-danger fw-bold border-bottom pb-2 mb-3">🔔 Çağrılar</h5>
            <?php if(empty($cagrilar)): ?>
                <p class="text-muted small">Şu an çağrı yok.</p>
            <?php endif; ?>
            <?php foreach($cagrilar as $c): ?>
            <div class="alert alert-danger d-flex justify-content-between align-items-center p-2 mb-2 shadow-sm">
                <b><?php echo $c['table_number']; ?></b>
                <a href="../islem.php?cagri_sil=<?php echo $c['id']; ?>" class="btn btn-sm btn-success rounded-circle">✓</a>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="col-md-9">
            <h5 class="text-primary fw-bold border-bottom pb-2 mb-3">🍱 Yeni Siparişler</h5>
            <div class="row">
                <?php if(empty($siparisler)): ?>
                    <div class="col-12"><p class="text-muted">Mutfak boş, sipariş bekleniyor...</p></div>
                <?php endif; ?>
                <?php foreach($siparisler as $s): $items=json_decode($s['items'],true); ?>
                <div class="col-md-4 mb-3">
                    <div class="card text-dark shadow-lg">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="fw-bold mb-0"><?php echo $s['table_number']; ?></h5>
                                <span class="payment-badge <?php echo ($s['payment_method'] == 'Kart') ? 'bg-info text-white' : 'bg-warning text-dark'; ?>">
                                    <?php echo ($s['payment_method'] == 'Kart') ? '💳 Kart' : '💵 Nakit'; ?>
                                </span>
                            </div>
                            <hr>
                            <ul class="list-unstyled mb-3" style="min-height: 60px;">
                                <?php foreach($items as $i) echo "<li><strong>".$i['adet']."x</strong> ".$i['ad']."</li>"; ?>
                            </ul>
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                                <b class="text-success fs-5"><?php echo $s['total_price']; ?> TL</b>
                                <button onclick="kapatSesiCal(<?php echo $s['id']; ?>)" class="btn btn-dark btn-sm px-3">Kapat</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
    // 1. ANLIK GÜNCELLEME VE YENİ SİPARİŞ SESİ
    let currentCount = <?php echo $toplam_bildirim; ?>;
    let lastCount = localStorage.getItem('lastCount') || 0;

    // Eğer yeni bir bildirim (sipariş veya çağrı) geldiyse ses çal
    if (currentCount > lastCount) {
        let playPromise = document.getElementById('notifSound').play();
        if (playPromise !== undefined) {
            playPromise.catch(error => {
                console.log("Tarayıcı engeli: Ses çalmak için sayfaya bir kez tıklayın.");
            });
        }
    }
    localStorage.setItem('lastCount', currentCount);

    // Sayfayı her 15 saniyede bir yenile
    setInterval(function(){ window.location.reload(); }, 15000);

    // 2. KAPAT BUTONU SESİ (onay.mp3)
    function kapatSesiCal(orderId) {
        let sound = document.getElementById('doneSound');
        sound.play(); 
        
        // Sesin duyulması için yarım saniye bekle ve yönlendir
        setTimeout(function(){
            window.location.href = "?kapat=" + orderId;
        }, 500);
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>