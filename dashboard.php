<?php if (isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
    <div id="success-alert" style="background-color: #d4edda; color: #155724; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px; margin: 20px; text-align: center; transition: opacity 1s ease;">
        <strong>🎉 Application Successful!</strong> Your loan request is under review.
    </div>
<?php endif; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMEP Microfinance | Empowering Your Dreams</title>
    <link rel="stylesheet" href="home_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <nav class="navbar">
        <div class="logo">SMEP <span>Microfinance</span></div>
        <div class="nav-links">
            <a href="#about">Home</a>
            <a href="#services">What We Do</a>
             <a href="./apply_loan.php" class="btn-apply_loan">Apply for a loan</a>
             <a href="./Repay_loan.php" class="btn-Repay">Repay loan</a>
            <a href="./LOGIN/Index.php" class="btn-login">Login</a>
            <a href="./register.php" class="btn-register">Join Us</a>
            
           
        </div>
    </nav>

    <header class="hero">
        <div class="hero-content">
            <h1>Quick Loans for a <br><span>Brighter Future.</span></h1>
            <p>We provide accessible financial solutions to help entrepreneurs, farmers, and families grow.</p>
            <div class="hero-btns">
                <a href="./apply_loan.php" class="btn-primary">Apply for a Loan</a>
                <a href="#services" class="btn-secondary">View Our Products</a>
            </div>
        </div>
    </header>

    <section id="services" class="services-section">
        <div class="section-title">
            <span>Our Expertise</span>
            <h2>What We Do</h2>
            <p>Tailored financial products to meet your specific needs.</p>
        </div>

       
        <div class="services-grid">
            <div class="service-card">
                <div class="card-image">
                    <img src="https://images.unsplash.com/photo-1556742044-3c52d6e88c62?auto=format&fit=crop&q=80&w=500" alt="Business Loans">
                </div>
                <div class="card-body">
                    <h3>Business Growth</h3>
                    <p>Capital to expand your shop, buy inventory, or scale your small enterprise.</p>
                </div>
            </div>

            <div class="service-card">
                <div class="card-image">
                    <img src="https://images.unsplash.com/photo-1523348837708-15d4a09cfac2?auto=format&fit=crop&q=80&w=500" alt="Agri-Loans">
                </div>
                <div class="card-body">
                    <h3>Agribusiness</h3>
                    <p>Supporting farmers with funds for seeds, fertilizer, and modern farming equipment.</p>
                </div>
            </div>

            <div class="service-card">
                <div class="card-image">
                    <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&q=80&w=500" alt="Education Loans">
                </div>
                <div class="card-body">
                    <h3>Education Loans</h3>
                    <p>Ensuring no dream is deferred. Quick loans for school fees and university tuition.</p>
                </div>
            </div>
        </div>
    </section>
<script>
    window.onload = function() {
        const alert = document.getElementById('success-alert');
        if (alert) {
            // Wait 3 seconds (3000ms), then start fading out
            setTimeout(function() {
                alert.style.opacity = '0'; // Fade out effect
                
                // Completely remove from view after the fade animation
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 1000); 
            }, 3000); 
        }
    };
    </script>
    <footer class="footer">
        <p>&copy; 2025 SMEP Microfinance. Licensed by the Central Bank of Kenya.</p>
    </footer>

</body>
</html>