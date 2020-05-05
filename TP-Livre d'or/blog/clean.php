<?php
require 'config.php';

if (isset($_GET['id'])) {
    $query = $pdo->prepare('DELETE FROM posts WHERE id = :id');
    $query->execute([
        'id' => $_GET['id']
    ]);
}

