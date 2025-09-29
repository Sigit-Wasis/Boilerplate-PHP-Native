// modules/instagram/instagram.js

document.addEventListener('DOMContentLoaded', function() {
    initializeInstagram();
});

function initializeInstagram() {
    initializeNavigation();
    initializeStories();
    initializeFollowButtons();
    initializePostActions();
    initializeProfilePicture();
    initializeModals();
}

// Navigation functionality
function initializeNavigation() {
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            // Skip if it's a modal trigger
            if (!link.getAttribute('data-bs-toggle')) {
                e.preventDefault();
                
                // Get target section
                const target = link.getAttribute('data-target');
                
                // Remove active class from all nav links
                navLinks.forEach(l => l.classList.remove('active'));
                // Add active class to clicked link
                link.classList.add('active');
                
                // Hide all sections
                document.querySelectorAll('.section').forEach(section => {
                    section.classList.remove('active');
                });
                
                // Show target section
                if (target && document.getElementById(target)) {
                    document.getElementById(target).classList.add('active');
                }
            }
        });
    });
}

// Story interactions
function initializeStories() {
    const stories = document.querySelectorAll('.story');
    
    stories.forEach(story => {
        story.addEventListener('click', () => {
            // Add click animation
            story.style.opacity = '0.7';
            story.style.transform = 'scale(0.95)';
            
            setTimeout(() => {
                story.style.opacity = '1';
                story.style.transform = 'scale(1)';
            }, 200);
            
            // Here you could add story viewing functionality
            console.log('Story clicked:', story.querySelector('.story-username').textContent);
        });
    });
}

// Follow button interactions
function initializeFollowButtons() {
    const followButtons = document.querySelectorAll('.follow-btn');
    
    followButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            if (btn.textContent.trim() === 'Ikuti') {
                btn.textContent = 'Mengikuti';
                btn.style.color = '#8e8e8e';
                btn.classList.add('following');
                
                // Show notification
                showNotification('Mulai mengikuti pengguna');
            } else {
                btn.textContent = 'Ikuti';
                btn.style.color = '#0095f6';
                btn.classList.remove('following');
                
                showNotification('Berhenti mengikuti pengguna');
            }
        });
    });
}

// Post action interactions
function initializePostActions() {
    const postActions = document.querySelectorAll('.post-action');
    
    postActions.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            
            const action = btn.textContent.trim();
            
            switch(action) {
                case '❤️':
                    toggleLike(btn);
                    break;
                case '🤍':
                    toggleLike(btn);
                    break;
                case '💬':
                    openComments(btn);
                    break;
                case '📤':
                    sharePost(btn);
                    break;
                case '🔖':
                    toggleSave(btn);
                    break;
                case '📋':
                    toggleSave(btn);
                    break;
            }
        });
    });
}

function toggleLike(btn) {
    if (btn.textContent === '❤️') {
        btn.textContent = '🤍';
        btn.style.color = '#ed4956';
        showNotification('Postingan disukai');
        
        // Update like count
        const likesElement = btn.closest('.feed-post').querySelector('.post-likes');
        if (likesElement) {
            const currentLikes = parseInt(likesElement.textContent.match(/\d+/)[0]);
            likesElement.textContent = `${currentLikes + 1} suka`;
        }
    } else {
        btn.textContent = '❤️';
        btn.style.color = 'white';
        showNotification('Batal menyukai postingan');
        
        // Update like count
        const likesElement = btn.closest('.feed-post').querySelector('.post-likes');
        if (likesElement) {
            const currentLikes = parseInt(likesElement.textContent.match(/\d+/)[0]);
            likesElement.textContent = `${Math.max(0, currentLikes - 1)} suka`;
        }
    }
}

function toggleSave(btn) {
    if (btn.textContent === '🔖') {
        btn.textContent = '📋';
        showNotification('Postingan disimpan');
    } else {
        btn.textContent = '🔖';
        showNotification('Postingan dihapus dari simpanan');
    }
}

function openComments(btn) {
    showNotification('Membuka komentar...');
    // Here you would implement comment modal or navigation
    console.log('Opening comments for post');
}

function sharePost(btn) {
    if (navigator.share) {
        navigator.share({
            title: 'Instagram Post',
            text: 'Check out this post!',
            url: window.location.href
        }).then(() => {
            showNotification('Postingan dibagikan');
        }).catch(console.error);
    } else {
        // Fallback for browsers that don't support Web Share API
        navigator.clipboard.writeText(window.location.href).then(() => {
            showNotification('Link disalin ke clipboard');
        }).catch(() => {
            showNotification('Gagal menyalin link');
        });
    }
}

