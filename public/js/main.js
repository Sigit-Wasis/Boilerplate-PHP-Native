// Upload foto postingan
const uploadPostInput = document.getElementById("uploadPost");
const postsGrid = document.getElementById("postsGrid");

if (uploadPostInput) {
    uploadPostInput.addEventListener("change", function () {
        const files = this.files; // ambil semua file

        // kosongkan grid sebelum render baru
        postsGrid.innerHTML = "";

        Array.from(files).forEach(file => {
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const newPost = document.createElement("div");
                    newPost.classList.add("post-item");
                    newPost.innerHTML =
                        `<img src="${e.target.result}" 
                              style="width:150px;height:150px;object-fit:cover;border-radius:8px;margin:5px;">`;
                    postsGrid.appendChild(newPost);
                };
                reader.readAsDataURL(file);
            }
        });
    });
}
