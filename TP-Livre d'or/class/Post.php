<?php
namespace App;

use \DateTime;
use \Parsedown;

class Post {

    public $id;

    public $name;
    
    public $content;

    public $created_at;

    public function __construct()
    {
        if (is_int($this->created_at) || is_string($this->created_at)) {
            $this->created_at = new DateTime('@' . $this->created_at);
        } else {
            $this->created_at = new DateTime();
        }
    }

    public function getExcerpt(): string 
    {
        return substr($this->content,0,2);
    }

    public function getBody(): string 
    {
        $parseDown = new Parsedown();
        return $parseDown->text($this->content);
    }
}