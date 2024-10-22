<!-- resources/views/home.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="logo">Idea</div>
        <div class="nav-links">
            <a href="#">Home</a>
            <a href="#">Products</a>
            <a href="#">Categories</a>
            <a href="#">Contact</a>
            <a href="#" class="cart-icon">
                <i class="fas fa-shopping-cart"></i>
            </a>
            <a href="{{ route('login') }}" class="login-btn">Login</a>
        </div>
    </nav>

    <section class="hero">
        <h1>Welcome to YourStore</h1>
        <p>Discover amazing products at great prices</p>
        <button class="btn">Shop Now</button>
    </section>

    <section class="features">
        <div class="feature-card">
            <i class="fas fa-truck"></i>
            <h3>Fast Delivery</h3>
            <p>Free shipping on all orders</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-tag"></i>
            <h3>Best Prices</h3>
            <p>Guaranteed low prices</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-headset"></i>
            <h3>24/7 Support</h3>
            <p>Always here to help</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-shield-alt"></i>
            <h3>Secure Payment</h3>
            <p>100% secure checkout</p>
        </div>
    </section>

    <section class="newsletter">
        <h2>Subscribe to Our Newsletter</h2>
        <form>
            <input type="email" placeholder="Enter your email">
            <button class="btn">Subscribe</button>
        </form>
    </section>

    <footer>
        <div class="social-links">
            <a href="#"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
        </div>
        <p>&copy; 2024 YourStore. All rights reserved.</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add smooth scrolling
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        });
    </script>
</body>
</html>
