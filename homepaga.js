document.addEventListener("DOMContentLoaded", function() {
    const searchForm = document.querySelector(".search form");
    const searchInput = document.getElementById("search");
    const photoGrid = document.querySelector(".photo-grid");

    // Funkcja do wyświetlania wyników wyszukiwania
    function displaySearchResults(users) {
        photoGrid.innerHTML = ""; // Wyczyszczenie zawartości galerii

        users.forEach(user => {
            const photoItem = document.createElement("div");
            photoItem.classList.add("photo-item");

            const img = document.createElement("img");
            img.src = "profile_photo.php?id=" + user.profilePhotoID; // Adres do skryptu pobierającego zdjęcie profilowe
            img.alt = "Zdjęcie użytkownika";

            const name = document.createElement("p");
            name.textContent = user.firstName + " " + user.lastName;

            photoItem.appendChild(img);
            photoItem.appendChild(name);
            photoGrid.appendChild(photoItem);
        });
    }

    // Obsługa wyszukiwania
    searchForm.addEventListener("submit", function(event) {
        event.preventDefault(); // Zapobieganie domyślnej akcji formularza

        const searchQuery = searchInput.value.trim();

        // Wysłanie zapytania do serwera
        fetch("search.php?search=" + encodeURIComponent(searchQuery))
            .then(response => response.json())
            .then(data => displaySearchResults(data))
            .catch(error => console.error("Błąd podczas wyszukiwania:", error));
    });
});
