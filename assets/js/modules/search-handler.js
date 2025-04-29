// Suchfunktionalität
export class SearchHandler {
    constructor() {
        this.searchInput = document.getElementById("post-search");
        this.searchButton = document.getElementById("search-button");
        this.resultsContainer = document.getElementById("search-results");
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

        this.searchInput.addEventListener("input", (event) => {
            if (event.target.value.trim() === "") {
                this.resultsContainer.classList.add("d-none");
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
            this.resultsContainer.innerHTML =
                "<div class='text-danger'><i class='bi bi-exclamation-circle mb-1'></i> Keine Treffer gefunden.</div>";
            this.resultsContainer.classList.remove("d-none");
        }

        this.searchInput.value = "";
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