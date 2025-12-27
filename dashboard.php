<?php
session_start(); // Fixes Error on Line 3
include "db_connect.php"; // Fixes Error on Line 5 (Ensure this filename is correct)

// Security Check: If user isn't logged in, send them back to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; 

// Now your notification query will work
$notif_query = "SELECT id FROM loans WHERE user_id = ? AND status = 'approved' AND seen = 0";
$stmt = $conn->prepare($notif_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$notif_result = $stmt->get_result();
$has_approved_loan = ($notif_result->num_rows > 0);
?>
<div class="dashboard-notifications" style="margin: 20px;">

    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
        <div id="success-alert" style="background-color: #d4edda; color: #155724; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px; margin-bottom: 10px; text-align: center;">
            <strong>🎉 Application Successful!</strong> Your loan request is under review.
        </div>
    <?php endif; ?>

    <?php if ($has_approved_loan): ?>
        <div class="bell-alert" style="background-color: #cfe2ff; color: #084298; padding: 15px; border: 1px solid #b6d4fe; border-radius: 5px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <span style="font-size: 20px; margin-right: 10px;">🔔</span>
                <strong>Good News!</strong> One of your loan applications has been <b>Approved</b>.
            </div>
            <a href="view_loans.php?action=mark_read" style="background: #084298; color: white; padding: 5px 12px; text-decoration: none; border-radius: 4px; font-size: 14px;">View & Clear</a>
        </div>
    <?php endif; ?>

</div>
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

<div id="preloader">
    <div class="spinner"></div>
    <p>Loading Dashboard...</p>
</div>

    <nav class="navbar">
        <div class="logo">SMEP <span>Microfinance</span></div>
        <div class="nav-links">
            <a href="#about">Home</a>
            <a href="#services">What We Do</a>
             <a href="./apply_loan.php" class="btn-apply_loan">Apply for a loan</a>
             <a href="./repay_loan.php" class="btn-Repay">Repay loan</a>
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
                 <div class="scroll-indicator">
    <p>Swipe Down</p>
    <div class="arrow"></div>
            </div>
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
    window.addEventListener("load", function() {
        var loader = document.getElementById("preloader");
        
        // Add a slight fade-out effect
        loader.style.transition = "opacity 4.5s ease";
        loader.style.opacity = "0";
        
        // Remove it from the DOM after fading so it doesn't block clicks
        setTimeout(function() {
            loader.style.display = "none";
        }, 500);
    });
    window.onscroll = function() {
    var indicator = document.querySelector(".scroll-indicator");
    if (window.pageYOffset > 50) {
        indicator.style.opacity = "0";
        indicator.style.transition = "0.5s";
    } else {
        indicator.style.opacity = "1";
    }
};
    </script>
    <footer class="footer">
        <p>&copy; 2025 SMEP Microfinance. Licensed by the Central Bank of Kenya.</p>
    </footer>

</body>
</html>