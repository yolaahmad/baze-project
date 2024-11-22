<?php
session_start();
include 'db_connect.php'; // Include the connection file

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Fetch user information from the database
$user_id = $_SESSION["user_id"];

// Fetch user details from the database **after** any updates to get the latest bio/username
function fetchUserDetails($conn, $user_id) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    return $user;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        /* Basic styling */
        body {
            font-family: 'Manrope', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .header {
            background-color: #007bff;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        .header h2 {
            margin: 0;
            font-size: 16px;
        }

        .balance-card {
            background-color: white;
            padding: 15px;
            margin: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .balance-card h3 {
            margin: 0;
            font-size: 20px;
        }

        .balance-card p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }

        .balance-amount {
            font-size: 24px;
            color: #007bff;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
       
        .balance-actions {
            display: flex;
            justify-content: space-around;
            margin-top: 10px;
        }

        .balance-actions button {
            padding: 10px 20px;
            background-color: #cce6ff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'Manrope', sans-serif;

        }

        .section {
            background-color: white;
            padding: 15px;
            margin: 10px 15px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .section h4 {
            margin-bottom: 10px;
            font-size: 16px;
        }

        .section .service {
            display: flex;
            justify-content: space-around;
            text-align: center;
        }

        .section .service div {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 10px;
        }

        .section .service div i {
            font-size: 30px;
            margin-bottom: 5px;
            color: #555;
        }

        .bottom-nav {
            background-color: white;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
            display: flex;
            justify-content: space-around;
            border-top: 1px solid #ccc;
        }

        .bottom-nav div {
            text-align: center;
        }

        .bottom-nav div i {
            font-size: 25px;
            color: #555;
        }

        .bottom-nav div p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>

    <!-- Top header -->
    <div class="header">
        <h2>Capstone Pro</h2>
        <h2><?php echo htmlspecialchars($_SESSION["username"]); ?></h2>
    </div>

    <!-- Balance card -->
    <div class="balance-card">
        <h3><?php echo htmlspecialchars($_SESSION["username"]); ?></h3>
        <p>*********</p>
        <div class="balance-amount">
            <span>Balance</span>
            <span>₦87,000,000</span>
        </div>
        <div class="balance-actions">
            <button><i class="bi bi-plus-circle"></i> Add Money</button>
            <button><i class="bi bi-dash-circle"></i> Withdraw Cash</button>
        </div>
    </div>

    <!-- Transfer and Cashout Section -->
    <div class="section">
        <h4>Transfer & Cashout</h4>
        <div class="service">
            <div>
                <i class="bi bi-arrow-up-right-circle"></i>
                <p>Transfer Money</p>
            </div>
            <div>
                <i class="bi bi-arrow-down-left-circle"></i>
                <p>Add Money</p>
            </div>
            <div>
                <i class="bi bi-person-circle"></i>
                <p>Withdraw Cash</p>
            </div>
            <div>
                <i class="bi bi-three-dots"></i>
                <p>More Services</p>
            </div>
        </div>
    </div>

    <!-- Recharge and Bill Payments Section -->
    <div class="section">
        <h4>Recharge & Bill Payments</h4>
        <div class="service">
            <div>
                <i class="bi bi-phone"></i>
                <p>Recharge Airtime</p>
            </div>
            <div>
                <i class="bi bi-bag"></i>
                <p>Buy Bundle</p>
            </div>
            <div>
                <i class="bi bi-receipt"></i>
                <p>Pay Bill</p>
            </div>
            <div>
                <i class="bi bi-lightning-charge"></i>
                <p>Electricity Prepaid</p>
            </div>
        </div>
    </div>

    <!-- Bottom navigation bar -->
    <div class="bottom-nav">
        <div>
            <i class="bi bi-house-door"></i>
            <p>Home</p>
        </div>
        <div>
            <i class="bi bi-qr-code-scan"></i>
            <p>Scan & Pay</p>
        </div>
        <div>
            <i class="bi bi-clock-history"></i>
            <p>Transaction History</p>
        </div>
        <div>
            <i class="bi bi-list"></i>
            <p>More</p>
        </div>
    </div>

</body>
</html>
