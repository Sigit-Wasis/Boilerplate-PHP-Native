// Tambahkan JavaScript jika diperlukan
console.log('Aplikasi berjalan');
// Like/Unlike via AJAX
fetch('likes.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({
        action: 'toggle',
        post_id: 1,
        user_id: 123
    })
})
.then(response => response.json())
.then(data => {
    console.log('Likes count:', data.likes_count);
    console.log('User has liked:', data.user_has_liked);
});

// Upload foto profil
const uploadInput = document.getElementById("uploadProfile");
const profileImage = document.getElementById("profileImage");

if (uploadInput) {
    uploadInput.addEventListener("change", function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                profileImage.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
}

// Upload highlight
const highlightInput = document.getElementById("uploadHighlight");
const highlightContainer = document.getElementById("highlightContainer");

if (highlightInput) {
    highlightInput.addEventListener("change", function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const highlightName = prompt("Masukkan nama highlight:", "Highlight");
                const finalName = highlightName ? highlightName : "Highlight";

                const newHighlight = document.createElement("div");
                newHighlight.classList.add("highlight-item");
                newHighlight.innerHTML = `
                    <div class="delete-highlight">&times;</div>
                    <img src="${e.target.result}" class="highlight-pic">
                    <div class="highlight-name">${finalName}</div>
                `;

                newHighlight.querySelector(".delete-highlight").addEventListener("click", function() {
                    newHighlight.remove();
                });

                highlightContainer.appendChild(newHighlight);
            }
            reader.readAsDataURL(file);
        }
    });
}

// Upload foto postingan
const uploadPostInput = document.getElementById("uploadPost");
const postsGrid = document.getElementById("postsGrid");

if (uploadPostInput) {
     uploadPostInput.addEventListener("change", function() {
      const file = this.files[0];  
    if (file) {
       const reader = new FileReader();
        reader.onload = function(e) {
        const newPost = document.createElement("div");
         newPost.classList.add("post-item");
         newPost.innerHTML = '<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">; 
         postsGrid.prepend(newPost);
        }
         reader.readAsDataURL(file);
      }
     } );

    
    }