<?php

use App\Auth;
use App\Connection;
use App\Table\CategoryTable;

Auth::check();

$pdo = Connection::getPDO();

$id = $params['id'];
(new CategoryTable($pdo))->delete($id);

header('Location: ' . $router->url('admin_categories') . '?delete=1');

?>    