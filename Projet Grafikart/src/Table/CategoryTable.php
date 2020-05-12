<?php

namespace App\Table;

use PDO;
use App\Model\Category;
use App\Table\Exception\NotFoundException;

final class CategoryTable extends Table {

    protected $table = 'category';
    protected $class = Category::class;

    /**
     * @param App\Model\Post[] $posts
     * 
     */
    public function hydratePosts (array $posts): void {
        
        $postsByID = [];
    
        foreach($posts as $post) {
            $postsByID[$post->getID()] = $post;
        }
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

}