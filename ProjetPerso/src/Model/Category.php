<?php

namespace App\Model;

class Category {

    private $name;
    public $post_id;

    public function getName(): string
    {
        return $this->name;
    }    
    
    public function getPostID(): string
    {
        return $this->post_id;
    }
}