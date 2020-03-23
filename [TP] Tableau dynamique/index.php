<?php 

use App\NumberHelper;
use App\TableHelper;
use App\UrlHelper;

require 'vendor/autoload.php';

define('PER_PAGE', 20);

$pdo = new PDO('sqlite:products.db', null, null, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

$query = "SELECT * FROM products ";
$queryCount = "SELECT COUNT(id) as count FROM products ";
$params = [];
$conditions = [
    'where' => [],
    'order' => '', 
    'limit' => '',
];
$offset = 0;
$sortable = ['id', 'name', 'city', 'price', 'address'];

// Recherche de ville
if(!empty($_GET['q'])) {
    $conditions['where'][] = "city LIKE :city";
    $params['city'] = '%' . $_GET['q'] . '%';
    $recherche = htmlentities($_GET['q']);
} else {
    $recherche = '';
}

// Prix 
$priceStart = $_GET['priceStart'] ?? null;
$priceEnd = $_GET['priceEnd'] ?? null;

if($priceStart != null) {
    $conditions['where'][] = "price >= :priceStart";
    $params['priceStart'] = $priceStart;
} 
if($priceEnd != null) {
    $conditions['where'][] = "price <= :priceEnd";
    $params['priceEnd'] =  $priceEnd;
}



// Tri croissant ou décroissant
if(!empty($_GET['sort']) && in_array($_GET['sort'], $sortable)) {

    $direction = $_GET['dir'] ?? 'asc';
    if(!in_array($direction, ['asc', 'desc'])) {
        $direction = 'asc';
    } 
    $conditions['order'] = " ORDER BY " . $_GET['sort'] . ' ' . $direction;
}


// Pagination
$numPage = $_GET['p'] ?? 1; 
$offset = (($numPage - 1) * PER_PAGE);

$conditions['limit'] = " LIMIT " . PER_PAGE . " OFFSET " . $offset;

// Construction de la requête
// WHERE
if(count($conditions['where']) > 0) {
    $query .= 'WHERE ';
    $queryCount .= 'WHERE '; 
    foreach($conditions['where'] as $k => $valeur) {
        if($k == 0) {
            $query .= "$valeur ";
            $queryCount .= "$valeur ";
        } else {
            $query .= "AND $valeur ";
            $queryCount .= "AND $valeur ";
        
        }
    }
}

//ORDER BY
$query .= "{$conditions['order']} ";
$queryCount .= "{$conditions['order']} ";
// LIMIT
$query .= "{$conditions['limit']} ";
$queryCount .= "{$conditions['limit']} ";


$statement = $pdo->prepare($query);
$statement->execute($params);
$products = $statement->fetchAll();

$statement = $pdo->prepare($queryCount);
$statement->execute($params);
$count = (int)$statement->fetch()['count'];

$nbPages = ceil($count / PER_PAGE);

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
    <h2>Nb résultats: <?=$count?></h2>

    <div class="form-group">
        <input type="text" name="q" class="form-control" placeholder="Rechercher par ville" value="<?= $recherche ?>">
    </div>
    <div class="form-group">
        <label for="">Prix début</label>
        <input class="form-control" style="width:100px" type="text" name="priceStart" value="<?= htmlentities($_GET['priceStart'] ?? "") ?>">
        <label for="">Prix fin</label>
        <input class="form-control" style="width:100px" type="text" name="priceEnd" value="<?= htmlentities($_GET['priceEnd'] ?? "") ?>">
    </div>
    
    <button class="btn btn-primary">Rechercher</button>
</form>

<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col"><?= TableHelper::sort('id', 'ID', $_GET)?></th>
            <th scope="col"><?= TableHelper::sort('name', 'Nom', $_GET)?></th>
            <th scope="col"><?= TableHelper::sort('price', 'Prix', $_GET)?></th>
            <th scope="col"><?= TableHelper::sort('address', 'Adress', $_GET)?></th>
            <th scope="col"><?= TableHelper::sort('city', 'Ville', $_GET)?></th>
        </tr>
    </thead>
    <tbody>

        <?php foreach($products as $ligneTab): ?>
        <tr>
            <th scope="col">#<?= $ligneTab['id'] ?></th>
            <td><?= $ligneTab['name'] ?></td>
            <td><?= NumberHelper::price($ligneTab['price'])?></td>
            <td><?= $ligneTab['address'] ?></td>
            <td><?= $ligneTab['city'] ?></td>
        </tr>
        <?php endforeach ?>

    </tbody>

</table>

<?php if ($nbPages > 1 && $numPage > 1):?>
<a href="?<?= UrlHelper::withParam($_GET, "p",$numPage - 1) ?>" class="btn btn-primary">Page précédente</a>
<?php endif?>

<?php if ($nbPages > 1 && $numPage < $nbPages):?>
<a href="?<?= UrlHelper::withParam($_GET,"p",$numPage + 1) ?>" class="btn btn-primary">Page suivante</a>
<?php endif?>

<!-- <div>
    <?php for($i = 1; $i <= $nbPages; $i++): ?>
        <a href="?q=<?= $_GET['q'] ?? ""?>&p=<?= $i ?>"><?= $i ?></a>
    <?php endfor ?>
</div> -->
    
</body>
</html>







