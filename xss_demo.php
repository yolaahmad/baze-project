<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XSS Demo</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div class="container">
        <h2>XSS Demonstration</h2>
        <form method="POST" action="xss_demo.php">
            <label for="comment">Leave a comment:</label>
            <textarea name="comment" required></textarea>
            <button type="submit">Submit</button>
        </form>
        
        <div class="comments-display">
            <h4>Comments:</h4>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $comment = $_POST["comment"];
                // Displaying the comment without sanitization (to show XSS vulnerability)
                echo "<p>" . $comment . "</p>";
            }
            ?>
        </div>

        <!-- SECURED TEXT INPUT <div class="comments-display">
    <h4>Comments:</h4>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $comment = $_POST["comment"];
        // Escaping the comment to prevent XSS
        echo "<p>" . htmlspecialchars($comment) . "</p>";
    }
    ?>
</div> -->

    </div>
</body>
</html>
