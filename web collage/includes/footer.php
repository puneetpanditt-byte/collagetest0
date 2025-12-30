<?php
require_once 'config.php';
?>

<!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>Contact Us</h4>
                    <p><i class="fas fa-map-marker-alt"></i> Sub Divisional Government Degree College, Nauhatta, Rohtas, Bihar</p>
                    <p><i class="fas fa-phone"></i> +91-XXXX-XXXXXX</p>
                    <p><i class="fas fa-envelope"></i> principal@sdgdcnauhatta.ac.in</p>
                    <p><i class="fas fa-globe"></i> www.sdgdcnauhatta.ac.in</p>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="about.php">About College</a></li>
                        <li><a href="courses.php">Courses</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
                        <li><a href="admin/login.php">Admin Login</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Academic Links</h4>
                    <ul>
                        <li><a href="examination.php">Examination</a></li>
                        <li><a href="results.php">Results</a></li>
                        <li><a href="student-portal.php">Student Portal</a></li>
                        <li><a href="facilities.php">Facilities</a></li>
                        <li><a href="magazine.php">College Magazine</a></li>
                        <li><a href="tender.php">Tenders</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Follow Us</h4>
                    <div class="social-links">
                        <a href="#" title="Facebook"><i class="fab fa-facebook"></i></a>
                        <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
                        <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
                        <a href="#" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    </div>
                    <div class="newsletter">
                        <p>Subscribe to our newsletter</p>
                        <form class="newsletter-form">
                            <input type="email" placeholder="Your email" required>
                            <button type="submit"><i class="fas fa-paper-plane"></i></button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="container">
                    <div class="footer-bottom-content">
                        <p>&copy; <?php echo date('Y'); ?> SDGD College, Nauhatta. All rights reserved.</p>
                        <div class="footer-bottom-links">
                            <a href="#">Privacy Policy</a>
                            <a href="#">Terms of Use</a>
                            <a href="#">Disclaimer</a>
                            <a href="#">Sitemap</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="backToTop" class="back-to-top" title="Back to top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        // Back to top button functionality
        window.onscroll = function() {
            scrollFunction();
        };

        function scrollFunction() {
            var backToTop = document.getElementById("backToTop");
            if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
                backToTop.style.display = "block";
            } else {
                backToTop.style.display = "none";
            }
        }

        document.getElementById("backToTop").onclick = function() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        };

        // Newsletter form submission
        document.querySelector('.newsletter-form').addEventListener('submit', function(e) {
            e.preventDefault();
            var email = this.querySelector('input[type="email"]').value;
            // Here you would normally send this to your server
            alert('Thank you for subscribing with email: ' + email);
            this.querySelector('input[type="email"]').value = '';
        });

        // Search functionality
        document.querySelector('.search-btn').addEventListener('click', function() {
            var searchTerm = document.querySelector('.search-input').value;
            if (searchTerm.trim()) {
                window.location.href = 'search.php?q=' + encodeURIComponent(searchTerm);
            }
        });

        document.querySelector('.search-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                var searchTerm = this.value;
                if (searchTerm.trim()) {
                    window.location.href = 'search.php?q=' + encodeURIComponent(searchTerm);
                }
            }
        });
    </script>
</body>
</html>
