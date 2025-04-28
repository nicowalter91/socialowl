<?php if (empty($results)): ?>
  <div class="text-center mt-4 text-light">
    <p>🔍 Keine Ergebnisse gefunden für „<?= htmlspecialchars($query) ?>“</p>
  </div>
<?php else: ?>
  <?php foreach ($results as $post): ?>
    <?php $GLOBALS["post"] = $post; ?>
    <?php include PARTIALS . "/post_card.php"; ?>
  <?php endforeach; ?>
<?php endif; ?>
