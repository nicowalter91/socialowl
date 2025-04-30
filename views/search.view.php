<?php
/**
 * View: Suche
 * Zeigt die Suchergebnisse für Posts und Nutzer an.
 * Erwartet: $results (Array mit Suchergebnissen)
 */
if (empty($results)): ?>
  <div class="text-center mt-4 text-light">
    <p>🔍 Keine Ergebnisse gefunden für „<?= htmlspecialchars($query) ?>“</p>
  </div>
<?php else: ?>
  <?php foreach ($results as $post): ?>
    <?php $GLOBALS["post"] = $post; ?>
    <?php include PARTIALS . "/post_card.php"; ?>
  <?php endforeach; ?>
<?php endif; ?>
