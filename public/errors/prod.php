<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body style="margin: 0; padding: 0;">
    <div class="wrapper">
        <main class="page">
            <section class="error" style="height: 100%; display: flex; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            justify-content: center; align-items: center; flex-direction: column;">
                <div class="error__text" style="font-size: 72px; line-height: 64px"><?= $err_response ?></div>
                <div class="error__subtext" style="font-size: 36px"><?= $err_message ?></div>
                <a href="<?= PATH ?>" class="error__back">
                    Back to home page
                </a>
            </section>
        </main>
    </div>
</body>
</html>