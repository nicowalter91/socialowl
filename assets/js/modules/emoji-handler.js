/**
 * Modul: emoji-handler.js
 * Stellt Emoji-Picker und Emoji-Einfügen für Posts, Kommentare und Chat bereit.
 */
export class EmojiHandler {
    constructor() {
        // Emojis in Kategorien organisieren
        this.categories = {
            "Smileys": {
                icon: "😊",
                emojis: {
                    "😀": "grinsen",
                    "😃": "grinsen mit großen Augen",
                    "😄": "grinsen mit lachenden Augen",
                    "😁": "strahlendes Grinsen",
                    "😅": "grinsen mit Schweiß",
                    "😂": "Freudentränen",
                    "🤣": "am Boden rollen",
                    "😊": "lächelnd",
                    "😇": "Heiligenschein",
                    "🙂": "leicht lächelnd",
                    "😉": "zwinkern",
                    "😍": "verliebt",
                    "🥰": "mit Herzen",
                    "😘": "Kusshand"
                }
            },
            "Emotionen": {
                icon: "🥺",
                emojis: {
                    "😭": "weinen",
                    "😢": "traurig",
                    "😤": "wütend schnauben",
                    "😠": "verärgert",
                    "😡": "zornig",
                    "🤬": "fluchen",
                    "🥺": "flehend",
                    "😳": "errötet",
                    "🥴": "benommen",
                    "😵": "schwindelig",
                    "🤪": "wild",
                    "😎": "cool"
                }
            },
            "Herzen": {
                icon: "❤️",
                emojis: {
                    "❤️": "rotes Herz",
                    "🧡": "oranges Herz",
                    "💛": "gelbes Herz",
                    "💚": "grünes Herz",
                    "💙": "blaues Herz",
                    "💜": "violettes Herz",
                    "🖤": "schwarzes Herz",
                    "🤍": "weißes Herz",
                    "🤎": "braunes Herz",
                    "💖": "funkelndes Herz",
                    "💗": "wachsendes Herz",
                    "💓": "schlagendes Herz"
                }
            },
            "Gesten": {
                icon: "👍",
                emojis: {
                    "👍": "Daumen hoch",
                    "👎": "Daumen runter",
                    "👌": "OK",
                    "🤌": "italienische Geste",
                    "👋": "winken",
                    "🤝": "Handschlag",
                    "🙏": "gefaltete Hände",
                    "✌️": "Victory",
                    "🤘": "Rock on",
                    "👊": "Faust",
                    "💪": "Bizeps",
                    "🤗": "Umarmung"
                }
            },
            "Objekte": {
                icon: "🎉",
                emojis: {
                    "🎉": "Party Popper",
                    "✨": "Funken",
                    "⭐": "Stern",
                    "🌟": "leuchtender Stern",
                    "💫": "schwindelig",
                    "🔥": "Feuer",
                    "💯": "100 Punkte",
                    "💎": "Diamant",
                    "🎵": "Musiknote",
                    "🎸": "Gitarre",
                    "🎮": "Controller",
                    "📱": "Smartphone"
                }
            }
        };

        this.init();
    }

    createEmojiPicker() {
        const container = document.createElement('div');
        container.className = 'emoji-picker-content';

        // Suchleiste
        const searchContainer = document.createElement('div');
        searchContainer.className = 'emoji-search-container';
        const searchInput = document.createElement('input');
        searchInput.type = 'text';
        searchInput.className = 'form-control form-control-sm text-light';
        searchInput.placeholder = 'Emoji suchen...';
        searchContainer.appendChild(searchInput);
        container.appendChild(searchContainer);

        // Kategorien-Tabs
        const tabContainer = document.createElement('div');
        tabContainer.className = 'emoji-tabs';
        const tabList = document.createElement('div');
        tabList.className = 'emoji-category-tabs';
        tabContainer.appendChild(tabList);
        container.appendChild(tabContainer);

        // Emoji-Container mit Scrollbar
        const emojiContainer = document.createElement('div');
        emojiContainer.className = 'emoji-container';
        
        let isFirstTab = true;
        Object.entries(this.categories).forEach(([categoryName, category]) => {
            // Tab Button
            const tabButton = document.createElement('button');
            tabButton.type = 'button';
            tabButton.className = `emoji-category-tab ${isFirstTab ? 'active' : ''}`;
            tabButton.innerHTML = category.icon;
            tabButton.title = categoryName;
            tabButton.setAttribute('data-category', categoryName.toLowerCase());
            tabList.appendChild(tabButton);

            // Kategorie-Container mit Titel
            const categoryContainer = document.createElement('div');
            categoryContainer.className = `category-${categoryName.toLowerCase()} ${isFirstTab ? '' : 'd-none'}`;
            
            // Kategorie-Titel
            const categoryTitle = document.createElement('div');
            categoryTitle.className = 'emoji-category-title';
            categoryTitle.textContent = categoryName;
            categoryContainer.appendChild(categoryTitle);

            // Emoji Grid
            const categoryGrid = document.createElement('div');
            categoryGrid.className = 'emoji-grid';
            
            Object.entries(category.emojis).forEach(([emoji, description]) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.textContent = emoji;
                button.title = description;
                categoryGrid.appendChild(button);
            });

            categoryContainer.appendChild(categoryGrid);
            emojiContainer.appendChild(categoryContainer);
            isFirstTab = false;
        });

