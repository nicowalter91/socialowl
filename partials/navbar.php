<!--
  Partial: Navigation Bar
  Modernisierte Hauptnavigation mit optimierten Farben, Animationen und responsivem Design.
-->

<!-- Updated Navigation Bar with modern UI/UX design -->
<div class="nav fixed-top d-flex justify-content-between align-items-center px-3 shadow-sm" style="background-color: #1c1f26;">
  <!-- Left Section: Logo + Title -->
  <div class="d-flex align-items-center gap-3" style="min-width: 200px;">
    <img class="logo" src="/Social_App/assets/img/Owl_logo.svg" alt="Owl Logo" style="width: 50px; height: 50px;">
    <h3 class="text-light mb-0 fw-bold">Social Owl</h3>
  </div>

  <!-- Center Section: Search Field -->
  <div class="flex-grow-1 mx-4 position-relative">
    <form id="post-search-form" class="d-flex justify-content-center" onsubmit="return false;" style="max-width: 500px; width: 100%;">
      <div class="input-group">
        <input class="form-control bg-dark text-light border-secondary rounded-start-pill shadow-sm" 
               id="post-search" 
               type="search" 
               placeholder="Search for text, @users, or #hashtags..." 
               aria-label="Search">
        <button class="btn btn-primary border-secondary rounded-end-pill shadow-sm" id="search-button" type="button">
          <i class="bi bi-search"></i>
        </button>
      </div>

      <!-- Search Results Container -->
      <div id="search-results" class="p-2 rounded d-none mt-2 w-100 shadow-lg" style="background-color: #28353e; position: absolute; top: 100%; left: 0; z-index: 1050;">
        <!-- Results will appear here -->
      </div>
    </form>
  </div>

  <!-- Right Section: Notifications + Dropdown -->
  <div class="d-flex align-items-center gap-4">
    <!-- Messaging Icon -->
    <div class="nav-item position-relative d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
      <a class="nav-link p-0 position-relative" href="#" data-bs-toggle="modal" data-bs-target="#chatModal" title="Chat">
        <i class="bi bi-envelope-fill fs-4 text-white"></i>
        <span id="chat-badge" class="notification-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none"></span>
      </a>
    </div>
    <div class="notification-container position-relative d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
      <button class="notification-bell-btn text-light p-0 position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-bell-fill fs-4"></i>
        <span class="notification-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notification-badge">1</span>
      </button>
      <div class="dropdown-menu dropdown-menu-end bg-dark border border-secondary shadow-lg" style="width: 320px;">
        <h6 class="dropdown-header text-light border-bottom border-secondary">Notifications</h6>
        <div id="notifications-list" class="notifications-list" style="max-height: 400px; overflow-y: auto;">
          <!-- Notifications will be dynamically inserted here -->
        </div>
      </div>
    </div>

    <div class="dropdown">
      <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="/Social_App/assets/uploads/<?php echo $_SESSION["profile_img"] ?>" alt="Profile Picture" width="36" height="36" class="rounded-circle me-2">
        <strong class="text-light fw-semibold"> <?php echo $_SESSION["username"] ?> </strong>
      </a>
      <ul class="dropdown-menu bg-dark dropdown-menu-end shadow-lg">
        <li><a class="dropdown-item text-light" href="#" data-bs-toggle="modal" data-bs-target="#profilModal">
            <i class="bi bi-person-circle me-2"></i>Profile
          </a></li>
        <hr class="dropdown-divider border-light opacity-75">
        </li>
        <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>/controllers/logout.php"><i class="bi bi-box-arrow-right text-danger me-2"></i>Logout</a></li>
      </ul>
    </div>
    <!-- Dark/Light Mode Toggle Button -->
    <button id="theme-toggle" class="btn btn-link text-light ms-2" title="Toggle Dark/Light Mode" aria-label="Toggle Dark/Light Mode">
      <i id="theme-toggle-icon" class="bi bi-moon-stars-fill fs-4"></i>
    </button>
  </div>
</div>