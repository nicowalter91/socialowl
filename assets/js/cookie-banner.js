document.addEventListener('DOMContentLoaded', function() {
    console.log('Cookie-Banner Script geladen');
    
    // Cookie-Helfer-Funktionen
    const Cookies = {
        set: function(name, value, days) {
            let expires = '';
            if (days) {
                const date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = '; expires=' + date.toUTCString();
            }
            document.cookie = name + '=' + (value || '') + expires + '; path=/; SameSite=Strict';
        },
        get: function(name) {
            const nameEQ = name + '=';
            const ca = document.cookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        },
        delete: function(name) {
            // Löscht ein Cookie, indem das Ablaufdatum in die Vergangenheit gesetzt wird
            document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; SameSite=Strict';
            console.log('Cookie "' + name + '" wurde gelöscht');
        }
    };

    // Cookie-Kategorien und ihre Standardeinstellungen
    const cookieCategories = {
        essential: {
            name: 'Essentielle Cookies',
            description: 'Diese Cookies sind notwendig, damit die Website funktioniert und können nicht deaktiviert werden.',
            required: true,
            default: true
        },
        functional: {
            name: 'Funktionale Cookies',
            description: 'Diese Cookies ermöglichen erweiterte Funktionen und Personalisierung.',
            required: false,
            default: true
        },
        analytics: {
            name: 'Analyse-Cookies',
            description: 'Diese Cookies helfen uns, die Nutzung unserer Website zu verstehen und zu verbessern.',
            required: false,
            default: true
        },
        marketing: {
            name: 'Marketing-Cookies',
            description: 'Diese Cookies werden verwendet, um Werbung zu personalisieren.',
            required: false,
            default: false
        }
    };

    // Banner anzeigen, wenn Cookie noch nicht gesetzt ist
    const cookieConsentData = Cookies.get('cookie_consent_settings');
    let cookieSettings = null;
    
    try {
        if (cookieConsentData) {
            cookieSettings = JSON.parse(cookieConsentData);
        }
    } catch (e) {
        console.error('Fehler beim Parsen der Cookie-Einstellungen:', e);
    }

    const cookieBanner = document.getElementById('cookie-banner');
    
    console.log('Cookie consent status:', cookieSettings ? 'vorhanden' : 'nicht vorhanden');
    console.log('Cookie banner element found:', cookieBanner !== null);
    
    if (!cookieSettings && cookieBanner) {
        console.log('Showing cookie banner in 1 second...');
        setTimeout(() => {
            cookieBanner.classList.add('show');
            console.log('Cookie banner should be visible now');
        }, 1000);
    } else {
        console.log('Cookie banner will not be shown because:', 
                   cookieSettings ? 'consent already given' : 'banner element not found');
    }

    // Initialisiere Cookie-Banner mit detaillierten Einstellungen
    function initializeCookieBanner() {
        if (!cookieBanner) return;
        
        // HTML für Banner mit erweiterten Einstellungsmöglichkeiten erstellen
        cookieBanner.innerHTML = `
            <div class="cookie-banner-header">
                <i class="bi bi-cookie cookie-icon"></i>
                <h3 class="cookie-banner-title">Cookie-Einstellungen</h3>
            </div>
            <p class="cookie-banner-text">
                Wir verwenden Cookies, um Ihnen die bestmögliche Erfahrung auf unserer Website zu bieten.
                Sie können Ihre Cookie-Einstellungen individuell anpassen.
            </p>
            <div class="cookie-banner-buttons">
                <button id="cookie-accept-all" class="cookie-accept-btn">Alle akzeptieren</button>
                <button id="cookie-close" class="cookie-close-btn">Schließen</button>
            </div>
            <div class="cookie-settings-toggle" id="cookie-settings-toggle">Einstellungen anpassen</div>
            <div class="cookie-settings" id="cookie-settings">
                <!-- Hier werden die Cookie-Kategorien dynamisch eingefügt -->
            </div>
        `;

        // Cookie-Kategorien hinzufügen
        const cookieSettingsContainer = document.getElementById('cookie-settings');
        
        for (const [category, info] of Object.entries(cookieCategories)) {
            const categoryElement = document.createElement('div');
            categoryElement.className = 'cookie-category';
            
            categoryElement.innerHTML = `
                <div class="cookie-category-title">
                    <span>${info.name}</span>
                    <label class="cookie-switch">
                        <input type="checkbox" id="cookie-${category}" 
                               ${info.required ? 'checked disabled' : ''} 
                               ${info.default ? 'checked' : ''}>
                        <span class="cookie-switch-slider"></span>
                    </label>
                </div>
                <p class="cookie-category-description">${info.description}</p>
            `;
            
            cookieSettingsContainer.appendChild(categoryElement);
        }
        
        // Save Button für detaillierte Einstellungen hinzufügen
        const saveButtonDiv = document.createElement('div');
        saveButtonDiv.className = 'cookie-buttons-group';
        saveButtonDiv.innerHTML = `
            <button id="cookie-save-settings" class="cookie-save-btn">Einstellungen speichern</button>
        `;
        cookieSettingsContainer.appendChild(saveButtonDiv);
        
        // Event-Listener für Einstellungen anzeigen/verbergen
        const settingsToggle = document.getElementById('cookie-settings-toggle');
        settingsToggle.addEventListener('click', function() {
            cookieSettingsContainer.classList.toggle('show');
        });
    }
    
    initializeCookieBanner();

    // Event-Listener für "Alle akzeptieren"-Button
    const acceptAllButton = document.getElementById('cookie-accept-all');
    if (acceptAllButton) {
        acceptAllButton.addEventListener('click', function() {
            const settings = {};
            
            // Alle Kategorien auf "akzeptiert" setzen
            for (const category of Object.keys(cookieCategories)) {
                settings[category] = true;
            }
            
            saveCookieSettings(settings);
            cookieBanner.classList.remove('show');
        });
    }
    
    // Event-Listener für Speichern-Button
    const saveSettingsButton = document.getElementById('cookie-save-settings');
    if (saveSettingsButton) {
        saveSettingsButton.addEventListener('click', function() {
            const settings = {};
            
            // Werte der Checkboxen auslesen
            for (const category of Object.keys(cookieCategories)) {
                const checkbox = document.getElementById(`cookie-${category}`);
                settings[category] = checkbox ? checkbox.checked : cookieCategories[category].default;
            }
            
            saveCookieSettings(settings);
            cookieBanner.classList.remove('show');
        });
    }

    // Event-Listener für Schließen-Button
    const closeButton = document.getElementById('cookie-close');
    if (closeButton) {
        closeButton.addEventListener('click', function() {
            cookieBanner.classList.remove('show');
            // Cookie temporär speichern, damit der Banner nicht sofort wieder erscheint
            Cookies.set('cookie_consent_temp', 'closed', 1);
        });
    }
    
    // Funktion zum Speichern der Cookie-Einstellungen
    function saveCookieSettings(settings) {
        Cookies.set('cookie_consent_settings', JSON.stringify(settings), 365);
        console.log('Cookie settings saved:', settings);
        
        // Hier können Sie Aktionen basierend auf den gespeicherten Einstellungen durchführen
        // z.B. Analytics starten oder deaktivieren
        applyCookieSettings(settings);
    }
    
    // Funktion zum Anwenden der Cookie-Einstellungen
    function applyCookieSettings(settings) {
        console.log('Applying cookie settings:', settings);
        
        // Beispiel: Google Analytics nur aktivieren, wenn analytics-Cookies erlaubt sind
        if (settings && settings.analytics) {
            console.log('Analytics-Cookies sind erlaubt - Analytics könnte hier gestartet werden');
            // Hier könnte der Analytics-Code initialisiert werden
        } else {
            console.log('Analytics-Cookies sind nicht erlaubt - Analytics wird deaktiviert');
            // Hier könnte Analytics deaktiviert werden
        }
        
        // Gleiche Logik für andere Cookie-Typen...
    }
    
    // Falls bereits Cookie-Einstellungen vorhanden sind, diese anwenden
    if (cookieSettings) {
        applyCookieSettings(cookieSettings);
    }

    // Funktion zum Löschen der Cookie-Einstellungen
    // Diese Funktion kann in der Konsole aufgerufen werden mit: deleteCookieConsent()
    window.deleteCookieConsent = function() {
        Cookies.delete('cookie_consent_settings');
        Cookies.delete('cookie_consent_temp');
        console.log('Cookie consent deleted via function call');
        alert('Cookie-Einstellungen wurden gelöscht. Bitte laden Sie die Seite neu, um den Cookie-Banner erneut anzuzeigen.');
        return 'Cookie-Einstellungen wurden gelöscht.';
    };
});