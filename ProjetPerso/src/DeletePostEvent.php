<?php

namespace App;

use App\Model\Post;

class DeletePostEvent {

    private $name = 'post.delete';
    private $target;

    public function __construct(Post $target)
    {
        $this->target = $target;
    }
    
    public function getName(): string 
    {
        return $this->name;
    }    
    
    public function getTarget()
    {
        return $this->target;
    }

}