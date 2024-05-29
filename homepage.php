<?php
$servername = "localhost";
$username = "username";
$password = "password";
$database = "profile";

// Tworzenie połączenia
$conn = new mysqli($servername, $username, $password, $database);

// Sprawdzanie połączenia
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pobieranie listy użytkowników
$sql = "SELECT ID, firstName, lastName, profilePhotoID FROM profile";
$result = $conn->query($sql);

// Przetwarzanie wyników
$users = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $user = array(
            "id" => $row["ID"],
            "firstName" => $row["firstName"],
            "lastName" => $row["lastName"],
            "profilePhotoID" => $row["profilePhotoID"]
        );
        $users[] = $user;
    }
} else {
    echo "Brak użytkowników";
}

// Zamykanie połączenia
$conn->close();

// Zwracanie wyników w formacie JSON
echo json_encode($users);
?>
