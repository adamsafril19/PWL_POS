<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
    <!-- Enhanced Navbar with Hamburger Menu -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <i class="fas fa-lightbulb"></i>
                <span>Adam Shop</span>
            </div>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="nav-links">
                <a href="#" class="active">Home</a>
                <a href="#">Products</a>
                <a href="#">Categories</a>
                <a href="#">Contact</a>
                <div class="nav-buttons">
                    <a href="#" class="cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count">0</span>
                    </a>
                    <a href="{{ route('login') }}" class="login-btn">
                        <i class="fas fa-user"></i>
                        <span>Login</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Updated Hero Section with Background Image -->
    <section class="hero" style="background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('/images/frntr.jpeg') no-repeat center center; background-size: cover;">
        <div class="hero-content">
            <div class="product-image">
                <img src="/images/frntr.jpeg" alt="Modern Sofa Set">
                <h1 class="gradient-text">Welcome to YourStore</h1>
                <p>Discover amazing products at great prices with our exclusive collection.</p>
                <div class="hero-buttons">
                    <button class="btn btn-primary">
                        <i class="fas fa-shopping-bag"></i>
                        Shop Now
                    </button>
                    <button class="btn btn-secondary">
                        <i class="fas fa-play"></i>
                        Watch Demo
                    </button>
                </div>
            </div>
        </div>
        <div class="hero-overlay"></div>
    </section>

    <!-- Enhanced Featured Products Section -->
    <section class="products">
        <div class="section-header">
            <h2>Featured Products</h2>
            <p>Handpicked products for you</p>
        </div>
        <div class="carousel">
            <div class="carousel-container">
                <div class="carousel-item">
                    <div class="product-card">
                        <div class="product-badge">New</div>
                        <div class="product-image">
                            <img src="/api/placeholder/400/300" alt="Product 1">
                            <div class="product-overlay">
                                <button class="btn-icon"><i class="fas fa-heart"></i></button>
                                <button class="btn-icon"><i class="fas fa-shopping-cart"></i></button>
                            </div>
                        </div>
                        <div class="product-info">
                            <h3>Premium Product 1</h3>
                            <div class="product-price">
                                <span class="price">$99.99</span>
                                <span class="original-price">$129.99</span>
                            </div>
                            <div class="product-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <span>(4.5)</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Repeat for other products -->
            </div>
            <button class="carousel-prev" aria-label="Previous"><i class="fas fa-chevron-left"></i></button>
            <button class="carousel-next" aria-label="Next"><i class="fas fa-chevron-right"></i></button>
        </div>
    </section>

    <!-- Enhanced Features Section -->
    <section class="features">
        <div class="section-header">
            <h2>Why Choose Us</h2>
            <p>We provide the best experience for our customers</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <h3>Fast Delivery</h3>
                <p>Free shipping on all orders over $50</p>
                <a href="#" class="feature-link">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-tag"></i>
                </div>
                <h3>Best Prices</h3>
                <p>Price match guarantee on all products</p>
                <a href="#" class="feature-link">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h3>24/7 Support</h3>
                <p>Expert assistance whenever you need</p>
                <a href="#" class="feature-link">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Secure Payment</h3>
                <p>Multiple secure payment options</p>
                <a href="#" class="feature-link">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </section>

    <!-- Enhanced Testimonials Section -->
    <section class="testimonials">
        <div class="section-header">
            <h2>What Our Customers Say</h2>
            <p>Real feedback from real customers</p>
        </div>
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="testimonial-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p>"Amazing service and fast delivery! Will definitely shop here again."</p>
                <div class="testimonial-author">
                    <img src="/api/placeholder/100/100" alt="Customer 1">
                    <div class="author-info">
                        <h4>John Doe</h4>
                        <span>Verified Buyer</span>
                    </div>
                </div>
            </div>
            <!-- Repeat for other testimonials -->
        </div>
    </section>

    <!-- Enhanced Newsletter Section -->
    <section class="newsletter">
        <div class="newsletter-content">
            <h2>Stay Updated</h2>
            <p>Subscribe to our newsletter for exclusive offers and updates</p>
            <form class="newsletter-form">
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" placeholder="Enter your email">
                </div>
                <button class="btn btn-primary">
                    Subscribe
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </section>

    <!-- Enhanced Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>About Us</h3>
                <p>Your trusted destination for quality products and exceptional service.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Products</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact Info</h3>
                <ul class="contact-info">
                    <li><i class="fas fa-map-marker-alt"></i> 123 Store Street, City, Country</li>
                    <li><i class="fas fa-phone"></i> +1 234 567 890</li>
                    <li><i class="fas fa-envelope"></i> info@yourstore.com</li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Download App</h3>
                <div class="app-buttons">
                    <a href="#" class="app-button">
                        <i class="fab fa-apple"></i>
                        <span>App Store</span>
                    </a>
                    <a href="#" class="app-button">
                        <i class="fab fa-google-play"></i>
                        <span>Google Play</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 YourStore. All rights reserved.</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hamburger Menu
            const hamburger = document.querySelector('.hamburger');
            const navLinks = document.querySelector('.nav-links');

            hamburger.addEventListener('click', () => {
                hamburger.classList.toggle('active');
                navLinks.classList.toggle('active');
            });

            // Navbar Scroll Effect
            const navbar = document.querySelector('.navbar');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });

            // Enhanced Carousel
            const carousel = document.querySelector('.carousel-container');
            const items = document.querySelectorAll('.carousel-item');
            const nextBtn = document.querySelector('.carousel-next');
            const prevBtn = document.querySelector('.carousel-prev');

            let currentIndex = 0;
            const itemWidth = items[0].offsetWidth;

            function updateCarousel() {
                carousel.style.transform = `translateX(-${currentIndex * itemWidth}px)`;
            }

            nextBtn.addEventListener('click', () => {
                currentIndex = (currentIndex + 1) % items.length;
                updateCarousel();
            });

            prevBtn.addEventListener('click', () => {
                currentIndex = (currentIndex - 1 + items.length) % items.length;
                updateCarousel();
            });

            // Intersection Observer for Animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.feature-card, .testimonial-card, .product-card').forEach(el => {
                observer.observe(el);
            });
        });
    </script>
</body>
</html>
