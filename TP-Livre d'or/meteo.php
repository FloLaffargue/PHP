<?php
declare(strict_types=1);
require_once __DIR__ . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'OpenWeather.php'; 
$weather = new OpenWeather('caca2368cb8ab1f996ae23ba26d564f0e433');
$error = null;
try {
    $donnees = explode(' ');
    $data = $weather->getForecasts('Nice,fr');
    $today = $weather->getToday('Nice,fr');
} catch (CurlException $e) {
     echo $e->getMessage();
} catch (UnauthorizedHTTPException $e) {
    $error = $e->getMessage() . $e->getCode();
} catch (Error $e) {
    $error = $e->getMessage();
}

?>
<h1>Coucou</h1>
<?php if ($error): ?>

    <?= $error ?>

<?php else: ?>

    <h1>La météo à aujourd'hui</h1>
    <?= $today['temp']?>

    <h1>La météo sur les 5 prochains jours, à Nice:</h1>

    <?php foreach($data as $jour): ?>
        <li>Jour: <?= $jour['date']->format('d/m/Y H:i') ?>température<?= $jour['temp']?>, <?=$jour['description']?> </li>
    <?php endforeach ?>

<?php endif ?>





