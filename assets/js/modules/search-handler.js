/**
 * Modul: search-handler.js
 * Steuert die Live-Suche nach Posts, Nutzern und Hashtags im Suchfeld der Navigation.
 */

export class SearchHandler {
    constructor() {
        this.searchInput = document.getElementById("post-search");
        this.searchButton = document.getElementById("search-button");
        this.resultsContainer = document.getElementById("search-results");
        this.BASE_URL = '/Social_App';  // Base URL hinzufügen
        this.init();
    }

    init() {
        this.initSearchEvents();
    }

    initSearchEvents() {
        this.searchButton.addEventListener("click", () => this.performSearch());
        this.searchInput.addEventListener("keypress", (event) => {
            if (event.key === "Enter") {
                event.preventDefault();
                this.performSearch();
            }
        });

        // Live-Suche bei Eingabe
        this.searchInput.addEventListener("input", async (event) => {
            const query = event.target.value.trim();
            if (query === "") {
                this.resultsContainer.classList.add("d-none");
                return;
            }
            
            if (query.startsWith("@")) {
                await this.performUserSearch(query.substring(1));
            } else if (query.startsWith("#")) {
                await this.performHashtagSearch(query);
            } else {
                await this.performSearch();
            }
        });

        document.addEventListener("click", (event) => {
            if (
                !this.searchInput.contains(event.target) &&
                !this.searchButton.contains(event.target) &&
                !this.resultsContainer.contains(event.target)
            ) {
                this.resultsContainer.classList.add("d-none");
            }
        });
    }

    async performHashtagSearch(query) {
        try {
            const response = await fetch(`${this.BASE_URL}/controllers/search.post.php?q=${encodeURIComponent(query)}&type=hashtag`);
            if (!response.ok) throw new Error('Netzwerkfehler');
            const html = await response.text();
            
            this.resultsContainer.innerHTML = "";
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = html;
            
            const posts = tempDiv.querySelectorAll('.tweet-card');
            if (posts.length > 0) {
                const info = document.createElement("div");
                info.className = "text-light mb-2";
                info.textContent = `${posts.length} Posts mit ${query} gefunden:`;
                this.resultsContainer.appendChild(info);
                
                posts.forEach(post => {
                    const postId = post.getAttribute('data-post-id');
                    const username = post.getAttribute('data-username');
                    const textElement = post.querySelector('.post-text');
                    const text = textElement ? textElement.textContent : '';
                    this.createSearchResultCard(postId, username, text);
                });
                this.resultsContainer.classList.remove("d-none");
            } else {
                this.resultsContainer.innerHTML = `<div class='text-danger p-2'><i class='bi bi-exclamation-circle mb-1'></i> Keine Posts mit ${query} gefunden.</div>`;
                this.resultsContainer.classList.remove("d-none");
            }
        } catch (error) {
            console.error('Fehler bei der Hashtag-Suche:', error);
            this.resultsContainer.innerHTML = "<div class='text-danger p-2'><i class='bi bi-exclamation-circle mb-1'></i> Fehler bei der Suche.</div>";
            this.resultsContainer.classList.remove("d-none");
        }
    }

    async performUserSearch(query) {
        try {
            const response = await fetch(`${this.BASE_URL}/controllers/api/search_users.php?q=${encodeURIComponent(query)}`);
            if (!response.ok) throw new Error('Netzwerkfehler');
            const data = await response.json();
            
            this.resultsContainer.innerHTML = "";
            
            if (data.success && data.users && data.users.length > 0) {
                data.users.forEach(user => {
                    this.createUserResultCard(user);
                });
                this.resultsContainer.classList.remove("d-none");
            } else {
                this.resultsContainer.innerHTML = "<div class='text-danger p-2'><i class='bi bi-exclamation-circle mb-1'></i> Keine Benutzer gefunden.</div>";
                this.resultsContainer.classList.remove("d-none");
            }
        } catch (error) {
            console.error('Fehler bei der Benutzersuche:', error);
            this.resultsContainer.innerHTML = "<div class='text-danger p-2'><i class='bi bi-exclamation-circle mb-1'></i> Fehler bei der Suche.</div>";
            this.resultsContainer.classList.remove("d-none");
        }
    }

