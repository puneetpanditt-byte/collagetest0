<?php
require_once 'config.php';
require_once 'includes/functions.php';

// Set page title
$page_title = get_page_title('about.php');

// Generate breadcrumbs
generate_breadcrumbs([
    ['title' => 'Home', 'url' => 'index.php'],
    ['title' => 'About Us', 'url' => 'about.php']
]);

// Get college statistics
$stats = get_college_stats();
?>

<?php include 'includes/header.php'; ?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1>About SDGD College</h1>
        <p>Excellence in Education Since 1980</p>
    </div>
</div>

<!-- Main Content -->
<main class="main-content">
    <div class="container">
        <!-- About Section -->
        <section class="about-section">
            <div class="section-content">
                <h2><i class="fas fa-university"></i> About Our College</h2>
                <div class="content-grid">
                    <div class="about-content">
                        <p>Sub Divisional Government Degree College, Nauhatta is a premier educational institution located in Rohtas district, Bihar. Established with the vision to provide quality higher education to students from rural and semi-urban areas, our college has been a beacon of knowledge and excellence for over four decades.</p>
                        
                        <h3>Our Mission</h3>
                        <p>To provide accessible, affordable, and high-quality education that empowers students to achieve their academic and professional goals while contributing to the development of society.</p>
                        
                        <h3>Our Vision</h3>
                        <p>To become a center of academic excellence that nurtures innovation, research, and holistic development of students, preparing them to be responsible global citizens.</p>
                        
                        <h3>Core Values</h3>
                        <ul class="values-list">
                            <li><i class="fas fa-check-circle"></i> <strong>Excellence:</strong> Striving for the highest standards in teaching and learning</li>
                            <li><i class="fas fa-check-circle"></i> <strong>Integrity:</strong> Upholding ethical principles and transparency</li>
                            <li><i class="fas fa-check-circle"></i> <strong>Innovation:</strong> Encouraging creativity and new ideas</li>
                            <li><i class="fas fa-check-circle"></i> <strong>Inclusivity:</strong> Providing equal opportunities for all students</li>
                            <li><i class="fas fa-check-circle"></i> <strong>Service:</strong> Contributing to community and society</li>
                        </ul>
                    </div>
                    
                    <div class="college-highlights">
                        <h3>Quick Facts</h3>
                        <div class="facts-grid">
                            <div class="fact-item">
                                <div class="fact-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="fact-info">
                                    <h4>Established</h4>
                                    <p>1980</p>
                                </div>
                            </div>
                            
                            <div class="fact-item">
                                <div class="fact-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="fact-info">
                                    <h4>Location</h4>
                                    <p>Nauhatta, Rohtas</p>
                                </div>
                            </div>
                            
                            <div class="fact-item">
                                <div class="fact-icon">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <div class="fact-info">
                                    <h4>Affiliation</h4>
                                    <p>VKS University, Ara</p>
                                </div>
                            </div>
                            
                            <div class="fact-item">
                                <div class="fact-icon">
                                    <i class="fas fa-certificate"></i>
                                </div>
                                <div class="fact-info">
                                    <h4>Recognition</h4>
                                    <p>UGC Approved</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Statistics Section -->
        <section class="stats-section">
            <div class="section-header">
                <h2><i class="fas fa-chart-bar"></i> Our Achievements</h2>
                <p>Numbers that speak volumes about our commitment to excellence</p>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo number_format($stats['students'] ?? 1500); ?>+</h3>
                        <p>Students Enrolled</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $stats['courses'] ?? 25; ?>+</h3>
                        <p>Courses Offered</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $stats['faculty'] ?? 50; ?>+</h3>
                        <p>Expert Faculty</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $stats['placement'] ?? 95; ?>%</h3>
                        <p>Placement Rate</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Facilities Section -->
        <section class="facilities-section">
            <div class="section-header">
                <h2><i class="fas fa-building"></i> Our Facilities</h2>
                <p>State-of-the-art infrastructure to support holistic learning</p>
            </div>
            
            <div class="facilities-grid">
                <div class="facility-card">
                    <div class="facility-icon">
                        <i class="fas fa-flask"></i>
                    </div>
                    <h3>Science Laboratories</h3>
                    <p>Well-equipped labs for Physics, Chemistry, Botany, and Zoology with modern equipment and safety measures.</p>
                </div>
                
                <div class="facility-card">
                    <div class="facility-icon">
                        <i class="fas fa-desktop"></i>
                    </div>
                    <h3>Computer Labs</h3>
                    <p>Modern computer laboratories with high-speed internet and latest software for technical education.</p>
                </div>
                
                <div class="facility-card">
                    <div class="facility-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h3>Library</h3>
                    <p>Spacious library with thousands of books, journals, and digital resources for research and learning.</p>
                </div>
                
                <div class="facility-card">
                    <div class="facility-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Sports Complex</h3>
                    <p>Large playground and sports facilities for cricket, football, athletics, and indoor games.</p>
                </div>
            </div>
        </section>

        <!-- Message Section -->
        <section class="message-section">
            <div class="section-header">
                <h2><i class="fas fa-quote-left"></i> Principal's Message</h2>
                <p>Words of wisdom from our leadership</p>
            </div>
            
            <div class="message-content">
                <div class="principal-message">
                    <div class="message-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <blockquote>
                        <p>"Education is the most powerful weapon which you can use to change the world. At SDGD College, we are committed to providing quality education that empowers our students to become responsible citizens and leaders of tomorrow."</p>
                        <footer>- Principal, SDGD College</footer>
                    </blockquote>
                </div>
                
                <div class="cta-section">
                    <h3>Join Our Community</h3>
                    <p>Be part of our journey towards academic excellence and holistic development.</p>
                    <div class="cta-buttons">
                        <a href="courses.php" class="btn btn-primary">
                            <i class="fas fa-graduation-cap"></i> Explore Courses
                        </a>
                        <a href="contact.php" class="btn btn-secondary">
                            <i class="fas fa-envelope"></i> Contact Us
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

