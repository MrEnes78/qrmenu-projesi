<?php
session_start();
require_once 'includes/db.php';

// Sepete Ekleme
if (isset($_GET['ekle'])) {
    $id = intval($_GET['ekle']);
    
    // Ürünü kontrol et
    $sorgu = $db->prepare("SELECT * FROM products WHERE id = ?");
    $sorgu->execute([$id]);
    $urun = $sorgu->fetch(PDO::FETCH_ASSOC);

    if ($urun) {
        // Sepet dizisi yoksa oluştur
        if (!isset($_SESSION['sepet'])) {
            $_SESSION['sepet'] = [];
        }

        // Ürün zaten sepette varsa adedini artır
        if (isset($_SESSION['sepet'][$id])) {
            $_SESSION['sepet'][$id]['adet'] += 1;
        } else {
            $_SESSION['sepet'][$id] = [
                'ad' => $urun['name'],
                'fiyat' => $urun['price'],
                'adet' => 1
            ];
        }
    }
    header("Location: " . $_SERVER['HTTP_REFERER']); // Geldiği sayfaya geri gönder
}

// Sepeti Boşalt
if (isset($_GET['bosalt'])) {
    unset($_SESSION['sepet']);
    header("Location: index.php");
}
?>