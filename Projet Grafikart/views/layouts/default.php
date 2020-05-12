<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Mon site' ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>

<body class="d-flex flex-column h-100">

<nav class="navbar navbar-expend-lg navbar-dark bg-primary">
    <a href="/" class="navbar-brand">Mon site</a>
</nav>

<div class="container mt-4">
    <?= $content ?>
</div>

<footer class="bg-light py-4 footer mt-auto">
    <div class="container">
        <?php if(defined('DEBUG_TIME')): ?>
            Page générée en <?= ( round((microtime(true) - DEBUG_TIME) * 1000)) ?> ms
        <?php endif ?>
    </div>
</footer>
</body>
</html>