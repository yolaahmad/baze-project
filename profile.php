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

$user = fetchUserDetails($conn, $user_id); // Initial fetch of user data

// Initialize notification message
$notification_message = '';
$notification_type = 'success'; // Default to success

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_bio = $_POST["bio"];
    $new_username = $_POST["username"];

    // Update user details in the database
    $update_stmt = $conn->prepare("UPDATE users SET bio = ?, username = ? WHERE id = ?");
    $update_stmt->bind_param("ssi", $new_bio, $new_username, $user_id);
    
    if ($update_stmt->execute()) {
        // Update the session variable for username
        $_SESSION["username"] = $new_username;
        $notification_message = 'Profile updated successfully!';

        // Fetch the updated user info from the database again to reflect changes
        $user = fetchUserDetails($conn, $user_id);
        
        // ** Reload the page to reflect changes immediately **
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    showNotification('$notification_message', 'success');
                    setTimeout(function() {
                        window.location.reload(); // Reload the page after the notification
                    }, 1500); // Give some time for the notification to appear
                });
              </script>";
    } else {
        $notification_message = 'Error updating profile.';
        $notification_type = 'error';
    }
    $update_stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"> <!-- Icons -->
    <title>Profile</title>
</head>
<body>
    <!-- Notification container -->
    <div id="notification" class="notification"></div>

    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?></h2>

        <!-- Display current bio -->
        <h4>Current Bio:</h4>
        <p><?php echo !empty($user["bio"]) ? htmlspecialchars($user["bio"]) : 'No bio available'; ?></p>

        <!-- Form to update profile -->
        <form method="POST" action="profile.php">
            <label for="username">Update Username:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($user["username"]); ?>" required>
            
            <label for="bio">Update Bio:</label>
            <textarea name="bio" placeholder="Enter your new bio here"><?php echo isset($user["bio"]) ? htmlspecialchars($user["bio"]) : ''; ?></textarea>
            
            <button type="submit">Update Profile</button>
        </form>
        
        <p><a href="logout.php">Logout</a></p>
    </div>

    <!-- Script for notifications -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Define the showNotification function
        function showNotification(message, type = 'success') {
            var notification = document.getElementById('notification');
            
            // Set the message and the notification type
            notification.innerHTML = message;
            notification.classList.add('show');
            
            if (type === 'error') {
                notification.classList.add('error');
            } else {
                notification.classList.remove('error');
            }
            
            // Hide the notification after 4 seconds
            setTimeout(function() {
                notification.classList.remove('show');
            }, 4000);
        }

        // Trigger notification if there's a message
        <?php if (!empty($notification_message)) { ?>
            showNotification("<?php echo $notification_message; ?>", "<?php echo $notification_type; ?>");
        <?php } ?>
    });

    </script>

       <!-- Bottom Navigation Bar -->
<nav class="bottom-nav">
    <a href="home.php" class="nav-item">
        <i class="bi bi-house-door"></i> <!-- Home Icon -->
        <span>Home</span>
    </a>
    <a href="add.php" class="nav-item">
        <i class="bi bi-plus-circle"></i> <!-- Add Icon -->
        <span>Add</span>
    </a>
    <a href="profile.php" class="nav-item">
        <i class="bi bi-person-circle"></i> <!-- Profile Icon -->
        <span>Profile</span>
    </a>
    <a href="settings.php" class="nav-item">
        <i class="bi bi-gear"></i> <!-- Settings Icon -->
        <span>Settings</span>
    </a>
</nav>
</body>
</html>
