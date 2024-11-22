<?php
session_start();
include 'db_connect.php'; // Include the connection file

$showNotificationScript = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Vulnerable version using direct SQL query (no SQL injection protection)
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    // Check if any rows are returned
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Here we do not check the password for the sake of the demonstration
        // Uncomment the following line if you want to see how it behaves with a password input
        // if ($password == $user['password']) {  
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            header("Location: home.php");
            exit();
        // } else {
        //     $showNotificationScript = "<script>
        //             document.addEventListener('DOMContentLoaded', function() {
        //                 showNotification('Invalid password.', 'error');
        //             });
        //           </script>";
        // }
    } else {
        $showNotificationScript = "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    showNotification('No account found with that email.', 'error');
                });
              </script>";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <title>Vulnerable Login</title>
</head>
<body>
    <div id="notification" class="notification"></div>
    
    <div class="header">
        <img src="assets/Baze Logo.png" alt="Baze University Logo">
        <h2>IDEAS/24/15429</h2>
    </div>

    <div class="container">
        <h2>Vulnerable Login</h2>
        <form method="POST" action="login_vulnerable.php">
            <label for="email">Email:</label>
            <input type="text" name="email" required>
            
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>

    <script>
    function showNotification(message, type = 'success') {
        var notification = document.getElementById('notification');
        notification.innerHTML = message;
        notification.classList.add('show');
        
        if (type === 'error') {
            notification.classList.add('error');
        } else {
            notification.classList.remove('error');
        }
        
        setTimeout(function() {
            notification.classList.remove('show');
        }, 4000);
    }
    </script>

    <?php echo $showNotificationScript; ?>
</body>
</html>
