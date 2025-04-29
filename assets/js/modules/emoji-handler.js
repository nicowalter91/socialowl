/**
 * Emoji-Funktionalität
 * Verwaltet die Emoji-Picker für Posts und Kommentare
 * Ermöglicht das Einfügen von Emojis in Textfelder
 */
export class EmojiHandler {
    /**
     * Initialisiert den EmojiHandler
     * Definiert die verfügbaren Emojis und deren Namen
     */
    constructor() {
        this.emojiNames = {
            "😀": "grinsen",
            "😂": "lachen",
            "😍": "verliebt",
            "😎": "cool",
            "😭": "weinen",
            "😡": "wütend",
            "👍": "daumen hoch",
            "❤️": "herz",
            "🔥": "feuer",
            "🎉": "feier",
            "👏": "applaus",
            "💯": "hundert",
        };
        this.init();
    }

    /**
     * Initialisiert die Emoji-Picker für Posts und Kommentare
     */
    init() {
        this.initPostEmojiPicker();
        this.initCommentEmojiPicker();
    }

    /**
     * Initialisiert den Emoji-Picker für Posts
     * - Öffnen/Schließen des Pickers
     * - Einfügen von Emojis
     * - Suche nach Emojis
     */
    initPostEmojiPicker() {
        const pickerBtn = document.getElementById("emoji-picker-btn");
        const picker = document.getElementById("emoji-picker");
        const tweetArea = document.querySelector(".tweet-input-box");

        if (pickerBtn && picker && tweetArea) {
            // Event-Listener für das Öffnen/Schließen des Pickers
            pickerBtn.addEventListener("click", (e) => {
                e.stopPropagation();
                picker.classList.toggle("d-none");
            });

            // Event-Listener für das Einfügen von Emojis
            picker.querySelectorAll("button").forEach((btn) => {
                btn.addEventListener("click", () => {
                    const emoji = btn.textContent;
                    const start = tweetArea.selectionStart;
                    const end = tweetArea.selectionEnd;
                    const text = tweetArea.value;
                    tweetArea.value = text.slice(0, start) + emoji + text.slice(end);
                    tweetArea.focus();
                    tweetArea.selectionStart = tweetArea.selectionEnd = start + emoji.length;
                    picker.classList.add("d-none");
                });
            });

            // Event-Listener für die Emoji-Suche
            const emojiSearch = document.getElementById("emoji-search");
            emojiSearch?.addEventListener("input", () => {
                const query = emojiSearch.value.toLowerCase();
                picker.querySelectorAll("button").forEach((btn) => {
                    const emoji = btn.textContent;
                    const name = this.emojiNames[emoji] || "";
                    btn.style.display = name.includes(query) ? "inline-block" : "none";
                });
            });

            // Event-Listener für das Schließen des Pickers beim Klick außerhalb
            document.addEventListener("click", (e) => {
                if (!picker.contains(e.target) && !pickerBtn.contains(e.target)) {
                    picker.classList.add("d-none");
                }
            });
        }
    }

    /**
     * Initialisiert den Emoji-Picker für Kommentare
     * - Öffnen/Schließen des Pickers
     * - Einfügen von Emojis
     * - Dynamisches Erstellen der Emoji-Buttons
     */
    initCommentEmojiPicker() {
        document.querySelectorAll(".emoji-comment-btn").forEach((btn) => {
            btn.addEventListener("click", () => {
                const form = btn.closest("form");
                const picker = form.querySelector(".emoji-picker");
                const textarea = form.querySelector("textarea");

                if (!picker || !textarea) return;

                picker.classList.toggle("d-none");

                // Emoji-Buttons nur einmal erstellen
                if (picker.childElementCount === 0) {
                    const grid = document.createElement("div");
                    grid.classList.add("emoji-grid");
                    Object.keys(this.emojiNames).forEach((emoji) => {
                        const button = document.createElement("button");
                        button.type = "button";
                        button.textContent = emoji;
                        button.addEventListener("click", () => {
                            textarea.value += emoji;
                            picker.classList.add("d-none");
                        });
                        grid.appendChild(button);
                    });
                    picker.appendChild(grid);
                }
            });
        });
    }
} 