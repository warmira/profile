<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Społecznościowy</title>
    <link rel="stylesheet" href="homepage.css">
    <script src="homepage.js" defer></script>
</head>
<body>
    <header>
        <div class="header-content">
            <h1>Portal Społecznościowy</h1>
            <nav>
            <?php if (isset($_SESSION['userid'])): ?>
                <p>Witaj, <?php echo htmlspecialchars($_SESSION['username']); ?>! Jesteś zalogowany.</p>
                <a href="profile.php">Profil Użytkownika</a>
                <a href="logout.php">Wyloguj się</a>
            <?php else: ?>
                <a href="login.html">Zaloguj się</a>
            <?php endif; ?>
            </nav>
        </div>
    </header>

    <div class="main-container">
        <section class="search">
            <h2>Wyszukiwarka znajomych</h2>
            <form id="search-form">
                <input type="text" id="search" name="search" placeholder="Szukaj znajomych..." required>
                <button type="submit">Szukaj</button>
            </form>
        </section>

        <section class="gallery">
            <h2>Wyniki wyszukiwania</h2>
            <div class="photo-grid" id="search-results"></div>
        </section>
    </div>

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
