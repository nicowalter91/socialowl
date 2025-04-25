<div class="feed">
  <?php if (empty($posts)): ?>
    <div class="text-center mt-5 p-5">
      <i class="bi bi-chat-dots display-1 text-secondary"></i>
      <h4 class="mt-3 text-light">Noch keine Owls vorhanden.</h4>
      <p class="text-light">Folge Profilen, um Inhalte in deinem Feed zu sehen.</p>
    </div>
  <?php else: ?>
    <?php foreach ($posts as $post): ?>
      <?php include PARTIALS . '/post_card.php'; ?>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
