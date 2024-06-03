document.addEventListener('DOMContentLoaded', () => {
    const searchForm = document.getElementById('search-form');
    const searchResults = document.getElementById('search-results');

    searchForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const searchQuery = document.getElementById('search').value;

        fetch(`search.php?search=${encodeURIComponent(searchQuery)}`)
            .then(response => response.json())
            .then(data => {
                searchResults.innerHTML = '';
                data.forEach(profile => {
                    const profileCard = document.createElement('div');
                    profileCard.classList.add('profile-card');
                    profileCard.innerHTML = `
                        <img src="${profile.URL || 'default-profile.png'}" alt="Zdjęcie profilowe">
                        <p>${profile.firstName} ${profile.lastName}</p>
                        <button class="message-button" data-user-id="${profile.userId}">Wyślij wiadomość</button>
                    `;
                    searchResults.appendChild(profileCard);
                });

                document.querySelectorAll('.message-button').forEach(button => {
                    button.addEventListener('click', (event) => {
                        const userId = event.target.dataset.userId;
                        window.location.href = `message.html`;
                    });
                });
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
});
