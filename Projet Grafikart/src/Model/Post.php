<?php

namespace App\Model;

use App\Helpers\Text;
use \DateTime;

class Post {

    // private $id;
    private $slug;
    private $name;
    private $content;
    private $created_at;
    private $categories = [];
    private $image;
    private $oldImage;
    private $pendingUpload = false;

    public function getID(): ?int {
        return $this->id;
    }

    public function setID(int $id): self {
        $this->id = $id;

        return $this;
    }

    public function getSlug(): ?string {
        return $this->slug;
    }

    public function setSlug(string $slug): self {
        $this->slug = $slug;

        return $this;
    }
    
    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;
        return $this;
    }

    public function getContent(): ?string {
        return $this->content;
    }
    
    public function setContent(string $content): self {
        $this->content = $content;

        return $this;
    }

    public function getExcerpt(): ?string {

        if($this->content == null) {
            return null;
        }

        return nl2br(htmlentities(Text::excerpt($this->content,60)));
    }

    public function getCreatedAt(): DateTime {
        return new DateTime($this->created_at);
    }

    public function setCreatedAt(string $date): self {
        $this->created_at = $date;

        return $this;
    }


    public function getFormattedContent(): ?string {
        return nl2br(esc($this->content));
    }

    /**
     * @var Category[]
     */
    public function getCategories(): array {
        return $this->categories;
    }

    public function setCategories(array $categories): self {
        $this->categories = $categories;
        
        return $this;   
    }

    public function getCategoriesIds(): array 
    {
        $ids = [];
        foreach($this->categories as $category) {
            $ids[] = $category->getID();
        }
        return $ids;
        
    }
    
    public function setCategory(Category $category): void {
        $this->categories[] = $category;
    }

    public function getImage(): ?string 
    {
        return $this->image;
    }

    public function getImageURL(string $size): ?string 
    {
        if(empty($this->image)) return null;

        return '/uploads/post/' . $this->image . '_' . $size . '.jpg';
    }

    public function setImage($image): self 
    {
        // Cas de l'hydradation (la clé image est un tableau associatif)
        if(is_array($image) && !empty($image['tmp_name'])) {
            if(!empty($this->image)) {
                $this->oldImage = $this->image;
            }
            $this->pendingUpload = true;
            $this->image = $image['tmp_name'];
        }

        // Cas de l'upload
        if(is_string($image)) {
            $this->image = $image;
        }

        return $this;
    }

    public function getOldImage(): ?string 
    {
        return $this->oldImage;
    }

    public function shouldUpload(): bool 
    {
        return $this->pendingUpload;
    }
}