    createUserResultCard(user) {
        const card = document.createElement("div");
        card.className = "bg-dark rounded p-2 mb-2 search-result-card d-flex justify-content-between align-items-center";
        
        const userInfo = document.createElement("div");
        userInfo.className = "d-flex align-items-center";
        
        const profileImg = document.createElement("img");
        profileImg.src = `${this.BASE_URL}/assets/uploads/${user.profile_img}`;
        profileImg.className = "rounded-circle me-2";
        profileImg.width = 40;
        profileImg.height = 40;
        
        const userText = document.createElement("div");
        userText.innerHTML = `
            <strong class="text-light">@${user.username}</strong>
            ${user.bio ? `<p class="mb-0 text-light small">${user.bio}</p>` : ''}
        `;
        
        userInfo.appendChild(profileImg);
        userInfo.appendChild(userText);
        
        const followButton = document.createElement("button");
        followButton.className = `btn btn-sm ${user.is_following ? 'btn-outline-danger' : 'btn-outline-light'}`;
        followButton.innerHTML = `<i class="bi bi-person-${user.is_following ? 'x' : 'plus'}-fill"></i>`;
        
        followButton.addEventListener("click", async () => {
            const action = user.is_following ? 'unfollow' : 'follow';
            try {
                const response = await fetch(`${this.BASE_URL}/controllers/${action}_user.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `user_id=${user.id}`
                });
                
                if (response.ok) {
                    user.is_following = !user.is_following;
                    followButton.className = `btn btn-sm ${user.is_following ? 'btn-outline-danger' : 'btn-outline-light'}`;
                    followButton.innerHTML = `<i class="bi bi-person-${user.is_following ? 'x' : 'plus'}-fill"></i>`;
                    
                    // Update sidebar and stats
                    window.liveUpdates.updateFollowingSidebar(user.id);
                }
            } catch (error) {
                console.error('Fehler beim Folgen/Entfolgen:', error);
            }
        });
        
        card.appendChild(userInfo);
        card.appendChild(followButton);
        this.resultsContainer.appendChild(card);
    }

    performSearch() {
        const query = this.searchInput.value.toLowerCase();
        const posts = document.querySelectorAll(".tweet-card");

        this.resultsContainer.innerHTML = "";
        let found = 0;

        if (query.length === 0) {
            this.resultsContainer.classList.add("d-none");
            return;
        }

        posts.forEach((post) => {
            let textElement = post.querySelector(".post-text") || post.querySelector(".text-light");
            let text = textElement ? textElement.textContent.toLowerCase() : "";
            let username = post.dataset.username || "@unknown";

            if (text.includes(query)) {
                const postId = post.getAttribute("data-post-id");
                this.createSearchResultCard(postId, username, text);
                found++;
            }
        });

        if (found > 0) {
            const info = document.createElement("div");
            info.className = "text-light mb-2";
            info.textContent = `${found} Treffer gefunden:`;
            this.resultsContainer.prepend(info);
            this.resultsContainer.classList.remove("d-none");
        } else {
            this.resultsContainer.innerHTML = "<div class='text-danger'><i class='bi bi-exclamation-circle mb-1'></i> Keine Treffer gefunden.</div>";
            this.resultsContainer.classList.remove("d-none");
        }
    }

    createSearchResultCard(postId, username, text) {
        const card = document.createElement("div");
        card.className = "bg-dark rounded p-2 mb-2 search-result-card";

        const link = document.createElement("a");
        link.href = `#post-${postId}`;
        link.className = "text-light text-decoration-none d-block";

        const title = document.createElement("div");
        title.className = "fw-bold";
        title.textContent = username;

        const snippet = document.createElement("small");
        snippet.className = "d-block text-light";
        snippet.textContent = text.substring(0, 80) + (text.length > 80 ? "..." : "");

        link.appendChild(title);
        link.appendChild(snippet);
        card.appendChild(link);

        link.addEventListener("click", (e) => {
            e.preventDefault();
            const target = document.getElementById(`post-${postId}`);
            if (target) {
                target.scrollIntoView({ behavior: "smooth", block: "start" });
                target.classList.add("highlight-post");
                setTimeout(() => {
                    target.classList.remove("highlight-post");
                }, 2000);
            }
            this.resultsContainer.classList.add("d-none");
        });

        this.resultsContainer.appendChild(card);
    }
}