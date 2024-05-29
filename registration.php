<?php
$servername = "localhost"; // Zmień na odpowiednią wartość
$username = "root"; // Zmień na odpowiednią wartość
$password = ""; // Zmień na odpowiednią wartość
$dbname = "profile";

// Sprawdzenie, czy formularz został wysłany metodą POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pobieranie danych z formularza
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordtwo = $_POST['passwordtwo'];

    // Sprawdzanie, czy pola nie są puste
    if (empty($username) || empty($email) || empty($password) || empty($passwordtwo)) {
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

    // Tworzenie połączenia z bazą danych
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Sprawdzanie połączenia
    if ($conn->connect_error) {
        die("Błąd połączenia: " . $conn->connect_error);
    }

    // Wstawianie nowego użytkownika do bazy danych
    $sql = "INSERT INTO user (email, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $hashed_password);

    if ($stmt->execute()) {
        echo "Rejestracja zakończona sukcesem!";
    } else {
        echo "Błąd: " . $sql . "<br>" . $conn->error;
    }

    // Zamykanie połączenia
    $stmt->close();
    $conn->close();
}
?>