// Profile picture upload
function initializeProfilePicture() {
    const profilePicInput = document.getElementById('profilePicInput');
    const uploadProfile = document.getElementById('uploadProfile');
    
    // Handle sidebar profile picture
    if (profilePicInput) {
        profilePicInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    const profileAvatar = document.getElementById('profileAvatar');
                    if (profileAvatar) {
                        profileAvatar.src = ev.target.result;
                    }
                    showNotification('Foto profil diperbarui');
                }
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Add to the existing <script> section

// Highlights data
let currentHighlightId = null;
let currentHighlightStoryIndex = 0;

// Open highlight modal
function openHighlight(highlightId) {
    currentHighlightId = highlightId;
    currentHighlightStoryIndex = 0;
    
    const modal = document.getElementById('highlightModal');
    modal.classList.add('active');
    
    showHighlightStory();
    document.addEventListener('keydown', handleHighlightKeyPress);
}

// Close highlight modal
function closeHighlight() {
    const modal = document.getElementById('highlightModal');
    modal.classList.remove('active');
    
    clearTimeout(storyTimer);
    clearInterval(progressTimer);
    document.removeEventListener('keydown', handleHighlightKeyPress);
}

// Show highlight story
function showHighlightStory() {
    const user = storiesData[0]; // Assuming highlights are only for current user
    const highlight = user.highlights.find(h => h.id === currentHighlightId);
    
    if (!highlight || !highlight.stories[currentHighlightStoryIndex]) return;
    
    const story = highlight.stories[currentHighlightStoryIndex];
    
    // Update highlight content
    document.getElementById('highlightUserAvatar').src = user.avatar;
    document.getElementById('highlightTitle').textContent = highlight.title;
    document.getElementById('highlightTimestamp').textContent = story.timestamp;
    document.getElementById('highlightImage').src = story.image;
    
    // Create progress bars
    createHighlightProgressBars();
    
    // Start story timer
    startStoryProgress();
}

// Create progress bars for highlights
function createHighlightProgressBars() {
    const user = storiesData[0];
    const highlight = user.highlights.find(h => h.id === currentHighlightId);
    const progressContainer = document.querySelector('#highlightModal .story-progress-bars');
    progressContainer.innerHTML = '';
    
    highlight.stories.forEach((_, index) => {
        const progressBar = document.createElement('div');
        progressBar.className = 'story-progress-bar';
        
        const progressFill = document.createElement('div');
        progressFill.className = 'story-progress-fill';
        
        if (index < currentHighlightStoryIndex) {
            progressFill.style.width = '100%';
        } else if (index === currentHighlightStoryIndex) {
            progressFill.style.width = '0%';
        } else {
            progressFill.style.width = '0%';
        }
        
        progressBar.appendChild(progressFill);
        progressContainer.appendChild(progressBar);
    });
}

// Navigate to next highlight story
function nextHighlightStory() {
    const user = storiesData[0];
    const highlight = user.highlights.find(h => h.id === currentHighlightId);
    
    if (currentHighlightStoryIndex < highlight.stories.length - 1) {
        currentHighlightStoryIndex++;
        showHighlightStory();
    } else {
        closeHighlight();
    }
}

// Navigate to previous highlight story
function previousHighlightStory() {
    if (currentHighlightStoryIndex > 0) {
        currentHighlightStoryIndex--;
        showHighlightStory();
    }
}

// Handle keypress for highlights
function handleHighlightKeyPress(event) {
    switch(event.key) {
        case 'Escape':
            closeHighlight();
            break;
        case 'ArrowLeft':
            previousHighlightStory();
            break;
        case 'ArrowRight':
        case ' ':
            event.preventDefault();
            nextHighlightStory();
            break;
    }
}

// Open create highlight modal
function openCreateHighlight() {
    const modal = document.getElementById('createHighlightModal');
    modal.classList.add('active');
    
    // Populate story selection
    const container = document.getElementById('storySelectionContainer');
    container.innerHTML = '';
    
    const user = storiesData[0];
    user.stories.forEach((story, index) => {
        const storyItem = document.createElement('div');
        storyItem.className = 'story-selection-item';
        storyItem.dataset.storyIndex = index;
        storyItem.innerHTML = `
            <img src="${story.image}" alt="Story">
            <div class="checkbox">✔</div>
        `;
        storyItem.addEventListener('click', function() {
            this.classList.toggle('selected');
        });
        container.appendChild(storyItem);
    });
}

// Close create highlight modal
function closeCreateHighlight() {
    const modal = document.getElementById('createHighlightModal');
    modal.classList.remove('active');
    document.getElementById('highlightTitleInput').value = '';
    document.getElementById('storySelectionContainer').innerHTML = '';
}

// Create new highlight
function createHighlight() {
    const title = document.getElementById('highlightTitleInput').value.trim();
    if (!title) {
        alert('Masukkan nama sorotan!');
        return;
    }
    
    const selectedStories = Array.from(document.querySelectorAll('.story-selection-item.selected'))
        .map(item => parseInt(item.dataset.storyIndex));
    
    if (selectedStories.length === 0) {
        alert('Pilih setidaknya satu story!');
        return;
    }
    
    const user = storiesData[0];
    const newHighlight = {
        id: `highlight_${Date.now()}`,
        title: title,
        cover: user.stories[selectedStories[0]].image, // Use first selected story as cover
        stories: selectedStories.map(index => ({ ...user.stories[index] }))
    };
    
    user.highlights.push(newHighlight);
    
    // Update UI (reload highlights section)
    updateHighlightsSection();
    
    // Close modal and show success
    closeCreateHighlight();
    showSuccessModal();
}

// Update highlights section
function updateHighlightsSection() {
    const highlightsSection = document.querySelector('.profile-highlights-section');
    if (!highlightsSection) return;
    
    const container = highlightsSection.querySelector('.highlights-container');
    container.innerHTML = '';
    
    const user = storiesData[0];
    user.highlights.forEach(highlight => {
        const highlightItem = document.createElement('div');
        highlightItem.className = 'highlight-item';
        highlightItem.dataset.highlightId = highlight.id;
        highlightItem.setAttribute('onclick', `openHighlight('${highlight.id}')`);
        highlightItem.innerHTML = `
            <div class="story-ring has-story">
                <img src="${highlight.cover}" alt="${highlight.title}" class="highlight-avatar">
            </div>
            <span class="highlight-username">${highlight.title}</span>
        `;
        container.appendChild(highlightItem);
    });
    
    // Add "New" button
    const newHighlightItem = document.createElement('div');
    newHighlightItem.className = 'highlight-item';
    newHighlightItem.setAttribute('onclick', 'openCreateHighlight()');
    newHighlightItem.innerHTML = `
        <div class="story-ring add-story">
            <div class="add-highlight-plus">+</div>
        </div>
        <span class="highlight-username">New</span>
    `;
    container.appendChild(newHighlightItem);
}

// Add option to save story as highlight in shareToStory
function shareToStory(type) {
    const storyData = {
        image: document.getElementById('previewImage').src,
        textElements: textElements.map(el => ({
            text: el.textContent,
            left: el.style.left,
            top: el.style.top,
            color: el.style.color,
            fontSize: el.style.fontSize,
            className: el.className
        })),
        type: type
    };
    
    const currentUser = storiesData[0];
    const newStory = {
        image: storyData.image,
        timestamp: 'Sekarang',
        textElements: storyData.textElements,
        type: type
    };
    
    if (!currentUser.stories) {
        currentUser.stories = [];
    }
    
    currentUser.stories.unshift(newStory);
    currentUser.has_story = true;
    
    // Update UI
    updateCurrentUserStoryRing();
    
    // Ask if user wants to add to highlights
    if (confirm('Tambahkan story ini ke sorotan?')) {
        openCreateHighlight();
    }
    
    // Close modal and show success
    closeAddStory();
    showSuccessModal();
}

    // Handle main profile picture
    if (uploadProfile) {
        uploadProfile.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    const profileImage = document.getElementById('profileImage');
                    if (profileImage) {
                        profileImage.src = ev.target.result;
                    }
                    showNotification('Foto profil diperbarui');
                }
                reader.readAsDataURL(file);
            }
        });
    }
}

