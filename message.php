<?php
// Ustawienia bazy danych
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "profile";

// Tworzenie połączenia
$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzanie połączenia
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = $_POST['subject'];
    $content = $_POST['content'];
    $senderID = 1; // Przykładowy ID nadawcy, zmienić zgodnie z logiką aplikacji
    $revID = 2; // Przykładowy ID odbiorcy, zmienić zgodnie z logiką aplikacji
    $timestamp = date('Y-m-d H:i:s');

    $sql = "INSERT INTO message (senderID, revID, subject, content, timesstamp) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisss", $senderID, $revID, $subject, $content, $timestamp);

    if ($stmt->execute()) {
        echo "New message sent successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
}

// Pobieranie wiadomości
$sql = "SELECT * FROM message ORDER BY timesstamp DESC";
$result = $conn->query($sql);
$messages = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Message</title>
    <link rel="stylesheet" href="message.css">
</head>
<body>
    <div class="container">
        <h1>Send Message</h1>
        
        <div class="chat-box">
            <?php foreach ($messages as $message): ?>
                <div class="message">
                    <div class="sender">User <?php echo htmlspecialchars($message['senderID']); ?>:</div>
                    <div class="message-content"><?php echo htmlspecialchars($message['content']); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <form action="message.php" method="post">
            <label for="subject">Subject:</label>
            <input type="text" id="subject" name="subject" required>
            
            <label for="content">Content:</label>
            <textarea id="content" name="content" rows="3" required></textarea>
            
            <input type="submit" value="Send Message">
        </form>
    </div>
</body>
</html>
