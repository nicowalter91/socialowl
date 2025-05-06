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
        this.searchButton.addEventListener("click", () => {
            const query = this.searchInput.value.trim();
            
            if (query.startsWith("@")) {
                this.performUserSearch(query.substring(1));
            } else if (query.startsWith("#")) {
                this.performHashtagSearch(query);
            } else {
                this.performSearch();
            }
        });
        
        this.searchInput.addEventListener("keypress", (event) => {
            if (event.key === "Enter") {
                event.preventDefault();
                const query = this.searchInput.value.trim();
                
                if (query.startsWith("@")) {
                    this.performUserSearch(query.substring(1));
                } else if (query.startsWith("#")) {
                    this.performHashtagSearch(query);
                } else {
                    this.performSearch();
                }
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
                // Änderung: text-light entfernt für Theme-Support
                info.className = "mb-2";
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
                this.resultsContainer.innerHTML = `<div class='p-2'><i class='bi bi-exclamation-circle mb-1'></i> Keine Posts mit ${query} gefunden.</div>`;
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
            // Debugmeldung anzeigen
            console.log(`Suche nach Benutzer mit Query: ${query}`);
            
            // API-URL mit vollständigem Pfad
            const apiUrl = `${this.BASE_URL}/controllers/api/search_users.php?q=${encodeURIComponent(query)}`;
            console.log(`API-URL: ${apiUrl}`);
            
            // Feedback für User zeigen - Änderung: text-light entfernt
            this.resultsContainer.innerHTML = "<div class='p-2'><i class='bi bi-hourglass-split mb-1'></i> Suche läuft...</div>";
            this.resultsContainer.classList.remove("d-none");
            
            const response = await fetch(apiUrl);
            
            if (!response.ok) {
                console.error(`Netzwerkfehler: ${response.status} ${response.statusText}`);
                throw new Error(`Netzwerkfehler: ${response.status}`);
            }
            
            const responseText = await response.text();
            console.log("API-Antwort (Text):", responseText);
            
            let data;
            try {
                data = JSON.parse(responseText);
                console.log("API-Antwort (JSON):", data);
            } catch (e) {
                console.error("JSON Parse Error:", e);
                throw new Error(`Fehler beim Parsen der Antwort: ${e.message}`);
            }
            
            this.resultsContainer.innerHTML = "";
            
            if (data.success && data.users && data.users.length > 0) {
                const info = document.createElement("div");
                // Änderung: text-light entfernt
                info.className = "mb-2";
                info.textContent = `${data.users.length} Benutzer gefunden:`;
                this.resultsContainer.appendChild(info);
                
                data.users.forEach(user => {
                    this.createUserResultCard(user);
                });
                this.resultsContainer.classList.remove("d-none");
            } else {
                this.resultsContainer.innerHTML = `
                    <div class='text-danger p-2'>
                        <i class='bi bi-exclamation-circle mb-1'></i> 
                        Keine Benutzer für "${query}" gefunden.
                    </div>`;
                this.resultsContainer.classList.remove("d-none");
                console.log("Keine Benutzer gefunden oder data.success ist false", data);
            }
        } catch (error) {
            console.error('Fehler bei der Benutzersuche:', error);
            this.resultsContainer.innerHTML = `
                <div class='text-danger p-2'>
                    <i class='bi bi-exclamation-circle mb-1'></i> 
                    Fehler bei der Suche: ${error.message}
                </div>`;
            this.resultsContainer.classList.remove("d-none");
        }
    }

    createUserResultCard(user) {
        const card = document.createElement("div");
        card.className = "theme-card rounded p-2 mb-2 search-result-card d-flex justify-content-between align-items-center";
        
        const userInfo = document.createElement("div");
        userInfo.className = "d-flex align-items-center";
        
        const profileImg = document.createElement("img");
        profileImg.src = `${this.BASE_URL}/assets/uploads/${user.profile_img}`;
        profileImg.className = "rounded-circle me-2 border border-secondary";
        profileImg.width = 40;
        profileImg.height = 40;
        
        const userText = document.createElement("div");
        userText.innerHTML = `
            <strong>@${user.username}</strong>
            ${user.bio ? `<p class="mb-0 small">${user.bio}</p>` : ''}
        `;
        
        userInfo.appendChild(profileImg);
        userInfo.appendChild(userText);
        
        // Rechte Seite mit Follow-Button und Status
        const rightSection = document.createElement("div");
        rightSection.className = "d-flex align-items-center gap-2";
        
        // Status-Badge für Follower-Anfragen
        if (user.follow_request_status) {
            const statusBadge = document.createElement("div");
            
            if (user.follow_request_status === 'accepted') {
                statusBadge.className = "badge bg-success d-flex align-items-center";
                statusBadge.innerHTML = '<i class="bi bi-check-circle me-1"></i> Akzeptiert';
            } else if (user.follow_request_status === 'pending') {
                statusBadge.className = "badge bg-warning text-dark d-flex align-items-center";
                statusBadge.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Ausstehend';
            }
            
            rightSection.appendChild(statusBadge);
        }
        
        // Follow/Unfollow Button
        const followButton = document.createElement("button");
        followButton.className = `btn btn-sm ${user.is_following ? 'btn-outline-danger' : 'btn-outline-success'} rounded-pill d-flex align-items-center`;
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
                    followButton.className = `btn btn-sm ${user.is_following ? 'btn-outline-danger' : 'btn-outline-success'} rounded-pill d-flex align-items-center`;
                    followButton.innerHTML = `<i class="bi bi-person-${user.is_following ? 'x' : 'plus'}-fill"></i>`;
                    
                    // Wenn der Benutzer jetzt folgt, Status auf "ausstehend" setzen
                    if (user.is_following) {
                        user.follow_request_status = 'pending';
                        
                        // Status-Badge hinzufügen oder aktualisieren
                        let statusBadge = rightSection.querySelector(".badge");
                        if (!statusBadge) {
                            statusBadge = document.createElement("div");
                            statusBadge.className = "badge bg-warning text-dark d-flex align-items-center";
                            statusBadge.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Ausstehend';
                            rightSection.insertBefore(statusBadge, followButton);
                        } else {
                            statusBadge.className = "badge bg-warning text-dark d-flex align-items-center";
                            statusBadge.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Ausstehend';
                        }
                    } else {
                        // Wenn entfolgt wird, Status-Badge entfernen
                        user.follow_request_status = null;
                        const statusBadge = rightSection.querySelector(".badge");
                        if (statusBadge) {
                            rightSection.removeChild(statusBadge);
                        }
                    }
                    
                    // Update sidebar and stats
                    if (window.liveUpdates) {
                        window.liveUpdates.updateFollowingSidebar(user.id);
                    }
                }
            } catch (error) {
                console.error('Fehler beim Folgen/Entfolgen:', error);
            }
        });
        
        rightSection.appendChild(followButton);
        card.appendChild(userInfo);
        card.appendChild(rightSection);
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
            // Änderung: text-light entfernt für Theme-Support
            info.className = "mb-2";
            info.textContent = `${found} Treffer gefunden:`;
            this.resultsContainer.prepend(info);
            this.resultsContainer.classList.remove("d-none");
        } else {
            // Der Fehler kann rot bleiben zur besseren Sichtbarkeit
            this.resultsContainer.innerHTML = "<div class='text-danger p-2'><i class='bi bi-exclamation-circle mb-1'></i> Keine Treffer gefunden.</div>";
            this.resultsContainer.classList.remove("d-none");
        }
    }

    createSearchResultCard(postId, username, text) {
        const card = document.createElement("div");
        card.className = "theme-card rounded p-2 mb-2 search-result-card";

        const link = document.createElement("a");
        link.href = `#post-${postId}`;
        link.className = "text-decoration-none d-block";

        const title = document.createElement("div");
        title.className = "fw-bold";
        title.textContent = username;

        const snippet = document.createElement("small");
        snippet.className = "d-block";
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