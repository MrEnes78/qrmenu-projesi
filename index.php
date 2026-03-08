<?php 
require_once 'includes/db.php'; 

// Dükkan ayarlarını çek
$sorgu = $db->query("SELECT * FROM settings WHERE id = 1");
$ayar = $sorgu->fetch(PDO::FETCH_ASSOC);

if ($ayar['shop_status'] == 0) {
    // Dükkan Kapalıysa
    echo "<div style='text-align:center; margin-top:100px;'>";
    echo "<h1>🏠 " . $ayar['closed_message'] . "</h1>";
    echo "<p>Lütfen daha sonra tekrar deneyiniz.</p>";
    echo "</div>";
    exit; // Kodun devamını çalıştırma
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>QR Menü Sistemi</title>
</head>
<body>
    <h1>Hoş Geldiniz!</h1>
    <p>Menümüz birazdan burada listelenecek.</p>
</body>
</html>