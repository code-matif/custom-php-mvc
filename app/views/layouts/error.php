<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error <?= $code ?></title>
</head>
<body>
    <header>
        <h1>Error <?= $code ?></h1>
    </header>

    <main>
        <?= $content ?>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> My Custom PHP Framework</p>
    </footer>
</body>
</html>
