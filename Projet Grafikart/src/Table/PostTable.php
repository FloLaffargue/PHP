<?php

namespace App\Table;

use \PDO;
use App\Model\Post;
use App\Model\Category;
use App\PaginatedQuery;

final class PostTable extends Table {

    protected $table = 'post';
    protected $class = Post::class;
        
    /**
     * Sélectionne tous les articles et leurs catégories associées
     */
    public function findPaginated () {

        $queryCount = 'SELECT COUNT(id) FROM post';
        $query = "SELECT * FROM post ORDER BY created_at DESC";

        $paginatedQuery = new PaginatedQuery($query, $queryCount, $this->pdo);

        /** @var Post[] */
        $posts = $paginatedQuery->getItems(Post::class);

        (new CategoryTable($this->pdo))->hydratePosts($posts);

        return [$posts, $paginatedQuery];

    }

    /**
     * Sélectionne tous les articles liés à une catégorie
     */
    public function findPaginatedForCategory(int $categoryID)
    {
        $query = "SELECT p.* FROM post p
        INNER JOIN post_category pc ON pc.post_id = p.id
        WHERE pc.category_id = {$categoryID}
        ORDER BY created_at DESC"
        ;
        
        $queryCount = 'SELECT COUNT(post_id) FROM post_category pc WHERE pc.category_id = ' . $categoryID;
        
        $paginatedQuery = new PaginatedQuery($query,$queryCount);
        
        /** @var Post[] */
        $posts = $paginatedQuery->getItems(Post::class);

        (new CategoryTable($this->pdo))->hydratePosts($posts);

        return [$posts, $paginatedQuery];

    }


}