<?php
try {
    // Port: 3307, Veritabanı: qrmenu_db
    $db = new PDO("mysql:host=localhost;port=3307;dbname=qrmenu_db;charset=utf8", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Bağlantı hatası: " . $e->getMessage());
}
?>