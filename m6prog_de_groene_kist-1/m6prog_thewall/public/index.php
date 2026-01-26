<?php
include_once "../source/database.php";

// Test database connection
$db_test = "error";
try {
    $connection = database_connect();
    $db_test = "success";
    $connection->close();
} catch (Exception $e) {
    $db_test = "error";
}

// Get messages from database
$messages = [];
if ($db_test === "success") {
    $messages = getMessages();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Wall - Message Board</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🌊 The Wall</h1>
            <p>Share your thoughts with the world</p>
        </header>

        <!-- Database Status -->
        <div class="db-status <?php echo $db_test; ?>">
            <strong>Database Status:</strong> 
            <?php echo ($db_test === "success") ? "✓ Connected" : "✗ Connection Failed"; ?>
        </div>

        <!-- Post New Message Form -->
        <div class="message-form">
            <h2>Post a New Message</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="author">Your Name:</label>
                    <input type="text" id="author" name="author" placeholder="Enter your name" required>
                </div>
                <div class="form-group">
                    <label for="content">Your Message:</label>
                    <textarea id="content" name="content" placeholder="What's on your mind?" required></textarea>
                </div>
                <button type="submit">Post Message</button>
            </form>
        </div>

        <!-- Messages Section -->
        <div class="messages-section">
            <h2>Messages on The Wall</h2>
            <?php if (count($messages) > 0): ?>
                <?php foreach ($messages as $message): ?>
                    <div class="message">
                        <div class="message-header">
                            <span class="message-author"><?php echo htmlspecialchars($message['author']); ?></span>
                            <span class="message-date"><?php echo date('d-m-Y H:i', strtotime($message['created_at'])); ?></span>
                        </div>
                        <div class="message-content">
                            <?php echo htmlspecialchars($message['content']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="message" style="text-align: center; color: #999;">
                    <p>No messages yet. Be the first to post!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
