<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instagram Clone</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Tambahkan font Billabong CDN -->
    <link href="https://fonts.cdnfonts.com/css/billabong" rel="stylesheet">
    <!-- <link href="../public/css/style.css"> -->
     <style>
         body {
            background-color: #000;
            color: #fff;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .sidebar {
            background-color: #000;
            border-right: 1px solid #262626;
            height: 100vh;
            position: fixed;
            width: 250px;
            padding: 20px 0;
        }
        
        .main-content {
            margin-left: 250px;
            background-color: #000;
            min-height: 100vh;
        }
        
        .logo {
            font-family: 'Billabong', cursive;
            font-size: 2.5rem;
            color: #fff;
            text-decoration: none;
            padding: 20px;
            letter-spacing: 2px; 
        }
        
        .nav-item {
            padding: 12px 24px;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: background-color 0.2s;
        }
        
        .nav-item:hover {
            background-color: #1a1a1a;
            color: #fff;
            text-decoration: none;
        }
        
        .nav-item i {
            margin-right: 16px;
            font-size: 24px;
            width: 24px;
        }
        
        .profile-header {
            padding: 40px 20px;
            max-width: 935px;
            margin: 0 auto;
        }
        
        .profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .profile-stats span {
            margin-right: 40px;
        }
        
        .profile-stats .number {
            font-weight: bold;
            color: #fff;
        }
        
        .profile-tabs {
            border-top: 1px solid #262626;
            display: flex;
            justify-content: center;
        }
        
        .profile-tab {
            padding: 16px;
            color: #8e8e8e;
            text-decoration: none;
            display: flex;
            align-items: center;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .profile-tab.active {
            color: #fff;
            border-top: 1px solid #fff;
        }
        
        .profile-tab i {
            margin-right: 6px;
        }
        
        .posts-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 4px;
            max-width: 935px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .post-item {
            aspect-ratio: 1;
            background-color: #262626;
            position: relative;
            overflow: hidden;
        }
        
        .post-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #8e8e8e;
        }
        
        .notification-dot {
            background-color: #ff3040;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            position: absolute;
            top: 8px;
            right: 8px;
        }
        
        .btn-follow {
            background-color: #262626;
            color: white;
            border: none;
            padding: 8px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
        }
        
        .btn-message {
            background-color: #262626;
            color: white;
            border: 1px solid #262626;
            padding: 8px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
        }
        
        .story-highlights {
            padding: 20px 0;
        }
        
        .highlight-item {
            display: inline-block;
            text-align: center;
            margin-right: 20px;
            position: relative;
        }
        
        .highlight-pic {
            width: 87px;
            height: 87px;
            border-radius: 50%;
            border: 2px solid #262626;
            padding: 2px;
            background-color: #262626;
        }
        
        .highlight-name {
            font-size: 12px;
            color: #fff;
            margin-top: 8px;
        }

        .highlight-circle {
            width: 87px;
            height: 87px;
            border-radius: 50%;
            border: 2px solid #262626;
            background-color: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: #fff;
            cursor: pointer;
            margin: 0 auto;
        }

        .highlight-circle:hover {
            background-color: #1a1a1a;
        }

        .delete-highlight {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ff3040;
            color: white;
            border-radius: 50%;
            font-size: 14px;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        #highlightContainer {
            display: flex;
            flex-direction: row;
            gap: 20px;
            align-items: flex-start;
        }

        .posts-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* 3 kolom */
    gap: 15px;
}

.post-item {
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
}

.post-item img {
    width: 100%;
    object-fit: cover; /* biar rapi proporsional */
    display: block;
}
.post-placeholder {
    height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999;
    background: #f5f5f5;
    font-size: 14px;
}
        </style>

</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="d-flex flex-column h-100">
            <div class="logo">Instagram</div>
            
            <nav class="flex-grow-1">
                <a href="#" class="nav-item"><i class="fas fa-home"></i>Home</a>
                <a href="#" class="nav-item"><i class="fas fa-search"></i>Search</a>
                <a href="#" class="nav-item"><i class="far fa-compass"></i>Explore</a>
                <a href="#" class="nav-item"><i class="fas fa-video"></i>Reels</a>
                <a href="#" class="nav-item position-relative"><i class="far fa-comment"></i>Messages <span class="notification-dot"></span></a>
                <a href="#" class="nav-item position-relative"><i class="far fa-heart"></i>Notifications <span class="notification-dot"></span></a>
                <a href="#" class="nav-item"><i class="far fa-plus-square"></i>Create</a>
                <a href="#" class="nav-item"><i class="far fa-user"></i>Profile</a>
            </nav>
            
            <div class="mt-auto">
                <a href="#" class="nav-item"><i class="fas fa-bars"></i>More</a>
            </div>
        </div>
    </div>