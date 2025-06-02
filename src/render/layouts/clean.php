<!DOCTYPE html>
<html lang="en">
<head>
    <?php if (isset($this->meta['description'])): ?>
    <meta name="description" content="<?=  htmlspecialchars($this->meta['description'], ENT_QUOTES, 'UTF-8'); ?>">
    <?php endif;?>
    <?php if (isset($this->meta['keywords'])): ?>
    <meta name="keywords" content="<?= htmlspecialchars($this->meta['keywords'], ENT_QUOTES, 'UTF-8'); ?>">
    <?php endif;?>
    <?php if (isset($this->meta['title'])): ?>
    <title><?=  htmlspecialchars($this->meta['title'], ENT_QUOTES, 'UTF-8'); ?></title>
    <?php endif;?>
    <base href="/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.min.css"/>
    <link rel="stylesheet" href="css/extra.css"/>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <script src="js/app.min.js" defer></script>
    <script type="module" src="js/script.js" defer></script>
</head>
<?php
preg_match("/.*\\\\(.*?)Controller/", $this->route->getController(), $page);
?>
<body data-page=<?= lcfirst($page[1]); ?>>
    <div class="wrapper">
        <?= $contentCallback() ?>
    </div>
    <div class="notification">
        <div class="notification__container">
        </div>
    </div>
    <template id="notification-item-template">
        <div class="notification__item">
            <div class="notification__message"></div>
            <button class="notification__close"></button>
            <div class="notification__time"></div>
        </div>
    </template>
    <div class="loader-wrapper">
        <div class="loader"></div>
    </div>
</body>
</html>