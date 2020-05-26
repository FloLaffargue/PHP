<?php

namespace App\Table;

use PDO;
use App\Model\Category;
use App\PaginatedQuery;
use App\Table\Exception\NotFoundException;

final class CategoryTable extends AbstractTable {

    protected $table = 'category';
    protected $class = Category::class;

    /**
     * @param App\Model\Post[] $posts
     * 
     */
    public function hydratePosts (array $posts): void {
        
        $postsByID = [];
    
        // Je crée un tab ordonné par ID
        foreach($posts as $post) {
            $post->setCategories([]);
            $postsByID[$post->getID()] = $post;
        }

        // J'extraie toutes les clés(ID) du tableau
        $ids = array_keys($postsByID);

        $categories = $this->pdo
            ->query('SELECT c.*, pc.post_id 
                    FROM post_category pc 
                    INNER JOIN category c on pc.category_id = c.id 
                    WHERE pc.post_id IN (' . implode(',',$ids) . ')')
            ->fetchAll(PDO::FETCH_CLASS, Category::class);

        foreach($categories as $category) {
            $idPost = $category->getPostID();
            $arrayCat = $postsByID[$idPost]->setCategory($category);
        }

    }

    public function all(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY id DESC";
        return $this->queryAndFetchAll($sql);
    }

    public function list (): array 
    {
        $categories = $this->queryAndFetchAll("SELECT * FROM {$this->table} ORDER BY name");
        $results = [];

        foreach($categories as $category) {
            $results[$category->getID()] = $category->getName();  
        }

        return $results;

    }

}