<style>
/* About Page Specific Styles */
.page-header {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    color: #fff;
    padding: 60px 0;
    text-align: center;
    margin-bottom: 40px;
}

.page-header h1 {
    font-size: 36px;
    font-weight: 700;
    margin-bottom: 15px;
}

.page-header p {
    font-size: 18px;
    opacity: 0.9;
    max-width: 600px;
    margin: 0 auto;
}

.about-section {
    padding: 40px 0;
}

.content-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 40px;
    align-items: start;
}

.about-content h3 {
    color: #2c3e50;
    margin: 30px 0 15px 0;
    font-size: 24px;
}

.values-list {
    list-style: none;
    padding: 0;
}

.values-list li {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    margin-bottom: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #3498db;
}

.values-list i {
    color: #3498db;
    margin-top: 2px;
}

.college-highlights {
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.facts-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.fact-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    transition: transform 0.3s;
}

.fact-item:hover {
    transform: translateY(-5px);
}

.fact-icon {
    width: 50px;
    height: 50px;
    background: #3498db;
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.fact-info h4 {
    color: #2c3e50;
    margin-bottom: 5px;
    font-size: 16px;
}

.fact-info p {
    color: #7f8c8d;
    font-weight: 600;
    margin: 0;
}

.facilities-section {
    padding: 60px 0;
    background: #f8f9fa;
}

.facilities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
}

.facility-card {
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}

.facility-card:hover {
    transform: translateY(-10px);
}

.facility-icon {
    width: 60px;
    height: 60px;
    background: #3498db;
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin: 0 auto 20px;
}

.facility-card h3 {
    color: #2c3e50;
    margin-bottom: 15px;
}

.message-section {
    padding: 60px 0;
}

.message-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 40px;
    align-items: center;
}

.principal-message {
    background: #fff;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.message-icon {
    width: 80px;
    height: 80px;
    background: #f39c12;
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    margin: 0 auto 30px;
}

blockquote {
    font-style: italic;
    color: #555;
    line-height: 1.8;
    margin: 0;
    padding: 0 20px;
}

blockquote footer {
    display: block;
    text-align: right;
    font-weight: 600;
    color: #2c3e50;
    margin-top: 20px;
}

.cta-section {
    text-align: center;
}

.cta-section h3 {
    color: #2c3e50;
    margin-bottom: 20px;
}

.cta-buttons {
    display: flex;
    gap: 20px;
    justify-content: center;
    margin-top: 30px;
}

@media (max-width: 768px) {
    .content-grid {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    .facts-grid {
        grid-template-columns: 1fr;
    }
    
    .facilities-grid {
        grid-template-columns: 1fr;
    }
    
    .message-content {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }
}
</style>

<?php include 'includes/footer.php'; ?>
