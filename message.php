<?php
require_once('class/Message.class.php');



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = $_POST['subject'];
    $content = $_POST['content'];
    $senderID = 1; // Przykładowy ID nadawcy, zmienić zgodnie z logiką aplikacji
    $revID = 2; // Przykładowy ID odbiorcy, zmienić zgodnie z logiką aplikacji

    if(Message::NewMessage($revID,$senderID,$subject,$content)) {
        echo "Wiadomość została wysłana";
    } else {
        echo "Błąd - wiadomośc nie została wysłana";
    }
    
}

// Pobieranie wiadomości
$messages = Message::GetInbox(1);
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
                    <div class="sender">User <?php echo $message->GetSndName(); ?>:</div>
                    <div class="message-content"><?php echo $message->GetContent(); ?></div>
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
