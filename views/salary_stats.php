<?php
include 'views/header.php';
?>
<h2>Statistik Gaji per Departemen</h2>

<?php
$stats = $employeeModel->getSalaryStats();
$rows = $stats->fetchAll(PDO::FETCH_ASSOC);
if (count($rows) > 0):
?>
<table class="data-table">
    <thead>
        <tr><th>Departemen</th><th>Rata-rata Gaji</th><th>Gaji Tertinggi</th><th>Gaji Terendah</th></tr>
    </thead>
    <tbody>
    <?php foreach($rows as $r): ?>
        <tr>
            <td><?php echo htmlspecialchars($r['department']); ?></td>
            <td>Rp <?php echo number_format($r['avg_salary'],0,',','.'); ?></td>
            <td>Rp <?php echo number_format($r['max_salary'],0,',','.'); ?></td>
            <td>Rp <?php echo number_format($r['min_salary'],0,',','.'); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
    <p>Tidak ada data gaji.</p>
<?php endif; ?>

<?php include 'views/footer.php'; ?>