        container.appendChild(emojiContainer);
        return { container, searchInput };
    }

    initEmojiPickerEvents(picker, searchInput, textarea) {
        // Tab-Wechsel
        picker.querySelectorAll('.emoji-category-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                // Aktiven Tab aktualisieren
                picker.querySelectorAll('.emoji-category-tab').forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                // Entsprechende Kategorie anzeigen
                const categoryName = tab.getAttribute('data-category');
                picker.querySelectorAll('[class^="category-"]').forEach(category => {
                    category.classList.add('d-none');
                });
                picker.querySelector(`.category-${categoryName}`)?.classList.remove('d-none');
            });
        });

        // Emoji-Button-Klicks
        picker.querySelectorAll('.emoji-grid button').forEach(emojiBtn => {
            emojiBtn.addEventListener('click', () => {
                const emoji = emojiBtn.textContent;
                // Cursor-Position ermitteln
                const cursorPos = textarea.selectionStart;
                // Text vor und nach Cursor
                const textBefore = textarea.value.substring(0, cursorPos);
                const textAfter = textarea.value.substring(cursorPos);
                // Emoji einfügen
                textarea.value = textBefore + emoji + textAfter;
                
                // Cursor nach dem Emoji positionieren
                textarea.selectionStart = cursorPos + emoji.length;
                textarea.selectionEnd = cursorPos + emoji.length;
                
                // Fokus zurück aufs Textfeld
                textarea.focus();
                
                // Trigger input event für Zeichenzähler
                textarea.dispatchEvent(new Event('input'));
                
                // Picker schließen
                picker.classList.add('d-none');
            });
        });

        // Suche
        searchInput.addEventListener('input', () => {
            const query = searchInput.value.toLowerCase();
            let hasResults = false;

            // Alle Kategorien durchsuchen
            Object.entries(this.categories).forEach(([categoryName, category]) => {
                const categoryContainer = picker.querySelector(`.category-${categoryName.toLowerCase()}`);
                if (!categoryContainer) return;

                let categoryHasResults = false;
                categoryContainer.querySelectorAll('.emoji-grid button').forEach(btn => {
                    const emoji = btn.textContent;
                    const description = category.emojis[emoji]?.toLowerCase() || '';
                    const matches = description.includes(query) || emoji.includes(query);
                    btn.style.display = matches ? 'inline-block' : 'none';
                    if (matches) {
                        categoryHasResults = true;
                        hasResults = true;
                    }
                });

                // Kategorie anzeigen/verstecken
                categoryContainer.classList.toggle('d-none', !categoryHasResults);
                if (query) {
                    categoryContainer.querySelector('.emoji-category-title').style.display = 
                        categoryHasResults ? 'block' : 'none';
                } else {
                    categoryContainer.querySelector('.emoji-category-title').style.display = 'block';
                }
            });

            // Wenn die Suche leer ist, zum ersten Tab zurückkehren
            if (!query) {
                const firstTab = picker.querySelector('.emoji-category-tab');
                if (firstTab) firstTab.click();
            }
        });
    }

    initCommonEmojiPicker(btnElement, textarea, onEmojiSelect) {
        const picker = btnElement.closest('.position-relative')?.querySelector('.emoji-picker');
        if (!picker || !textarea) return;

        // Nur einen Klick-Handler pro Button zulassen
        if (!btnElement._emojiClickHandlerSet) {
            btnElement.addEventListener('click', (e) => {
                e.stopPropagation();
                // Andere Picker schließen
                document.querySelectorAll('.emoji-picker').forEach(p => {
                    if (p !== picker) p.classList.add('d-none');
                });
                let wasHidden = picker.classList.contains('d-none');
                // Picker-Inhalt erstellen, wenn noch nicht vorhanden
                if (picker.childElementCount === 0) {
                    const { container, searchInput } = this.createEmojiPicker();
                    picker.appendChild(container);
                    this.initEmojiPickerEvents(container, searchInput, textarea);
                    // Nach dem Erstellen: Picker anzeigen
                    picker.classList.remove('d-none');
                    wasHidden = false;
                } else {
                    picker.classList.toggle('d-none');
                }
            });
            btnElement._emojiClickHandlerSet = true;
        }

        // Schließen beim Klick außerhalb
        if (!picker._emojiOutsideHandlerSet) {
            document.addEventListener('click', (e) => {
                if (!picker.contains(e.target) && !btnElement.contains(e.target)) {
                    picker.classList.add('d-none');
                }
            });
            picker._emojiOutsideHandlerSet = true;
        }
    }

    initPostEmojiPicker() {
        const pickerBtn = document.getElementById('emoji-picker-btn');
        const textarea = document.querySelector('.tweet-input-box');
        
        if (pickerBtn && textarea) {
            this.initCommonEmojiPicker(pickerBtn, textarea);
        }
    }

    initCommentEmojiPicker() {
        document.querySelectorAll('.emoji-comment-btn').forEach(btn => {
            const textarea = btn.closest('form').querySelector('textarea');
            if (textarea) {
                this.initCommonEmojiPicker(btn, textarea);
            }
        });
    }

    initBioEmojiPicker() {
        const bioBtn = document.querySelector('.emoji-bio-btn');
        const textarea = document.getElementById('bio');
        
        if (bioBtn && textarea) {
            this.initCommonEmojiPicker(bioBtn, textarea, () => {
                // Bio Counter aktualisieren
                textarea.dispatchEvent(new Event('input'));
            });
        }
    }

    init() {
        this.initPostEmojiPicker();
        this.initCommentEmojiPicker();
        this.initBioEmojiPicker();
    }
}