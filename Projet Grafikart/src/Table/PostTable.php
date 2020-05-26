<?php

namespace App\Table;

use \PDO;
use App\Model\Post;
use App\Model\Category;
use App\PaginatedQuery;

final class PostTable extends AbstractTable {

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
    
    public function updatePost(Post $post):void {

        // J'utilise la méthode Update que j'hérite du parent
        $this->update([
            'name' => $post->getName(),
            'slug' => $post->getSlug(),
            'content' => $post->getContent(),
            'created_at' => $post->getCreatedAt()->format('Y-m-d H:i:s'),
            'image' => $post->getImage()
        ], $post->getID());

    }

    public function createPost (Post $post): void {

        $id = $this->create([
            'name' => $post->getName(),
            'content' => $post->getContent(),
            'slug' => $post->getSlug(),
            'created_at' => $post->getCreatedAt()->format('Y-m-d H:i:s'),
            'image' => $post->getImage()
        ]);

        $post->setID($id);

    }

    public function attachCategories(int $id, array $categories) 
    {
        // Je nettoie la base des anciennes associations article => catégorie
        $this->pdo->exec("DELETE FROM post_category WHERE post_id = $id"); 

        // J'insére les nouvelles catégories
        foreach($categories as $category) {

            $query = $this->pdo->prepare("INSERT INTO post_category (post_id, category_id) VALUES (:post_id, :category_id)");
            $query->execute([
                'post_id' => $id,
                'category_id' => $category,
            ]);
        }

    }


}