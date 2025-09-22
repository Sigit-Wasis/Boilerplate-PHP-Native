<?php
require_once _DIR_ . '/../includes/header.php';

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