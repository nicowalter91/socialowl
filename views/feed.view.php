<?php
/**
 * View: Feed
 * Zeigt den Haupt-Feed mit allen Posts, Kommentarformular und Sidebar an.
 * Erwartet: $posts (Array mit Postdaten)
 */

if (isset($_GET["q"])): ?>
  <div class="alert alert-secondary text-dark fw-bold rounded-4 p-3">
    🔍 Ergebnisse für „<span class="text-primary"><?= htmlspecialchars($_GET["q"]) ?></span>“
  </div>
<?php endif; ?>


<div class="feed" id="feed" data-user-id="<?= $_SESSION["id"] ?>">
  <?php if (empty($posts)): ?>
    <div class="text-center mt-5 p-5">
      <i class="bi bi-chat-dots display-1 text-secondary"></i>
      <h4 class="mt-3 text-light">Noch keine Owls vorhanden.</h4>
      <p class="text-light">Folge Profilen, um Inhalte in deinem Feed zu sehen. </br> Oder schreibe deinen eigenen ersten Owl.</p>
    </div>
  <?php else: ?>
    <?php foreach ($posts as $post): ?>
      <?php include PARTIALS . '/post_card.php'; ?>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
