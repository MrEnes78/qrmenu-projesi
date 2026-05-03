<?php
session_start();
require_once 'includes/db.php';

$masa_id = isset($_GET['masa']) ? intval($_GET['masa']) : 0;

// --- GARSON ÇAĞRISINI SİLME (Admin Paneli İçin) ---
if (isset($_GET['cagri_sil'])) {
    $cagri_id = intval($_GET['cagri_sil']);
    
    $sil = $db->prepare("DELETE FROM waiter_calls WHERE id = ?");
    $sil->execute([$cagri_id]);

    // Admin paneline geri dön (Dosya yolun admin/index.php ise ona göre yönlendirir)
    // Eğer admin panelin ana dizindeyse "admin.php" yapabilirsin
    header("Location: admin/index.php"); 
    exit;
}

// --- ÜRÜN EKLEME ---
if (isset($_GET['ekle'])) {
    $urun_id = intval($_GET['ekle']);
    $urun = $db->prepare("SELECT * FROM products WHERE id = ?");
    $urun->execute([$urun_id]);
    $u = $urun->fetch(PDO::FETCH_ASSOC);

    if ($u) {
        if (!isset($_SESSION['sepet'])) { $_SESSION['sepet'] = []; }
        
        $_SESSION['sepet'][$urun_id] = [
            'ad' => $u['name'],
            'fiyat' => $u['price'],
            'adet' => ($_SESSION['sepet'][$urun_id]['adet'] ?? 0) + 1
        ];
        header("Location: kategori.php?id=" . $u['category_id'] . "&masa=" . $masa_id . "&eklendi=1");
    } else {
        header("Location: index.php?masa=" . $masa_id);
    }
    exit;
}

// --- ÜRÜN SİLME/AZALTMA ---
if (isset($_GET['sil'])) {
    $urun_id = intval($_GET['sil']);
    if (isset($_SESSION['sepet'][$urun_id])) {
        if ($_SESSION['sepet'][$urun_id]['adet'] > 1) {
            $_SESSION['sepet'][$urun_id]['adet']--;
        } else {
            unset($_SESSION['sepet'][$urun_id]);
        }
    }
    header("Location: sepet.php?masa=" . $masa_id);
    exit;
}

// --- GARSON ÇAĞIRMA (Müşteri İçin) ---
if (isset($_GET['garson_cagir'])) {
    if ($masa_id > 0) {
        $db->prepare("INSERT INTO waiter_calls (table_number) VALUES (?)")->execute(["Masa " . $masa_id]);
    }
    header("Location: index.php?masa=" . $masa_id . "&durum=garson_yolda");
    exit;
}

// Eğer hiçbir şeye girmezse ana sayfaya at
header("Location: index.php");
exit;
?>