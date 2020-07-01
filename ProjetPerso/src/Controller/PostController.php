<?php

namespace App\Controller;

use App\Connection;
use App\Model\Post;
use App\EventManager;
use App\DeletePostEvent;
use App\Table\PostTable;

class PostController {

    public function index($match, $router) {
        $GLOBALS['title'] = "Page de liste des articles";
        require VIEW_PATH . 'listposts.php';
    }

    public function show($match, $router) {
        $GLOBALS['title'] = "Page d'un article";
        require VIEW_PATH . 'post.php';
    }

    public function delete($match, $router) {

        $manager = new EventManager();

        $manager->attach('post.delete', function($event) use ($router){
            $pdo = Connection::getPDO();
            $postSlug = $event->getTarget()->getSlug();
            (new PostTable($pdo, $router))->delete($postSlug);

        }, 1);
        
        $manager->attach('post.delete', function($event) {
            echo "Bonjour à tous, on va supprimer l'article {$event->getTarget()->getSlug()} !";
        }, 5);

        $slug = $match['params']['slug'];
        $post = (new Post())->setSlug($slug);
        $manager->emit(new DeletePostEvent($post));

        $message = "Le post $slug a bien été supprimé";
        require VIEW_PATH . 'delete.php';

    }
}