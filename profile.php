<?php
session_start();

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$userid = $_SESSION['userid'];

$conn = new mysqli("localhost", "root", "", "profile");

if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

// Pobieranie ID profilu powiązanego z zalogowanym użytkownikiem
$sql = "SELECT profileID FROM owner WHERE userID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userid);
$stmt->execute();
$stmt->bind_result($profileID);
$stmt->fetch();
$stmt->close();

if (empty($profileID)) {
    echo "Nie znaleziono profilu dla tego użytkownika";
    exit();
}

// Pobieranie danych profilu
$sql = "SELECT firstName, lastName, description, profilePhotoID FROM profile WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $profileID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $profile = $result->fetch_assoc();
} else {
    echo "Nie znaleziono danych profilu";
    exit();
}

$stmt->close();

// Pobieranie URL zdjęcia profilowego
$photoURL = '';
if ($profile['profilePhotoID'] != 0) {
    $sql = "SELECT URL FROM photo WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $profile['profilePhotoID']);
    $stmt->execute();
    $stmt->bind_result($photoURL);
    $stmt->fetch();
    $stmt->close();
}

// Obsługa zapisu formularza
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newName = $_POST['Name'];
    $newDescription = $_POST['description'];
    $profilePhotoID = $profile['profilePhotoID']; // Używamy istniejącego zdjęcia profilowego

    // Rozdzielenie imienia i nazwiska
    $nameParts = explode(" ", $newName, 2);
    $firstName = $nameParts[0];
    $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

    // Przesyłanie nowego zdjęcia profilowego, jeśli jest
    if (!empty($_FILES['photo']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["photo"]["name"]);
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            // Wstawianie nowego zdjęcia profilowego do tabeli `photo`
            $sql = "INSERT INTO photo (profileID, URL) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $profileID, $target_file);
            $stmt->execute();
            $profilePhotoID = $stmt->insert_id;
            $stmt->close();
        }
    }

    // Aktualizacja informacji o profilu w bazie danych
    $sql = "UPDATE profile SET firstName = ?, lastName = ?, description = ?, profilePhotoID = ? WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $firstName, $lastName, $newDescription, $profilePhotoID, $profileID);

    if ($stmt->execute()) {
        echo "Profil zaktualizowany pomyślnie!";
    } else {
        echo "Błąd podczas aktualizacji profilu: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Użytkownika</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <header>
        <nav>
            <p>Twój profil!</p>
        </nav>
        <nav>
            <a href="homepage.php"> Powrót do strony głównej!</a>
        </nav>
        <nav>
            <a href="logout.php">Wyloguj się!</a>
        </nav>
    </header>

    <main>
        <div class="profile-container">
            
            <form class="profile-form" method="post" enctype="multipart/form-data">
                <label for="Name">Imię i Nazwisko:</label>
                <input type="text" id="Name" name="Name" value="<?php echo htmlspecialchars($profile['firstName'] . ' ' . $profile['lastName']); ?>" required>

                <label for="description">Opis:</label>
                <textarea id="description" name="description"><?php echo htmlspecialchars($profile['description']); ?></textarea>

                <label for="photo">Zdjęcie:</label>
                <input type="file" id="photo" name="photo" accept="image/*">

                <?php if ($photoURL): ?>
                <div class="profile-photo">
                    <style>
                        img {
                           max-width:100px;
                           max-height:100px;
                        }
                    </style>
                    <img src="<?php echo htmlspecialchars($photoURL); ?>" alt="Zdjęcie profilowe">
                </div>
                <?php endif; ?>

                <button type="submit">Zapisz</button>
            </form>
        </div>
    </main>

    <footer>
        <div class="contact-info">
            <h3>Kontakt</h3>
            <p>Email: kontakt@portal.pl</p>
            <p>Telefon: +48 123 456 789</p>
            <p>Adres: Ul. Przykładowa 1, 00-001 Warszawa</p>
        </div>
        <p>2024 Wszystkie prawa zastrzeżone. Portal społecznościowy - znajdź i połącz się ze znajomymi.</p>
    </footer>

</body>
</html>
