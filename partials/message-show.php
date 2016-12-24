
<!-- messages -->
<?php if(isset($message)): ?>
<div class="<?= ($messageType) ? 'callout' : '' ?> <?= $messageType ?>">
  <p ><?= $message ?></p>
</div>
<?php endif; ?>
