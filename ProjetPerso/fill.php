<?php

use App\Connection;

require './src/Autoloader.php';
Autoloader::autoload();

$pdo = Connection::getPDO();

$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
$pdo->exec('TRUNCATE TABLE post');
$pdo->exec('TRUNCATE TABLE category');
$pdo->exec('TRUNCATE TABLE post_category');
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

$posts = [];
$categories = [];

// Post
for($i = 1; $i <= 30; $i++) {
    $name = "Article_$i";
    $slug = "article-$i";
    $content = "Contenu de l\'article $i";
    $created_at = (new DateTime())->format('Y-m-d H:i:s');

    $sql = "INSERT INTO post (name, slug, content, created_at) VALUES ('$name', '$slug' ,'$content', '2018-11-05 15:00:25')";

    $pdo->exec($sql);   
    $posts[] = $pdo->lastInsertId();
}

// Categories
for($i = 1; $i <= 5; $i++) {

    $name = "Categorie $i";
    $created_at = (new DateTime())->format('Y-m-d H:i:s');

    $sql = "INSERT INTO category (name, created_at) VALUES ('$name','$created_at')";

    $pdo->exec($sql);
    $categories[] = $pdo->lastInsertId();
}

// Lien categorie post
foreach($posts as $post) {

    $randomCategories = getRandomElements($categories, mt_rand(0,5));

    foreach($randomCategories as $categorie) {
        $pdo->exec("INSERT into post_category (post_id, category_id) VALUES ('$post','$categorie')");
    }
}


function getRandomElements(array $tab, int $nb): array {
    
    $randomElements = [];
    
    for($i = 0; $i < $nb; $i++) {
        $indice = mt_rand(0, count($tab) - 1);
        $element = $tab[$indice];
        if(in_array($element, $randomElements)) {
            $i--;
        } else {
            $randomElements[] = $element;
        }
    }
    
    return $randomElements;
}




