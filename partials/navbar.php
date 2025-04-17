<!-- ============================
       Navigation Bar
  ============================ -->
  
  <div class="nav fixed-top d-flex justify-content-between align-items-center px-3">
    <!-- Linke Seite: Logo + Titel -->
    <div class="d-flex align-items-center gap-2" style="min-width: 200px;">
      <img class="logo" src="/Social_App/assets/img/Owl_logo.svg" alt="Owl Logo">
      <h3 class="text-light mb-0">Social Owl</h3>
    </div>

    <!-- Mitte: Suchfeld -->
    <div class="flex-grow-1 mx-4">
      <form class="d-flex justify-content-center" role="search">
        <input class="form-control" style="border-radius: 48px; max-width: 400px;" type="search" placeholder="# Search">
      </form>
    </div>

    <!-- Rechte Seite: Notifications + Dropdown -->
    <div class="d-flex align-items-center gap-3">
      <div class="notification-container position-relative">
        <span class="icon"><i class="fa-solid fa-bell text-white"></i></span>
        <span class="notification-badge">3</span>
      </div>

      <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="/Social_App/assets/uploads/<?php echo $_SESSION["profile_img"] ?>" alt="Profilbild" width="32" height="32" class="rounded-circle me-2">
          <strong class="text-light"><?php echo $_SESSION["username"] ?></strong>
        </a>
        <ul class="dropdown-menu bg-dark dropdown-menu-end border border-secondary">
          <li><a class="dropdown-item text-light" data-bs-toggle="modal" data-bs-target="#profilModal">
              <i class="bi bi-person-circle me-2"></i>Profil
            </a></li>
          <li><a class="dropdown-item text-light" href="settings.html"><i class="bi bi-gear me-2"></i>Einstellungen</a></li>
          <li>
            <hr class="dropdown-divider border-light opacity-75">
          </li>
          <li><a class="dropdown-item text-danger" href="./logout.php"><i class="bi bi-box-arrow-right me-2"></i>Abmelden</a></li>
        </ul>
      </div>
    </div>
  </div>