// Modal interactions
function initializeModals() {
    const createPostForm = document.querySelector('#createPostModal form');
    
    if (createPostForm) {
        createPostForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const caption = document.getElementById('caption').value;
            const images = document.getElementById('images').files;
            
            if (caption.trim() === '' && images.length === 0) {
                showNotification('Mohon isi caption atau pilih gambar', 'error');
                return;
            }
            
            // Here you would send the data to your PHP backend
            console.log('Creating post:', { caption, images: images.length });
            
            // For demo purposes, just show success message
            showNotification('Post berhasil dibuat!', 'success');
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('createPostModal'));
            if (modal) {
                modal.hide();
            }
            
            // Reset form
            createPostForm.reset();
        });
    }
}

// Utility functions
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    // Style the notification
    Object.assign(notification.style, {
        position: 'fixed',
        top: '20px',
        right: '20px',
        backgroundColor: type === 'error' ? '#ed4956' : type === 'success' ? '#00d461' : '#262626',
        color: 'white',
        padding: '12px 16px',
        borderRadius: '8px',
        fontSize: '14px',
        zIndex: '9999',
        opacity: '0',
        transition: 'opacity 0.3s ease'
    });
    
    // Add to page
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.opacity = '1';
    }, 10);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Utility function to format time
function formatTime(date) {
    const now = new Date();
    const diff = now - new Date(date);
    const minutes = Math.floor(diff / 60000);
    const hours = Math.floor(diff / 3600000);
    const days = Math.floor(diff / 86400000);
    
    if (minutes < 60) {
        return `${minutes} menit`;
    } else if (hours < 24) {
        return `${hours} jam`;
    } else {
        return `${days} hari`;
    }
}

// Handle window resize
window.addEventListener('resize', function() {
    // Adjust layout for mobile
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    
    if (window.innerWidth <= 768) {
        if (sidebar) sidebar.classList.add('mobile');
        if (mainContent) mainContent.classList.add('mobile');
    } else {
        if (sidebar) sidebar.classList.remove('mobile');
        if (mainContent) mainContent.classList.remove('mobile');
    }
});

// Initialize on page load
window.addEventListener('load', function() {
    // Add loading animations
    const posts = document.querySelectorAll('.feed-post, .post-item');
    posts.forEach((post, index) => {
        post.style.opacity = '0';
        post.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            post.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            post.style.opacity = '1';
            post.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

// Export functions for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        initializeInstagram,
        showNotification,
        formatTime
    };
}