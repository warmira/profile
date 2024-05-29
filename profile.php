<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "profile";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userID = isset($_POST['userID']) ? intval($_POST['userID']) : null;
$userData = null;

if ($userID !== null) {
    $sql = "SELECT profile.firstName, profile.lastName, user.nameUser, photo.URL
            FROM profile
            JOIN owner ON profile.ID = owner.profileID
            JOIN user ON owner.userID = user.ID
            JOIN photo ON profile.profilePhotoID = photo.ID
            WHERE user.ID = $userID";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Użytkownika</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="profile-container">
        <form method="post" action="profile.php" class="user-form">
            <label for="userID">Podaj ID użytkownika:</label>
            <input type="number" id="userID" name="userID" required>
            <button type="submit">Pokaż Profil</button>
        </form>

        <?php if ($userData): ?>
            <div class="profile">
                <img src="<?= $userData["URL"] ?>" alt="Profile Photo" class="profile-photo">
                <h2><?= $userData["firstName"] . " " . $userData["lastName"] ?></h2>
                <h3>@<?= $userData["nameUser"] ?></h3>
            </div>
        <?php elseif ($userID !== null): ?>
            <p>Nie znaleziono użytkownika o ID <?= $userID ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
