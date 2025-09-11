<?php
require_once __DIR__ . '/../includes/header.php';

$users = getUsers(); // fungsi dari models/User.php
?>
<div class="container">
    <h2>Data User</h2>
    <ul>
        <?php foreach ($users as $user): ?>
            <li><?= htmlspecialchars($user['name']); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php
require_once __DIR__ . '/../includes/footer.php';
