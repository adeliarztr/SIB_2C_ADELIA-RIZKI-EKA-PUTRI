<?php
include 'views/header.php';
?>
<h2>Statistik Masa Kerja</h2>

<?php
$stats = $employeeModel->getTenureStats();
$rows = $stats->fetchAll(PDO::FETCH_ASSOC);
if (count($rows) > 0):
?>
<table class="data-table">
    <thead>
        <tr><th>Kategori</th><th>Jumlah Karyawan</th></tr>
    </thead>
    <tbody>
    <?php foreach($rows as $r): ?>
        <tr>
            <td><?php echo htmlspecialchars($r['category']); ?></td>
            <td style="text-align:center;"><?php echo $r['employee_count']; ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
    <p>Tidak ada data masa kerja.</p>
<?php endif; ?>

<?php include 'views/footer.php'; ?>
