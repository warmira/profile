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
                    `;
                    searchResults.appendChild(profileCard);
                });
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
});
