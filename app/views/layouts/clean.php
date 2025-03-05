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
    <link rel="stylesheet" href="css/style.min.css?_v=20240719131907"/>
    <link rel="stylesheet" href="css/extra.css"/>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <meta charset="UTF-8">
</head>
<body>
    <div class="wrapper">
        <?= $content ?>
    </div>
    <div class="notification">
        <div class="notification__container">
        </div>
    </div>
    <div class="loader-wrapper">
        <div class="loader"></div>
    </div>
    <script src="js/app.min.js?_v=20240719131907"></script>
    <script type="module" src="js/script.js"></script>
</body>
</html>