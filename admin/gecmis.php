<?php
require_once '../includes/db.php';
$gecmis = $db->query("SELECT * FROM orders WHERE is_active=0 ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$ciro = $db->query("SELECT SUM(total_price) FROM orders WHERE is_active=0")->fetchColumn() ?: 0;
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
    <div class="container card shadow p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Sipariş Arşivi</h3>
            <h4 class="text-success">Toplam Ciro: <?php echo number_format($ciro, 2); ?> TL</h4>
        </div>
        <table class="table table-striped border">
            <thead class="table-dark">
                <tr><th>Tarih</th><th>Masa</th><th>Ürünler</th><th>Tutar</th></tr>
            </thead>
            <tbody>
                <?php foreach($gecmis as $s): $items=json_decode($s['items'],true); ?>
                <tr>
                    <td><?php echo date('d.m.Y H:i', strtotime($s['created_at'])); ?></td>
                    <td><?php echo $s['table_number']; ?></td>
                    <td><?php foreach($items as $i) echo $i['ad']." (".$i['adet']."), "; ?></td>
                    <td class="fw-bold"><?php echo $s['total_price']; ?> TL</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="index.php" class="btn btn-dark w-100">Geri Dön</a>
    </div>
</body>
</html>