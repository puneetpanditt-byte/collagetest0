<?php
require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media - SDGD College</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="top-bar">
            <div class="container">
                <div class="top-links">
                    <a href="admin/login.php" class="admin-link"><i class="fas fa-user-shield"></i> ADMIN</a>
                    <a href="teacher/login.php" class="teacher-link"><i class="fas fa-chalkboard-teacher"></i> TEACHER LOGIN</a>
                    <a href="magazine.php" class="magazine-link"><i class="fas fa-book"></i> College Magazine</a>
                </div>
            </div>
        </div>
        <div class="main-header">
            <div class="container">
                <div class="logo-section">
                    <img src="assets/logo.png" alt="College Logo" class="logo">
                    <div class="college-info">
                        <h1>SUB DIVISIONAL GOVERNMENT DEGREE COLLEGE</h1>
                        <h2>NAUHATTA, ROHTAS</h2>
                        <p>Affiliated to Veer Kunwar Singh University, Ara</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="main-nav">
        <div class="container">
            <ul class="nav-menu">
                <li><a href="index.php"><i class="fas fa-home"></i> HOME</a></li>
                <li><a href="about.php"><i class="fas fa-info-circle"></i> ABOUT US</a></li>
                <li><a href="media.php" class="active"><i class="fas fa-images"></i> MEDIA</a></li>
                <li><a href="contact.php"><i class="fas fa-phone"></i> CONTACT US</a></li>
            </ul>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-images"></i> Media Gallery</h1>
            <p>Photos, videos, and publications from SDGD College</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Media Navigation Tabs -->
            <div class="media-tabs">
                <button class="tab-btn active" onclick="showTab('photos')">
                    <i class="fas fa-image"></i> Photo Gallery
                </button>
                <button class="tab-btn" onclick="showTab('videos')">
                    <i class="fas fa-video"></i> Video Gallery
                </button>
                <button class="tab-btn" onclick="showTab('publications')">
                    <i class="fas fa-newspaper"></i> Publications
                </button>
            </div>

            <!-- Photos Section -->
            <div id="photos-tab" class="media-content active">
                <div class="content-header">
                    <h2><i class="fas fa-camera"></i> Photo Gallery</h2>
                    <p>Campus life, events, and achievements captured through our lens</p>
                </div>
                
                <div class="photo-grid">
                    <div class="photo-item">
                        <div class="photo-container">
                            <img src="assets/gallery/college-building.jpg" alt="College Building" class="gallery-image">
                            <div class="photo-overlay">
                                <div class="photo-info">
                                    <h4>College Building</h4>
                                    <span class="photo-date">Main Campus</span>
                                </div>
                            </div>
                        </div>
                        <div class="photo-details">
                            <p>Beautiful main building of SDGD College showcasing modern architecture and infrastructure.</p>
                        </div>
                    </div>
                    
                    <div class="photo-item">
                        <div class="photo-container">
                            <img src="assets/gallery/library.jpg" alt="Library" class="gallery-image">
                            <div class="photo-overlay">
                                <div class="photo-info">
                                    <h4>Library</h4>
                                    <span class="photo-date">Academic Resources</span>
                                </div>
                            </div>
                        </div>
                        <div class="photo-details">
                            <p>Well-stocked library with thousands of books and digital learning resources.</p>
                        </div>
                    </div>
                    
                    <div class="photo-item">
                        <div class="photo-container">
                            <img src="assets/gallery/laboratory.jpg" alt="Laboratory" class="gallery-image">
                            <div class="photo-overlay">
                                <div class="photo-info">
                                    <h4>Laboratory</h4>
                                    <span class="photo-date">Science Labs</span>
                                </div>
                            </div>
                        </div>
                        <div class="photo-details">
                            <p>Modern science laboratories equipped with latest equipment for practical learning.</p>
                        </div>
                    </div>
                    
                    <div class="photo-item">
                        <div class="photo-container">
                            <img src="assets/gallery/classroom.jpg" alt="Classroom" class="gallery-image">
                            <div class="photo-overlay">
                                <div class="photo-info">
                                    <h4>Classroom</h4>
                                    <span class="photo-date">Learning Spaces</span>
                                </div>
                            </div>
                        </div>
                        <div class="photo-details">
                            <p>Spacious and well-ventilated classrooms with modern teaching aids.</p>
                        </div>
                    </div>
                    
                    <div class="photo-item">
                        <div class="photo-container">
                            <img src="assets/gallery/sports.jpg" alt="Sports" class="gallery-image">
                            <div class="photo-overlay">
                                <div class="photo-info">
                                    <h4>Sports Facilities</h4>
                                    <span class="photo-date">Sports & Recreation</span>
                                </div>
                            </div>
                        </div>
                        <div class="photo-details">
                            <p>Excellent sports facilities including playground, cricket ground, and indoor games.</p>
                        </div>
                    </div>
                    
                    <div class="photo-item">
                        <div class="photo-container">
                            <img src="gallery/assets/auditorium.jpg" alt="Auditorium" class="gallery-image">
                            <div class="photo-overlay">
                                <div class="photo-info">
                                    <h4>Auditorium</h4>
                                    <span class="photo-date">Events & Programs</span>
                                </div>
                            </div>
                        </div>
                        <div class="photo-details">
                            <p>Large auditorium for cultural events, seminars, and college programs.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Videos Section -->
            <div id="videos-tab" class="media-content">
                <div class="content-header">
                    <h2><i class="fas fa-video"></i> Video Gallery</h2>
                    <p>Educational videos, events, and promotional content</p>
                </div>
                
                <div class="video-grid">
                    <div class="video-item">
                        <div class="video-container">
                            <div class="video-thumbnail">
                                <img src="assets/videos/college-intro.jpg" alt="College Introduction" class="video-thumb">
                                <div class="play-overlay">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>
                            <div class="video-info">
                                <h4>College Introduction</h4>
                                <span class="video-duration">5:30</span>
                                <span class="video-date">Overview</span>
                            </div>
                            </div>
                        </div>
                        <div class="video-details">
                            <p>Welcome to SDGD College! This video provides an overview of our institution, facilities, and educational offerings.</p>
                        </div>
                    </div>
                    
                    <div class="video-item">
                        <div class="video-container">
                            <div class="video-thumbnail">
                                <img src="assets/videos/annual-function.jpg" alt="Annual Function" class="video-thumb">
                                <div class="play-overlay">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>
                            <div class="video-info">
                                <h4>Annual Function</h4>
                                <span class="video-duration">45:00</span>
                                <span class="video-date">Events</span>
                            </div>
                            </div>
                        </div>
                        <div class="video-details">
                            <p>Highlights from our annual cultural function and prize distribution ceremony.</p>
                        </div>
                    </div>
                    
                    <div class="video-item">
                        <div class="video-container">
                            <div class="video-thumbnail">
                                <img src="videos/seminar.jpg" alt="Educational Seminar" class="video-thumb">
                                <div class="play-overlay">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>
                            <div class="video-info">
                                <h4>Educational Seminar</h4>
                                <span class="video-duration">30:00</span>
                                <span class="video-date">Academic</span>
                            </div>
                            </div>
                        </div>
                        <div class="video-details">
                            <p>Guest lecture on modern educational practices and industry trends.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Publications Section -->
            <div id="publications-tab" class="media-content">
                <div class="content-header">
                    <h2><i class="fas fa-newspaper"></i> Publications</h2>
                    <p>Research papers, magazines, and college publications</p>
                </div>
                
                <div class="publications-grid">
                    <div class="publication-item">
                        <div class="publication-cover">
                            <img src="assets/publications/research-journal.jpg" alt="Research Journal" class="publication-thumb">
                            <div class="publication-overlay">
                                <h4>Research Journal</h4>
                                <span class="pub-year">2024</span>
                            </div>
                            </div>
                        </div>
                        <div class="publication-details">
                            <h4>SDGD College Research Journal</h4>
                            <p>Peer-reviewed research journal featuring articles from faculty and students on various academic topics.</p>
                            <p><strong>ISSN:</strong> 2345-1234</p>
                            <p><strong>Editor:</strong> Dr. [Editor Name]</p>
                        </div>
                    </div>
                    
                    <div class="publication-item">
                        <div class="publication-cover">
                            <img src="assets/publications/college-magazine.jpg" alt="College Magazine" class="publication-thumb">
                                <div class="publication-overlay">
                                    <h4>College Magazine</h4>
                                <span class="pub-year">2024</span>
                            </div>
                            </div>
                        </div>
                        <div class="publication-details">
                            <h4>SDGD College Magazine</h4>
                            <p>Annual magazine showcasing student achievements, events, and college news.</p>
                            <p><strong>Features:</strong> Student articles, faculty contributions, campus highlights</p>
                        </div>
                    </div>
                    
                    <div class="publication-item">
                        <div class="publication-cover">
                            <img src="assets/publications/prospectus.jpg" alt="Prospectus" class="publication-thumb">
                                <div class="publication-overlay">
                                    <h4>Prospectus</h4>
                                <span class="pub-year">2024</span>
                            </div>
                            </div>
                        </div>
                        <div class="publication-details">
                            <h4>College Prospectus</h4>
                            <p>Comprehensive guide for admissions, courses, facilities, and college information.</p>
                            <p><strong>Includes:</strong> Course details, fee structure, admission process, and contact information</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>Contact Us</h4>
                    <p><i class="fas fa-map-marker-alt"></i> Sub Divisional Government Degree College, Nauhatta, Rohtas, Bihar</p>
                    <p><i class="fas fa-phone"></i> +91-XXXX-XXXXXX</p>
                    <p><i class="fas fa-envelope"></i> principal@sdgdcnauhatta.ac.in</p>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="photo-gallery.php">Photo Gallery</a></li>
                        <li><a href="video-gallery.php">Video Gallery</a></li>
                        <li><a href="admin/login.php">Admin Login</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Follow Us</h4>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> SDGD College, Nauhatta. All rights reserved. | Developed by <a href="#">Web Team</a></p>
            </div>
        </div>
    </footer>

    <script>
        function showTab(tabName) {
            // Hide all tabs
            const tabs = document.querySelectorAll('.media-content');
            tabs.forEach(tab => tab.classList.remove('active'));
            
            // Show selected tab
            const selectedTab = document.getElementById(tabName + '-tab');
            if (selectedTab) {
                selectedTab.classList.add('active');
            }
        }
    </script>

    <style>
        .page-header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 40px 0;
            text-align: center;
        }
        
        .page-header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 300;
        }
        
        .page-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 16px;
        }
        
        .media-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            justify-content: center;
        }
        
        .tab-btn {
            background: #f8f9fa;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .tab-btn.active {
            background: #3498db;
        }
        
        .tab-btn:hover {
            background: #2980b9;
        }
        
        .media-content {
            display: none;
        }
        
        .media-content.active {
            display: block;
        }
        
        .content-header {
            margin-bottom: 30px;
        }
        
        .content-header h2 {
            color: #2c3e50;
            font-size: 24px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .content-header h2 i {
            font-size: 20px;
            color: #3498db;
        }
        
        .photo-grid,
        .video-grid,
        .publications-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .photo-item,
        .video-item,
        .publication-item {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .photo-item:hover,
        .video-item:hover,
        .publication-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .photo-container {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
        }
        
        .gallery-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s;
        }
        
        .photo-item:hover .gallery-image {
            transform: scale(1.05);
        }
        
        .photo-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.7), rgba(0,0,0,0));
            color: white;
            padding: 15px;
            transform: translateY(100%);
            transition: transform 0.3s;
        }
        
        .photo-item:hover .photo-overlay {
            transform: translateY(0);
        }
        
        .photo-info {
            margin-bottom: 10px;
        }
        
        .photo-info h4 {
            margin: 0 0 10px 0;
            font-size: 16px;
            color: white;
        }
        
        .photo-date,
        .video-duration,
        .video-date {
            background: rgba(255,255,255,0.2);
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 500;
        }
        
        .video-thumbnail {
            position: relative;
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 5px;
        }
        
        .play-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0,0,0,0.7);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .video-placeholder {
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 120px;
            border-radius: 5px;
            border: 2px dashed #bdc3c7;
        }
        
        .video-placeholder i {
            font-size: 32px;
            color: #bdc3c7;
        }
        
        .video-info {
            padding: 15px;
        }
        
        .video-info h4 {
            margin: 0 0 10px 0;
            font-size: 16px;
            color: #2c3e50;
        }
        
        .video-description {
            color: #5a6c7d;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        
        .video-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        .watch-btn,
        .download-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .watch-btn:hover,
        .download-btn:hover {
            background: #c0392b;
        }
        
        .publication-cover {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
        }
        
        .publication-thumb {
            width: 100%;
            height: 150px;
            object-fit: cover;
            transition: transform 0.3s;
        }
        
        .publication-item:hover .publication-thumb {
            transform: scale(1.05);
        }
        
        .publication-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.7), rgba(0,0,0,0));
            color: white;
            padding: 15px;
            transform: translateY(100%);
            transition: transform 0.3s;
        }
        
        .publication-info {
            margin-bottom: 10px;
        }
        
        .publication-info h4 {
            margin: 0 0 10px 0;
            font-size: 16px;
            color: white;
        }
        
        .pub-year {
            background: #3498db;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 500;
        }
        
        .publication-details {
            padding: 15px;
            background: rgba(255,255,255,0.9);
        }
        
        .publication-details h4 {
            margin: 0 0 10px 0;
            font-size: 16px;
            color: #2c3e50;
        }
        
        .publication-details p {
            color: #5a6c7d;
            line-height: 1.6;
            margin: 0;
        }
        
        .publication-details strong {
            color: #2c3e50;
        }
        
        @media (max-width: 768px) {
            .media-tabs {
                flex-direction: column;
            }
            
            .photo-grid,
            .video-grid,
            .publications-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>
