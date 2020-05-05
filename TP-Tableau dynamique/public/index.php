<?php 

use App\NumberHelper;
use App\QueryBuilder;
use App\TableHelper;
use App\UrlHelper;
use App\Table;

require '../vendor/autoload.php';

define('PER_PAGE', 20);

$pdo = new PDO('sqlite:../products.db', null, null, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

$query = (new QueryBuilder($pdo))->from('products');

// Recherche de ville
if(!empty($_GET['q'])) {
    $query
          ->where("city LIKE :city")
          ->setParam('city', '%' . $_GET['q'] . '%')
          ;
} 

$table = (new Table($query, $_GET))
    ->sortable('id', 'city')
    ->format('price', function($value) {
        return NumberHelper::price($value);
    })
    ->columns([
        'id'   => 'ID',
        'name' => 'Nom',
        'city' => 'Ville',
        'address' => "Adresse",
        'price' => 'price'
    ]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body class="p-4">

<form action="/" class="mb-4">

    <h1>Les biens immobiliers</h1>
    <h2>Nb résultats: <?= $query->count()?></h2>

    <div class="form-group">
        <input type="text" name="q" class="form-control" placeholder="Rechercher par ville" value="<?= $_GET['q'] ?? "" ?>">
    </div>
    <div class="form-group">
        <label for="">Prix début</label>
        <input class="form-control" style="width:100px" type="text" name="priceStart" value="<?= htmlentities($_GET['priceStart'] ?? "") ?>">
        <label for="">Prix fin</label>
        <input class="form-control" style="width:100px" type="text" name="priceEnd" value="<?= htmlentities($_GET['priceEnd'] ?? "") ?>">
    </div>
    
    <button class="btn btn-primary">Rechercher</button>
</form>

<?= $table->render() ?>


<!-- <div>
    <?php for($i = 1; $i <= $nbPages; $i++): ?>
        <a href="?q=<?= $_GET['q'] ?? ""?>&p=<?= $i ?>"><?= $i ?></a>
    <?php endfor ?>
</div> -->
    
</body>
</html>







