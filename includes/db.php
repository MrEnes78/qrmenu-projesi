<?php
$host = 'localhost:3307'; // Portu buraya ekledik!
$dbname = 'qrmenu_db';
$user = 'root';
$pass = '';

try {
    // Port bilgisiyle beraber bağlantı dizesi
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    die("Veritabanı Bağlantı Hatası: " . $e->getMessage());
}
?>