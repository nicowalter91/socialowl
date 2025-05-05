<?php
require_once __DIR__ . '/../includes/config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Social Owl</title>
  <meta name="description" content="Willkommen bei Social Owl - deinem sozialen Netzwerk für kreative Köpfe.">
  <meta name="theme-color" content="#0d6efd" />

  <!-- CSS -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bootstrap-icons.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
  <link rel="icon" href="<?= BASE_URL ?>/assets/img/Owl_logo.svg" type="image/x-icon">
</head>

<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark" style="background: #232946;">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#hero">
        <img src="<?= BASE_URL ?>/assets/img/Owl_logo.svg" alt="Logo" width="36" class="me-2 align-text-top">Social Owl
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
          <li class="nav-item"><a class="nav-link" href="#gallery">Galerie</a></li>
          <li class="nav-item"><a class="nav-link" href="#community">Community</a></li>
          <li class="nav-item"><a class="nav-link" href="#faq">FAQ</a></li>
          <li class="nav-item"><a class="nav-link" href="#kontakt">Kontakt</a></li>
        </ul>
        <div class="d-flex ms-lg-3 mt-3 mt-lg-0">
          <a href="<?= BASE_URL ?>/views/login.view.php" class="btn btn-outline-light me-2" style="border-color: #eebbc3; color: #eebbc3;">Login</a>
          <a href="<?= BASE_URL ?>/views/register.view.php" class="btn" style="background: #eebbc3; color: #232946; font-weight: bold;">Registrieren</a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section id="hero" class="text-white text-center py-5" style="background: linear-gradient(120deg, #232946 60%, #0d6efd 100%); min-height: 60vh; display: flex; flex-direction: column; justify-content: center; align-items: center;">
    <img src="<?= BASE_URL ?>/assets/img/Owl_logo.svg" alt="Social Owl Logo" width="90" class="mb-3 animate__animated animate__fadeInDown" style="background: #fff; border-radius: 50%; padding: 8px;">
    <h1 class="display-4 fw-bold animate__animated animate__fadeInUp" style="color: #eebbc3;">Willkommen bei Social Owl</h1>
    <p class="lead mb-4 animate__animated animate__fadeInUp animate__delay-1s" style="color: #f4f4f4;">Dein soziales Netzwerk für kreative Köpfe, Austausch & Inspiration.</p>
    <a href="<?= BASE_URL ?>/views/register.view.php" class="btn btn-lg shadow animate__animated animate__pulse animate__infinite" style="background: #eebbc3; color: #232946; font-weight: bold; border: none;">Jetzt kostenlos starten</a>
  </section>

  <!-- Features Section -->
  <section id="features" class="container py-5">
    <h2 class="text-center mb-5 fw-bold" style="color: #232946;">Was macht Social Owl besonders?</h2>
    <div class="row g-4">
      <div class="col-md-4 text-center">
        <div class="p-4 rounded shadow-sm h-100 animate__animated animate__fadeInUp" style="background: #eebbc3; color: #232946;">
          <i class="bi bi-chat-dots" style="font-size: 3rem; color: #232946;"></i>
          <h4 class="mt-3">Echtzeit-Chat</h4>
          <p>Unterhalte dich mit Freunden und neuen Kontakten in Echtzeit – überall, jederzeit.</p>
        </div>
      </div>
      <div class="col-md-4 text-center">
        <div class="p-4 rounded shadow-sm h-100 animate__animated animate__fadeInUp animate__delay-1s" style="background: #eebbc3; color: #232946;">
          <i class="bi bi-image" style="font-size: 3rem; color: #232946;"></i>
          <h4 class="mt-3">Bilder & Stories</h4>
          <p>Teile deine schönsten Momente, erstelle Stories und inspiriere die Community.</p>
        </div>
      </div>
      <div class="col-md-4 text-center">
        <div class="p-4 rounded shadow-sm h-100 animate__animated animate__fadeInUp animate__delay-2s" style="background: #eebbc3; color: #232946;">
          <i class="bi bi-bell" style="font-size: 3rem; color: #232946;"></i>
          <h4 class="mt-3">Benachrichtigungen</h4>
          <p>Verpasse keine Neuigkeiten mehr – du bist immer up to date!</p>
        </div>
      </div>
    </div>

    <!-- Weitere Features -->
    <div class="row justify-content-center mt-5">
      <div class="col-md-8">
        <h3 class="fw-bold mb-3" style="color: #232946;">Weitere Highlights</h3>
        <ul class="list-group list-group-flush" style="background: transparent;">
          <li class="list-group-item" style="background: transparent; color: #232946;"><i class="bi bi-shield-lock me-2" style="color: #0d6efd;"></i>Datenschutz & Privatsphäre: Deine Daten sind bei uns sicher und werden nicht weitergegeben.</li>
          <li class="list-group-item" style="background: transparent; color: #232946;"><i class="bi bi-emoji-smile me-2" style="color: #0d6efd;"></i>Individuelle Profile: Gestalte dein Profil mit Headerbild, Profilbild und Bio.</li>
          <li class="list-group-item" style="background: transparent; color: #232946;"><i class="bi bi-search me-2" style="color: #0d6efd;"></i>Intelligente Suche: Finde Nutzer, Beiträge und Hashtags blitzschnell.</li>
          <li class="list-group-item" style="background: transparent; color: #232946;"><i class="bi bi-heart me-2" style="color: #0d6efd;"></i>Likes & Kommentare: Interagiere mit Beiträgen und zeige deine Wertschätzung.</li>
          <li class="list-group-item" style="background: transparent; color: #232946;"><i class="bi bi-people me-2" style="color: #0d6efd;"></i>Folgen & Community: Baue dein Netzwerk auf und bleibe mit Freunden in Kontakt.</li>
          <li class="list-group-item" style="background: transparent; color: #232946;"><i class="bi bi-bell-fill me-2" style="color: #0d6efd;"></i>Echtzeit-Benachrichtigungen: Verpasse keine Aktivitäten mehr.</li>
          <li class="list-group-item" style="background: transparent; color: #232946;"><i class="bi bi-image me-2" style="color: #0d6efd;"></i>Medien-Upload: Teile Bilder und Fotos direkt in deinen Beiträgen.</li>
          <li class="list-group-item" style="background: transparent; color: #232946;"><i class="bi bi-moon-stars me-2" style="color: #0d6efd;"></i>Modernes Dark-Design: Angenehm für die Augen, inspiriert von der App.</li>
        </ul>
      </div>
    </div>
  </section>

  <!-- Gallery Section -->
  <section id="gallery" class="container py-5">
    <h2 class="text-center mb-5 fw-bold" style="color: #232946;">Einblicke in Social Owl</h2>
    <div id="socialOwlCarousel" class="carousel slide shadow rounded" data-bs-ride="carousel" style="max-width: 500px; margin: 0 auto; position: relative; background: #232946;">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="<?= BASE_URL ?>/assets/img/social_owl_page.png" class="d-block w-100 img-fluid rounded" style="max-height: 320px; object-fit: cover; background: #fff;" alt="Feed Ansicht">
        </div>
        <div class="carousel-item">
          <img src="<?= BASE_URL ?>/assets/img/social_owl_chat.png" class="d-block w-100 img-fluid rounded" style="max-height: 320px; object-fit: cover; background: #fff;" alt="Chat Ansicht">
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#socialOwlCarousel" data-bs-slide="prev" style="left: -120px; filter: invert(0);">
        <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: #232946; border-radius: 50%;"></span>
        <span class="visually-hidden">Vorheriges</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#socialOwlCarousel" data-bs-slide="next" style="right: -120px; filter: invert(0);">
        <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: #232946; border-radius: 50%;"></span>
        <span class="visually-hidden">Nächstes</span>
      </button>
    </div>
  </section>

  <!-- Team Section -->
  <section id="team" class="container py-5">
    <h2 class="text-center mb-5 fw-bold" style="color: #232946;">Das Team hinter Social Owl</h2>
    <div class="row justify-content-center g-4">
      <div class="col-6 col-md-3 text-center">
        <div class="p-3 rounded shadow-sm h-100" style="background: #f4f4f4; color: #232946;">
          <img src="<?= BASE_URL ?>/assets/img/profil.png" alt="Nico Walter" width="80" class="rounded-circle mb-2 border border-3" style="border-color: #232946;">
          <h5 class="fw-bold mb-1">Nico Walter</h5>
          <div class="small">Co-Founder &amp; Development</div>
        </div>
      </div>
      <div class="col-6 col-md-3 text-center">
        <div class="p-3 rounded shadow-sm h-100" style="background: #f4f4f4; color: #232946;">
          <img src="<?= BASE_URL ?>/assets/img/profil.png" alt="Georg Diesendorf" width="80" class="rounded-circle mb-2 border border-3" style="border-color: #232946;">
          <h5 class="fw-bold mb-1">Georg Diesendorf</h5>
          <div class="small">Co-Founder &amp; Backend</div>
        </div>
      </div>
      <div class="col-6 col-md-3 text-center">
        <div class="p-3 rounded shadow-sm h-100" style="background: #f4f4f4; color: #232946;">
          <img src="<?= BASE_URL ?>/assets/img/profil.png" alt="Andreas Wiegand" width="80" class="rounded-circle mb-2 border border-3" style="border-color: #232946;">
          <h5 class="fw-bold mb-1">Andreas Wiegand</h5>
          <div class="small">Co-Founder &amp; Frontend</div>
        </div>
      </div>
      <div class="col-6 col-md-3 text-center">
        <div class="p-3 rounded shadow-sm h-100" style="background: #f4f4f4; color: #232946;">
          <img src="<?= BASE_URL ?>/assets/img/profil.png" alt="Florian Prottengeier" width="80" class="rounded-circle mb-2 border border-3" style="border-color: #232946;">
          <h5 class="fw-bold mb-1">Florian Prottengeier</h5>
          <div class="small">Co-Founder &amp; Design</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Community Section -->
  <section id="community" class="py-5" style="background: #f4f4f4;">
    <div class="container">
      <h2 class="text-center mb-5 fw-bold" style="color: #232946;">Was unsere Community sagt</h2>
      <div class="row g-4 justify-content-center">
        <div class="col-md-4">
          <div class="p-4 rounded shadow-sm h-100 animate__animated animate__fadeInLeft" style="background: #fff; color: #232946;">
            <p class="mb-2">“Social Owl ist der perfekte Ort, um neue Leute kennenzulernen und kreativ zu sein!”</p>
            <div class="d-flex align-items-center">
              <img src="<?= BASE_URL ?>/assets/img/profil.png" alt="User" width="40" class="rounded-circle me-2">
              <span class="fw-bold">Anna, Designerin</span>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="p-4 rounded shadow-sm h-100 animate__animated animate__fadeInRight animate__delay-1s" style="background: #fff; color: #232946;">
            <p class="mb-2">“Die App ist super intuitiv und macht einfach Spaß!”</p>
            <div class="d-flex align-items-center">
              <img src="<?= BASE_URL ?>/assets/img/profil.png" alt="User" width="40" class="rounded-circle me-2">
              <span class="fw-bold">Max, Student</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ Section -->
  <section id="faq" class="container py-5">
    <h2 class="text-center mb-5 fw-bold" style="color: #232946;">Häufige Fragen</h2>
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="accordion" id="faqAccordion">
          <div class="accordion-item mb-3 shadow-sm rounded">
            <h2 class="accordion-header" id="faq1-heading">
              <button class="accordion-button rounded" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="true" aria-controls="faq1" style="background: #f4f4f4; color: #232946; font-weight: 600;">
                <i class="bi bi-patch-question me-2" style="color: #0d6efd;"></i>Ist Social Owl kostenlos?
              </button>
            </h2>
            <div id="faq1" class="accordion-collapse collapse show" aria-labelledby="faq1-heading" data-bs-parent="#faqAccordion">
              <div class="accordion-body" style="background: #fff; color: #232946;">Ja, die Nutzung von Social Owl ist komplett kostenlos.</div>
            </div>
          </div>
          <div class="accordion-item mb-3 shadow-sm rounded">
            <h2 class="accordion-header" id="faq2-heading">
              <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="false" aria-controls="faq2" style="background: #f4f4f4; color: #232946; font-weight: 600;">
                <i class="bi bi-person-badge me-2" style="color: #0d6efd;"></i>Wie kann ich mein Profil anpassen?
              </button>
            </h2>
            <div id="faq2" class="accordion-collapse collapse" aria-labelledby="faq2-heading" data-bs-parent="#faqAccordion">
              <div class="accordion-body" style="background: #fff; color: #232946;">Nach der Registrierung kannst du dein Profilbild, Header und weitere Infos jederzeit bearbeiten.</div>
            </div>
          </div>
          <div class="accordion-item mb-3 shadow-sm rounded">
            <h2 class="accordion-header" id="faq3-heading">
              <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="false" aria-controls="faq3" style="background: #f4f4f4; color: #232946; font-weight: 600;">
                <i class="bi bi-phone me-2" style="color: #0d6efd;"></i>Gibt es eine mobile App?
              </button>
            </h2>
            <div id="faq3" class="accordion-collapse collapse" aria-labelledby="faq3-heading" data-bs-parent="#faqAccordion">
              <div class="accordion-body" style="background: #fff; color: #232946;">Eine mobile App ist in Planung. Bis dahin kannst du Social Owl bequem im mobilen Browser nutzen.</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Kontakt Section -->
  <section id="kontakt" class="container py-5">
    <h2 class="text-center mb-5 fw-bold" style="color: #232946;">Kontakt</h2>
    <div class="row justify-content-center">
      <div class="col-md-6">
        <form>
          <div class="mb-3">
            <label for="name" class="form-label" style="color: #232946;">Name</label>
            <input type="text" class="form-control" id="name" placeholder="Dein Name" style="background: #f4f4f4; color: #232946;">
          </div>
          <div class="mb-3">
            <label for="email" class="form-label" style="color: #232946;">E-Mail</label>
            <input type="email" class="form-control" id="email" placeholder="deine@email.de" style="background: #f4f4f4; color: #232946;">
          </div>
          <div class="mb-3">
            <label for="message" class="form-label" style="color: #232946;">Nachricht</label>
            <textarea class="form-control" id="message" rows="4" placeholder="Deine Nachricht" style="background: #f4f4f4; color: #232946;"></textarea>
          </div>
          <button type="submit" class="btn" style="background: #eebbc3; color: #232946; font-weight: bold; border: none;">Absenden</button>
        </form>
      </div>
      <div class="col-md-6 d-flex align-items-center justify-content-center mt-4 mt-md-0">
        <div style="width:100%; min-height:320px; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 16px rgba(35,41,70,0.08);">
          <iframe title="Nürnberg, Veilhofstraße" src="https://www.openstreetmap.org/export/embed.html?bbox=11.0925%2C49.4475%2C11.1005%2C49.4515&amp;layer=mapnik&amp;marker=49.4495%2C11.0965" style="width:100%; height:320px; border:0;"></iframe>
        </div>
      </div>
    </div>
  </section>

  <!-- Call to Action -->
  <section class="text-center py-5" style="background: linear-gradient(120deg, #eebbc3 60%, #0d6efd 100%);">
    <h2 class="mb-4 fw-bold" style="color: #232946;">Werde Teil der Social Owl Community!</h2>
    <a href="<?= BASE_URL ?>/views/register.view.php" class="btn btn-lg shadow" style="background: #232946; color: #eebbc3; font-weight: bold; border: none;">Jetzt registrieren</a>
  </section>

  <!-- Footer -->
  <footer class="text-white text-center py-4 mt-0" style="background: #232946;">
    <div class="container">
      <div class="mb-2">
        <a href="#" class="text-white me-3"><i class="bi bi-facebook"></i></a>
        <a href="#" class="text-white me-3"><i class="bi bi-instagram"></i></a>
        <a href="#" class="text-white me-3"><i class="bi bi-twitter-x"></i></a>
      </div>
      <div>
        © 2025 Social Owl. Alle Rechte vorbehalten. |
        <a class="text-white text-decoration-underline" href="<?= BASE_URL ?>/privacy.php">Datenschutz</a> |
        <a class="text-white text-decoration-underline" href="<?= BASE_URL ?>/impressum.php">Impressum</a>
      </div>
    </div>
  </footer>

  <!-- JS & Animation CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <script src="<?= BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
  <script src="<?= BASE_URL ?>/assets/js/script.js"></script>
  <script src="<?= BASE_URL ?>/assets/js/notifications.js"></script>
  <script>
    // Smooth Scrolling für Navigation
    document.querySelectorAll('a.nav-link').forEach(link => {
      link.addEventListener('click', function(e) {
        const target = document.querySelector(this.getAttribute('href'));
        if(target) {
          e.preventDefault();
          window.scrollTo({
            top: target.offsetTop - 70,
            behavior: 'smooth'
          });
        }
      });
    });

    // Kleine Animation für die Galerie
    document.querySelectorAll('.gallery-img').forEach(img => {
      img.addEventListener('mouseover', () => img.classList.add('animate__pulse'));
      img.addEventListener('mouseout', () => img.classList.remove('animate__pulse'));
    });
  </script>
</body>

</html>