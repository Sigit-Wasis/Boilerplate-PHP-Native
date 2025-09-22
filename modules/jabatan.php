<?php
<<<<<<< HEAD
require_once _DIR_ . '/../includes/header.php';
=======
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../models/Jabatan.php';
>>>>>>> 1e01a3f506a160359ed9cc95849f612d7a575c4a

$jabatan = getJabatan(); // fungsi dari models/Jabatan.php
?>
<div class="container">
    <h2>Data Jabatan</h2>
    <ul>
        <?php foreach ($jabatan as $j): ?>
            <li><?= htmlspecialchars($j['nama_jabatan']); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php
require_once _DIR_ . '/../includes/footer.php';