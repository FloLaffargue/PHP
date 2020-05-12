<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Connection;

$pdo = Connection::getPDO();

$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
$pdo->exec('TRUNCATE TABLE post_category');
$pdo->exec('TRUNCATE TABLE post');
$pdo->exec('TRUNCATE TABLE category');
$pdo->exec('TRUNCATE TABLE user');
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

$faker = Faker\Factory::create('fr_FR');

$posts     = [];
$categories = [];

for($i = 0; $i < 50; $i++) {
    $name = $faker->sentence();
    $slug = $faker->slug;
    $content = $faker->paragraphs(rand(3,10), true);

    $pdo->exec("INSERT INTO post SET name='$name', slug='$slug', created_at='{$faker->date} {$faker->time}', content='$content'");

    $posts[] = $pdo->lastInsertId();
}

for($i = 0; $i < 5; $i++) {
    $name = $faker->sentence();
    $slug = $faker->slug;
    $content = $faker->paragraphs(rand(3,10), true);

    $pdo->exec("INSERT INTO category SET name='{$faker->sentence(3)}',slug='{$faker->slug}' ");

    $categories[] = $pdo->lastInsertId();
}

foreach($posts as $post) {

    $randomCategories = $faker->randomElements($categories, rand(0, count($categories)));

    foreach($randomCategories as $category) {
        $pdo->exec("INSERT INTO post_category SET post_id='$post',category_id='$category' ");
    }
}

$hash = password_hash('admin', PASSWORD_BCRYPT);
$pdo->exec("INSERT INTO user SET username='admin', password='$hash'");
