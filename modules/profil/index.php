<?php

require_once __DIR__ . '/../../includes/header.php';

?>

<!-- Main Content -->
<div class="main-content">
     <!-- Profile Header -->
        <div class="profile-header">
            <div class="row align-items-center">
                <div class="col-md-4 text-center position-relative">
                    <img id="profileImage" src="public/img/gambar1.jpg" alt="Profile Picture" class="profile-pic">
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
                        <span><strong class="number">121</strong> followers</span>
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
           <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
  Launch demo modal
</button>
            <input type="file" id="uploadPost" accept="image/*" style="display:none;">
        </div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="/post/store" method="POST" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" style="color: #0b0505ff !important;" id="exampleModalLabel">Tambah Post Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <!-- Input Caption -->
          <div class="mb-3">
            <label for="caption"style="color: #0b0505ff !important;" class="form-label">Caption</label>
            <input type="text" class="form-control" id="caption" name="caption" required>
          </div>

          <!-- Input File -->
          <div class="mb-3">
            <label for="images"style="color: #0b0505ff !important;" class="form-label">Upload Gambar</label>
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

        <!-- Posts Grid -->
        <div class="posts-grid mt-4 pb-5" id="postsGrid">
            <div class="post-item"><div class="post-placeholder"><i class="fas fa-camera fa-2x"></i></div></div>
            <div class="post-item"><div class="post-placeholder"><i class="fas fa-camera fa-2x"></i></div></div>
            <div class="post-item"><div class="post-placeholder"><i class="fas fa-camera fa-2x"></i></div></div>
        </div>  
</div>

<?php

require_once __DIR__. '/../../includes/footer.php';

?>