<?php

namespace App\Model;


class Post {

    private $id;
    private $name;
    private $slug;
    private $content;
    private $created_at;
    private $categories = [];
    
    public function getID(): int 
    {
        return $this->id;

    }
    public function getName(): string 
    {
        return $this->name;
    }

    public function setName(string $name): self 
    {
        $this->name = $name;
        return $this;
    }

    public function getSlug(): string 
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self 
    {
        $this->slug = $slug;
        return $this;
    }

    public function getContent(): string 
    {
        return $this->content;
    }

    public function setContent(string $content) 
    {
        $this->content = $content;
    }

    public function getCreatedAt() 
    {
        return new \DateTime($this->created_at);
    }

    public function getCategories(): array 
    {
        return $this->categories;
    }
    
    public function setCategory(Category $category): void
    {
        $this->categories[] = $category;
    }

    public function getExcerpt(): string 
    {
        return substr($this->content,0,5) . '...';
    }
}