<?php
session_start();
// Tampilkan header
require_once __DIR__. '/../../includes/header.php';
//call modal post
require_once __DIR__. '/../../models/Post.php';
require_once __DIR__. '/../../models/Highlight.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user'])) {
    header("Location: /login");
    exit;
}
// Ambil semua post
$posts = getAllPosts();

// Ambil highlights user
$highlights = getUserHighlights($_SESSION['user']['id']);

?>
<!-- Main Content -->
    <div class="main-content">
        <!-- Notifikasi -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Profile Header -->
        <div class="profile-header">
            <div class="row align-items-center">
                <div class="col-md-4 text-center position-relative">
                    <img id="profileImage" src="public/img/gambar3.jpg" alt="Profile Picture" class="profile-pic">
                    <input type="file" id="uploadProfile" accept="image/*" style="display:none;">
                    <label for="uploadProfile" class="position-absolute bottom-0 start-50 translate-middle-x mb-2" style="cursor:pointer;">
                        <i class="fas fa-camera" style="font-size:20px; color:white; background:#262626; padding:8px; border-radius:50%;"></i>
                    </label>
                </div>
                <div class="col-md-8">
                    <div class="d-flex align-items-center mb-3">
                        <h1 class="h4 me-4 mb-0"><?= htmlspecialchars($_SESSION['user']['nama_lengkap'] ?? '-') ?></h1>
                        <button class="btn btn-follow me-2">Edit profil</button>
                        <button class="btn btn-message me-2">View archive</button>
                        <i class="fas fa-cog ms-3" style ="font-size: 24px; cursor: pointer;"></i>
                    </div>
                    
                    <div class="profile-stats mb-3">
                        <span><strong class="number">3</strong> posts</span>
                        <span><strong class="number">642</strong> followers</span>
                        <span><strong class="number">384</strong> following</span>
                    </div>
                </div>
            </div>
            
             <!-- Story Highlights -->
            <div class="story-highlights">
                <div id="highlightContainer" class="d-flex gap-3">
                    <!-- Tombol New -->
                    <div class="highlight-item text-center">
                        <div class="highlight-circle" data-bs-toggle="modal" data-bs-target="#addHighlightModal" style="cursor: pointer;">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="highlight-name">New</div>
                    </div>

                    <!-- Tampilkan highlights yang sudah ada -->
                    <?php foreach ($highlights as $highlight): ?>
                        <div class="highlight-item text-center position-relative">
                            <div class="highlight-circle">
                                <img src="/public/img/highlights/<?= htmlspecialchars($highlight['image']) ?>" alt="<?= htmlspecialchars($highlight['name']) ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                            </div>
                            <div class="highlight-name"><?= htmlspecialchars($highlight['name']) ?></div>
                            <!-- Tombol Edit & Delete -->
                            <div class="highlight-actions position-absolute" style="top: -5px; right: -5px;">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-dark rounded-circle" type="button" data-bs-toggle="dropdown" style="width: 25px; height: 25px; padding: 0; font-size: 12px;">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editHighlightModal<?= $highlight['id'] ?>">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#deleteHighlightModal<?= $highlight['id'] ?>">
                                                <i class="fas fa-trash"></i> Hapus
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Edit Highlight -->
                        <div class="modal fade" id="editHighlightModal<?= $highlight['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="/highlight?action=update" method="POST">
                                        <input type="hidden" name="highlight_id" value="<?= $highlight['id'] ?>">
                                        <div class="modal-header">
                                            <h5 class="modal-title" style="color: #0b0505ff !important;">Edit Nama Highlight</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="new_name" style="color: #0b0505ff !important;" class="form-label">Nama Baru</label>
                                                <input type="text" class="form-control" name="new_name" value="<?= htmlspecialchars($highlight['name']) ?>" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Delete Highlight -->
                        <div class="modal fade" id="deleteHighlightModal<?= $highlight['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="/highlight?action=delete" method="POST">
                                        <input type="hidden" name="highlight_id" value="<?= $highlight['id'] ?>">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-danger">Hapus Highlight</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p style="color: #0b0505ff !important;">Apakah Anda yakin ingin menghapus highlight "<strong><?= htmlspecialchars($highlight['name']) ?></strong>"?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Modal Tambah Highlight -->
        <div class="modal fade" id="addHighlightModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="/highlight/store" method="POST" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: #0b0505ff !important;">Tambah Highlight Baru</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="highlight_name" style="color: #0b0505ff !important;" class="form-label">Nama Highlight</label>
                                <input type="text" class="form-control" name="name" maxlength="15" required>
                                <div class="form-text">Maksimal 15 karakter</div>
                            </div>
                            <div class="mb-3">
                                <label for="highlight_image" style="color: #0b0505ff !important;" class="form-label">Upload Gambar</label>
                                <input type="file" class="form-control" name="image" accept="image/*" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
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
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Launch demo modal
            </button>
        </div>

        <!-- Modal Post -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="/post/store" method="POST" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: #0b0505ff !important;" id="exampleModalLabel">Tambah Post Baru</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="caption" style="color: #0b0505ff !important;" class="form-label">Caption</label>
                                <input type="text" class="form-control" id="caption" name="caption" required>
                            </div>
                            <div class="mb-3">
                                <label for="images" style="color: #0b0505ff !important;" class="form-label">Upload Gambar</label>
                                <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                                <div class="form-text">Bisa pilih lebih dari satu gambar.</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="posts-grid mt-4 pb-5" id="postsGrid">
            <?php if (!empty($posts)): ?>
                <?php foreach ($posts as $post): ?>
                    <div class="post-item">
                        <?php if (!empty($post['image'])): ?>
                            <img src="<?= '/public/img/' . htmlspecialchars($post['image']) ?>" alt="Post Image">
                        <?php else: ?>
                            <div class="post-placeholder">
                                <i class="fas fa-camera fa-2x"></i><br>
                                Tidak ada gambar
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Belum ada postingan.</p>
            <?php endif; ?>
        </div>
    </div>

<?php

require_once __DIR__. '/../../includes/footer.php';

?>