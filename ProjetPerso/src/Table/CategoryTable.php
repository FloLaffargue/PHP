<?php

namespace App\Table;

use App\Model\Category;

class CategoryTable {

    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function hydratePosts(array $posts)
    {
        $postsKeys = [];
        foreach($posts as $post) {
            $postsKeys[$post->getID()] = $post ;
        }
        $keys = implode(',', array_keys($postsKeys));

        $categories = $this->pdo
            ->query("SELECT * FROM post_category 
                    inner join category on post_category.category_id = category.id
                    WHERE post_id IN ($keys)", \PDO::FETCH_CLASS,Category::class)
            ->fetchAll();
        
        foreach($categories as $category) {
            $idPost = $category->getPostID();
            $postsKeys[$idPost]->setCategory($category);
        }

    }

}