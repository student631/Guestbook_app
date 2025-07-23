<?php
// File to store messages
$filename = 'messages.json';

// Load existing messages
$messages = [];
if (file_exists($filename)) {
    $messages = json_decode(file_get_contents($filename), true);
    if (!is_array($messages)) $messages = [];
}

// Handle new submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $message = htmlspecialchars(trim($_POST['message']));

    if ($name && $message) {
        $newEntry = [
            'name' => $name,
            'message' => $message,
            'time' => date('Y-m-d H:i')
        ];

        $messages[] = $newEntry;

        // Save updated list to file
        file_put_contents($filename, json_encode($messages, JSON_PRETTY_PRINT));

        // Redirect to avoid resubmission on refresh
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>📖 Guest Book</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        input[type="text"],
        textarea {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            resize: vertical;
        }
        button {
            background: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #218838;
        }
        .messages {
            margin-top: 30px;
        }
        .entry {
            background: #fafafa;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 6px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .entry .name {
            font-weight: bold;
            color: #28a745;
        }
        .entry .time {
            font-size: 12px;
            color: #888;
            margin-top: 5px;
        }
        .entry .text {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>📖 Guest Book</h2>
        <form method="post">
            <input type="text" name="name" placeholder="Your Name" required>
            <textarea name="message" placeholder="Your Message" rows="4" required></textarea>
            <button type="submit">Submit</button>
        </form>

        <div class="messages">
            <?php if (empty($messages)): ?>
                <p>No messages yet. Be the first to sign the guest book!</p>
            <?php else: ?>
                <?php foreach (array_reverse($messages) as $entry): ?>
                    <div class="entry">
                        <div class="name"><?= htmlspecialchars($entry['name']) ?></div>
                        <div class="time"><?= $entry['time'] ?></div>
                        <div class="text"><?= nl2br(htmlspecialchars($entry['message'])) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
