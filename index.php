<?php
session_start();
require_once 'includes/db.php';

// QR'dan gelen masa numarasını tutar
if(isset($_GET['masa'])) { 
    $_SESSION['masa_no'] = "Masa " . $_GET['masa']; 
    $_SESSION['masa_id'] = $_GET['masa']; // ID'yi ayrıca tutmak işlemlerde kolaylık sağlar
}
$masa_no = $_SESSION['masa_no'] ?? "Masa Seçilmedi";
$masa_id = $_SESSION['masa_id'] ?? 0;

$categories = $db->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>QR Menü Sistemi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }
        /* Üst Header Tasarımı */
        .menu-header {
            background: linear-gradient(135deg, #212529 0%, #343a40 100%);
            color: white;
            padding: 25px 15px;
            border-bottom-left-radius: 30px;
            border-bottom-right-radius: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            margin-bottom: 25px;
        }
        /* Kategori Kartları */
        .category-card {
            background: #ffffff;
            border: none;
            border-radius: 18px;
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            transition: all 0.2s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            text-decoration: none !important;
            color: #2d3436 !important;
            font-weight: 700;
            font-size: 1.1rem;
            border: 1px solid rgba(0,0,0,0.03);
        }
        .category-card:active {
            transform: scale(0.92);
            background-color: #f1f1f1;
        }
        /* Alt Navigasyon */
        .fixed-bottom {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            border-top-left-radius: 25px;
            border-top-right-radius: 25px;
            padding: 15px 20px !important;
        }
        .nav-btn {
            border-radius: 15px;
            padding: 12px 25px;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-title {
            color: #636e72;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-left: 5px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body class="pb-5">

<div class="menu-header">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-0 fw-bold" style="letter-spacing: -1px;">🍴 QR Menü</h2>
            <small class="opacity-75">Lezzete hoş geldiniz</small>
        </div>
        <div class="text-end">
            <span class="badge bg-danger px-3 py-2 fs-6 shadow-sm rounded-pill">
                ● <?php echo $masa_no; ?>
            </span>
        </div>
    </div>
</div>

<div class="container">
    <h6 class="section-title fw-bold">Menü Kategorileri</h6>
    
    <div class="row g-3">
        <?php foreach($categories as $c): ?>
        <div class="col-6">
            <a href="kategori.php?id=<?php echo $c['id']; ?>&masa=<?php echo $masa_id; ?>" class="category-card shadow-sm">
                <div>
                    <div class="mb-1" style="font-size: 1.5rem;">🍽️</div>
                    <?php echo $c['name']; ?>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="fixed-bottom d-flex justify-content-between align-items-center shadow-lg border-top">
    <a href="sepet.php?masa=<?php echo $masa_id; ?>" class="btn btn-warning nav-btn shadow">
        <span>🛒</span> SEPETİM
    </a>
    <a href="islem.php?garson_cagir=1&masa=<?php echo $masa_id; ?>" class="btn btn-danger nav-btn shadow">
        <span>🔔</span> GARSON
    </a>
</div>

<?php if(isset($_GET['durum']) && $_GET['durum'] == 'garson_yolda'): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Garson Yolda!',
            text: 'Talebiniz alındı, en kısa sürede masanızda olacağız.',
            confirmButtonColor: '#d33',
            timer: 3000
        });
    </script>
<?php endif; ?>

</body>
</html>