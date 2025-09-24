<?php

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../config/database.php';

// Ambil semua post

?>

<!-- Main Content -->
<div class="main-content">
     <!-- Profile Header -->
        <div class="profile-header">
            <div class="row align-items-center">
                <div class="col-md-4 text-center position-relative">
                    <img id="profileImage" src="../../public/img/gambar1.jpg" alt="Profile Picture" class="profile-pic">
                    <input type="file" id="uploadProfile" accept="image/*" style="display:none;">
                    <label for="uploadProfile" class="position-absolute bottom-0 start-50 translate-middle-x mb-2" style="cursor:pointer;">
                        <i class="fas fa-camera" style="font-size:20px; color:white; background:#262626; padding:8px; border-radius:50%;"></i>
                    </label>
                </div>
                <div class="col-md-8"> 
                    <div class="d-flex align-items-center mb-3">
                        <h1 class="h4 me-4 mb-0">_dwiirhma</h1>
                        <button class="btn btn-follow me-2">Edit profil</button>
                        <button class="btn btn-message me-2">View archive</button>
                        <i class="fas fa-cog ms-3" style ="font-size: 24px; cursor: pointer;"></i>
                    </div>
                    
                     <div class="profile-stats mb-3">
                        <span><strong class="number">37</strong> posts</span>
                        <span><strong class="number">12.k</strong> followers</span>
                        <span><strong class="number">74</strong> following</span>
                    </div>
                </div>
            </div>
            
            <!-- Story Highlights -->
            <div class="story-highlights">
                <div id="highlightContainer">
                    <!-- Tombol New -->
                    <div class="highlight-item text-center">
                        <div class="highlight-circle" onclick="document.getElementById('uploadHighlight').click();">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="highlight-name">New</div>
                    </div>
                </div>
                <input type="file" id="uploadHighlight" accept="image/*" style="display:none;">
            </div>
        </div>

        <!-- Profile Tabs -->
        <div class="profile-tabs">
            <a href="#" class="profile-tab active"><i class="fas fa-th"></i>Posts</a>
            <a href="#" class="profile-tab"><i class="fas fa-video"></i>Reels</a>
            <a href="#" class="profile-tab"><i class="fas fa-bookmark"></i>Saved</a>
            <a href="#" class="profile-tab"><i class="fas fa-user-tag"></i>Tagged</a>
        </div>

        <!-- Tombol Upload Foto -->
        <div class="d-flex justify-content-end align-items-center mb-3" style="max-width:935px; margin:0 auto; padding:0 20px;">
            <a href="/modules/post/create.php" class="btn btn-primary" style="background:#0095f6; border:none;">
                <i class="fas fa-plus"></i> Upload Foto
            </a>
        </div>

        <!-- Posts Grid -->
        <div class="posts-grid mt-4 pb-5" id="postsGrid">
            <?php if (!empty($posts)): ?>
                <?php foreach ($posts as $post_id): ?>
                    <?php
                    // Ambil gambar untuk post ini
                    $sqlImg = "SELECT i.file_path FROM pv_post_image pi 
                               JOIN image i ON pi.id_image = i.id 
                               WHERE pi.id_post = ?";
                    $stmtImg = $conn->prepare($sqlImg);
                    $stmtImg->bind_param("i", $post_id);
                    $stmtImg->execute();
                    $resultImg = $stmtImg->get_result();
                    $images = $resultImg->fetch_all(MYSQLI_ASSOC);
                    $stmtImg->close();
                    ?>
                    <div class="post-item">
                        <?php if (!empty($images)): ?>
                            <?php foreach ($images as $img): ?>
                                <img src="<?= htmlspecialchars($img['file_path']) ?>" alt="Post Image" style="width:100%;object-fit:cover;">
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="post-placeholder"><i class="fas fa-camera fa-2x"></i></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="post-item"><div class="post-placeholder"><i class="fas fa-camera fa-2x"></i></div></div>
            <?php endif; ?>
        </div>  
</div>

<?php

require_once __DIR__ . '/../../includes/footer.php';

?>