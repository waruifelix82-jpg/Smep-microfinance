<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMEP | Loan Application</title>
    <link rel="stylesheet" href="apply_loan.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="user-wrapper">
   <nav class="user-nav">
    <div class="logo">SMEP <span>Mobile</span></div>
   <button type="button" onclick="window.location.href='./dashboard.php'">
    ← Back to Dashboard
</button>
</nav>

    <main class="apply-container">
        <div class="apply-card">
            <header class="form-header">
                <h2>Loan Application</h2>
                <p>Enter your details below to request a new loan.</p>
            </header>

            <form action="submit_loan.php" method="POST" id="loanForm">
                <div class="form-group">
                    <label for="amount">How much do you need? (KES)</label>
                    <input type="number" name="amount" id="amount" placeholder="Min: 5,000 | Max: 100,000" required oninput="calculateLoan()">
                </div>

                <div class="form-group">
                    <label for="duration">Repayment Period</label>
                    <select name="duration" id="duration" required onchange="calculateLoan()">
                        <option value="1">1 Month</option>
                        <option value="3">3 Months</option>
                        <option value="6">6 Months</option>
                        <option value="12">12 Months</option>
                    </select>
                </div>
                    // Business Available
                <div class="form-group">
                    <label for="purpose">Purpose of Loan</label>
                    <select name="purpose" required>
                        <option value="Business">Business Expansion</option>
                        <option value="Agri">Agribusiness</option>
                        <option value="Education">Education/School Fees</option>
                        <option value="Emergency">Emergency/Medical</option>
                    </select>
                </div>

                <div class="calculation-summary">
                    <div class="summary-item">
                        <span>Interest Rate (12%)</span>
                        <span id="interestText">KES 0</span>
                    </div>
                    <div class="summary-item total">
                        <span>Total Repayment</span>
                        <span id="totalText">KES 0</span>
                    </div>
                </div>
<div class="form-actions">
    <button type="submit" class="btn-submit-loan">Submit Application</button>
    <button type="reset" class="btn-clear-loan">Cancel</button>
   
</div>
            </form>
        </div>
    </main>
</div>

<script>
function calculateLoan() {
    const amount = document.getElementById('amount').value;
    const duration = document.getElementById('duration').value;
    
    // Logic: 12% flat interest rate for this example
    if (amount > 0) {
        const interest = amount * 0.12;
        const total = parseFloat(amount) + parseFloat(interest);
        
        document.getElementById('interestText').innerText = "KES " + interest.toLocaleString();
        document.getElementById('totalText').innerText = "KES " + total.toLocaleString();
    } else {
        document.getElementById('interestText').innerText = "KES 0";
        document.getElementById('totalText').innerText = "KES 0";
    }
}
</script>

</body>
</html>