<?php
$servername = "localhost"; // Zmień na odpowiednią wartość
$dbusername = "root"; // Zmień na odpowiednią wartość
$dbpassword = ""; // Zmień na odpowiednią wartość
$dbname = "profile";

// Sprawdzenie, czy formularz został wysłany metodą POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pobieranie danych z formularza
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordtwo = $_POST['confirmPassword'];

    // Sprawdzanie, czy pola nie są puste
    if (empty($fullName) || empty($email) || empty($password) || empty($passwordtwo)) {
        echo "Wszystkie pola są wymagane!";
        exit;
    }

    // Sprawdzanie, czy hasła są takie same
    if ($password !== $passwordtwo) {
        echo "Hasła nie są takie same!";
        exit;
    }

    // Hashowanie hasła
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $nameParts = explode(" ", $fullName, 2);
    $firstName = $nameParts[0];
    $lastName = $nameParts[1] ?? '';

    // Tworzenie połączenia z bazą danych
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    // Sprawdzanie połączenia
    if ($conn->connect_error) {
        die("Błąd połączenia: " . $conn->connect_error);
    }

    $conn->begin_transaction();

    try {
        // Wstawianie nowego użytkownika do bazy danych
        $sql = "INSERT INTO user (email, nameUser, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $email, $fullName, $hashed_password);

        if ($stmt->execute()) {
            // Pobranie ID nowego użytkownika
            $userID = $stmt->insert_id;
        } else {
            throw new Exception("Błąd: " . $stmt->error);
        }

        $stmt->close();

        // Wstawianie nowego profilu do bazy danych
        $sql = "INSERT INTO profile (firstName, lastName, profilePhotoID, description) VALUES (?, ?, 0, '')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $firstName, $lastName);
        $stmt->execute();
        $profileID = $stmt->insert_id;

        $stmt->close();

        // Wstawianie powiązania użytkownika z profilem do bazy danych
        $sql = "INSERT INTO owner (userID, profileID) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userID, $profileID);
        $stmt->execute();

        $stmt->close();

        $conn->commit();
        echo "Rejestracja zakończona sukcesem!";
        Header("Location: login.html");
    } catch (Exception $e) {
        $conn->rollback();
        echo "Błąd: " . $e->getMessage();
    }

    $conn->close();
}
?>
