<?php

namespace App\Model;

use App\Helpers\Text;
use \DateTime;

class Post {

    private $id;
    private $slug;
    private $name;
    private $content;
    private $created_at;
    private $categories = [];

    public function getName(): ?string {
        return $this->name;
    }

    public function getSlug(): ?string {
        return $this->slug;
    }

    public function getID(): ?int {
        return $this->id;
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

    public function getFormattedContent(): ?string {
        return nl2br(esc($this->content));
    }

    /**
     * @var Categoriy[]
     */
    public function getCategories(): array {
        return $this->categories;
    }

    public function setCategory(Category $category): void {
        $this->categories[] = $category;
    }

}