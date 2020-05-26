<?php

use App\Auth;
use App\Connection;
use App\Table\PostTable;
use App\Attachment\PostAttachment;

Auth::check();

$pdo = Connection::getPDO();

$id = $params['id'];
$postTable = new PostTable($pdo);
PostAttachment::detache($postTable->find($id));
$postTable->delete($id);

header('Location: ' . $router->url('admin_posts') . '?delete=1');

?>    