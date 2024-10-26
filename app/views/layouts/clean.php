<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->getMeta() ?>
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
        <?= $view ?>
    </div>
    <div class="notification">
        <div class="notification__container">
        </div>
    </div>
    <script src="js/app.min.js?_v=20240719131907"></script>
    <script src="js/script.js"></script>
</body>
</html>