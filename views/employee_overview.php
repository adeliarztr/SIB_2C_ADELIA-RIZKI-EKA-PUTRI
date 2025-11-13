<?php
include 'views/header.php';
?>
<h2>Ringkasan Karyawan</h2>

<?php
$overview = $employeeModel->getEmployeeOverview();
?>
<div class="dashboard-cards">
    <div class="card">
        <h3>Total Karyawan</h3>
        <div class="number"><?php echo $overview['total_employees']; ?></div>
    </div>
    <div class="card">
        <h3>Total Gaji / Bulan</h3>
        <div class="number">Rp <?php echo number_format($overview['total_salary_per_month'],0,',','.'); ?></div>
    </div>
    <div class="card">
        <h3>Rata-rata Masa Kerja</h3>
        <div class="number"><?php echo $overview['avg_years_service']; ?> tahun</div>
    </div>
</div>

<?php include 'views/footer.php'; ?>
