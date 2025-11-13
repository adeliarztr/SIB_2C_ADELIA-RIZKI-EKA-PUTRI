<?php
/**
 * FILE: views/employee_form.php
 * FUNGSI: Form untuk menambah atau mengedit data karyawan
 */

// Memasukkan header
include 'views/header.php';

// Tentukan mode (edit atau create)
$is_edit = isset($employee) && $employee;
$form_title = $is_edit ? 'Edit Data Karyawan' : 'Tambah Karyawan Baru';
$button_text = $is_edit ? 'Update Data' : 'Simpan Data';
$form_action = $is_edit ? "index.php?action=edit&id={$employee['id']}" : "index.php?action=create";
?>

<div class="container">
    <h2><?php echo $form_title; ?></h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-error">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo $form_action; ?>">
        
        <?php if ($is_edit): ?>
            <input type="hidden" name="id" value="<?php echo $employee['id']; ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="first_name" class="form-label">Nama Depan *</label>
            <input type="text"
                   id="first_name"
                   name="first_name"
                   class="form-input"
                   value="<?php echo $is_edit ? htmlspecialchars($employee['first_name']) : ''; ?>"
                   required
                   placeholder="Masukkan nama depan">
        </div>

        <div class="form-group">
            <label for="last_name" class="form-label">Nama Belakang *</label>
            <input type="text"
                   id="last_name"
                   name="last_name"
                   class="form-input"
                   value="<?php echo $is_edit ? htmlspecialchars($employee['last_name']) : ''; ?>"
                   required
                   placeholder="Masukkan nama belakang">
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email *</label>
            <input type="email"
                   id="email"
                   name="email"
                   class="form-input"
                   value="<?php echo $is_edit ? htmlspecialchars($employee['email']) : ''; ?>"
                   required
                   placeholder="contoh@company.com">
        </div>

        <div class="form-group">
            <label for="department" class="form-label">Departemen *</label>
            <select id="department" name="department" class="form-input" required>
                <option value="">Pilih Departemen</option>
                <?php
                $departments = ['IT', 'HR', 'Finance', 'Marketing', 'Operations', 'Sales'];
                foreach ($departments as $dept) {
                    $selected = ($is_edit && $employee['department'] == $dept) ? 'selected' : '';
                    echo "<option value=\"{$dept}\" {$selected}>{$dept}</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="position" class="form-label">Posisi *</label>
            <input type="text"
                   id="position"
                   name="position"
                   class="form-input"
                   value="<?php echo $is_edit ? htmlspecialchars($employee['position']) : ''; ?>"
                   required
                   placeholder="Masukkan posisi/jabatan">
        </div>

        <div class="form-group">
            <label for="salary" class="form-label">Gaji (Rp) *</label>
            <input type="number"
                   id="salary"
                   name="salary"
                   class="form-input"
                   value="<?php echo $is_edit ? $employee['salary'] : ''; ?>"
                   required
                   min="0"
                   step="100000"
                   placeholder="Contoh: 5000000">
            <small style="color: #666;">Isikan angka tanpa titik (contoh: 5000000 untuk 5 juta)</small>
        </div>

        <div class="form-group">
            <label for="hire_date" class="form-label">Tanggal Mulai Bekerja *</label>
            <input type="date"
                   id="hire_date"
                   name="hire_date"
                   class="form-input"
                   value="<?php echo $is_edit ? $employee['hire_date'] : ''; ?>"
                   required>
        </div>

        <div class="form-group" style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">
                <?php echo $button_text; ?>
            </button>
            <a href="index.php?action=list" class="btn" style="background: #6c757d; color: white;">
                Kembali ke Daftar
            </a>
            <?php if ($is_edit): ?>
                <a href="index.php?action=delete&id=<?php echo $employee['id']; ?>"
                   class="btn btn-delete"
                   onclick="return confirm('Yakin ingin menghapus karyawan <?php echo htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']); ?>?')">
                    Hapus Karyawan
                </a>
            <?php endif; ?>
        </div>
    </form>

    <script>
        document.getElementById('salary').addEventListener('input', function(e) {
            // Logika formatting tampilan salary di sini (optional, sebaiknya lakukan di sisi server atau saat menampilkan)
            // Namun, karena kode asli mencoba melakukan formatting, mari kita pertahankan
            let value = e.target.value.replace(/\D/g, '');
            if (value) {
                // Baris ini akan menyebabkan masalah karena toLocaleString menghasilkan string,
                // sementara tipe input adalah number. Lebih aman hanya membiarkannya berupa angka.
                // e.target.value = parseInt(value).toLocaleString('id-ID'); 
            }
        });

        // Validasi form sebelum submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const salaryInput = document.getElementById('salary');
            const hireDate = document.getElementById('hire_date').value;
            const today = new Date().toISOString().split('T')[0];

            // Ambil nilai gaji yang pasti berupa angka
            const salary = parseInt(salaryInput.value.replace(/\D/g, ''));

            // Validasi hire date tidak boleh lebih dari hari ini
            if (hireDate > today) {
                alert('Tanggal mulai bekerja tidak boleh lebih dari hari ini!');
                e.preventDefault();
                return false;
            }

            // Validasi salary minimal
            if (isNaN(salary) || salary < 1000000) {
                alert('Gaji minimal Rp 1.000.000!');
                e.preventDefault();
                return false;
            }
        });
    </script>
</div>
<?php 
// Memasukkan footer
include 'views/footer.php'; 
?>