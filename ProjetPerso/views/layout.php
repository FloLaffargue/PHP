<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    header {
        height:80px;
        background-color: lightblue;
    }
</style>

<body>

    <header>
        <a href="<?= $router->url('accueil')?>">Accueil</a>    
        <p><?= $GLOBALS['title'] ?? '' ?></p>
    </header>
    <div>
        <?= $content ?>
    </div>
    <footer>
        <h1>Mon footer</h1>
    </footer>


</body>
</html>