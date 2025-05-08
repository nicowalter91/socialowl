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

    // Banner anzeigen, wenn Cookie noch nicht gesetzt ist
    const cookieConsentAccepted = Cookies.get('cookie_consent');
    const cookieBanner = document.getElementById('cookie-banner');
    
    console.log('Cookie consent status:', cookieConsentAccepted);
    console.log('Cookie banner element found:', cookieBanner !== null);
    
    if (!cookieConsentAccepted && cookieBanner) {
        console.log('Showing cookie banner in 1 second...');
        setTimeout(() => {
            cookieBanner.classList.add('show');
            console.log('Cookie banner should be visible now');
        }, 1000);
    } else {
        console.log('Cookie banner will not be shown because:', 
                   cookieConsentAccepted ? 'consent already given' : 'banner element not found');
    }

    // Event-Listener für Akzeptier-Button
    const acceptButton = document.getElementById('cookie-accept');
    if (acceptButton) {
        acceptButton.addEventListener('click', function() {
            Cookies.set('cookie_consent', 'accepted', 365); // Cookie für 365 Tage speichern
            cookieBanner.classList.remove('show');
        });
    }

    // Event-Listener für Schließen-Button
    const closeButton = document.getElementById('cookie-close');
    if (closeButton) {
        closeButton.addEventListener('click', function() {
            cookieBanner.classList.remove('show');
            // Cookie temporär speichern, damit der Banner nicht sofort wieder erscheint
            Cookies.set('cookie_consent', 'closed', 1);
        });
    }

    // Funktion zum Löschen der Cookie-Einstellungen
    // Diese Funktion kann in der Konsole aufgerufen werden mit: deleteCookieConsent()
    window.deleteCookieConsent = function() {
        Cookies.delete('cookie_consent');
        console.log('Cookie consent deleted via function call');
        alert('Cookie-Einstellungen wurden gelöscht. Bitte laden Sie die Seite neu, um den Cookie-Banner erneut anzuzeigen.');
        return 'Cookie-Einstellungen wurden gelöscht.';
    };
});