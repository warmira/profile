<?php
session_start();

$servername = "localhost"; // Zmień na odpowiednią wartość
$dbusername = "root"; // Zmień na odpowiednią wartość
$dbpassword = ""; // Zmień na odpowiednią wartość
$dbname = "profile";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        echo "Wszystkie pola są wymagane";
        exit;
    }

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname) or die ("conn error");

    if ($conn->connect_error){
        die ("Błąd połączenia " . $conn->connect_error);
    }

    $sql = "SELECT ID, nameUser, password FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $username, $hashed_password);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['userid'] = $id;
            $_SESSION['username'] = $username;
            echo "Logowanie zakończone sukcesem";
            header("Location: homepage.php");
            exit;
        } else {
            echo "Nieprawidłowe hasło!";
        }
    } else {
        echo "Nie znaleziono użytkownika z takim emailem";
    }

    $stmt->close();
    $conn->close();
}