<?php if (isset($this->meta['description'])): ?>
<meta name="description" content="<?=  htmlspecialchars($this->meta['description'], ENT_QUOTES, 'UTF-8'); ?>">
<?php endif;?>
<?php if (isset($this->meta['keywords'])): ?>
<meta name="keywords" content="<?= htmlspecialchars($this->meta['keywords'], ENT_QUOTES, 'UTF-8'); ?>">
<?php endif;?>
<?php if (isset($this->meta['title'])): ?>
<title><?=  htmlspecialchars($this->meta['title'], ENT_QUOTES, 'UTF-8'); ?></title>
<?php endif;?>