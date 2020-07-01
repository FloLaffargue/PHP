<?php

namespace App\Table;

use PDO;
use Exception;
use App\Router;
use App\Model\Post;

class PostTable {

    const PER_PAGE = 10;
    
    private $pdo;
    private $totalPages;
    private $router;

    public function __construct(PDO $pdo, Router $router)
    {
        $this->pdo = $pdo;    
        $this->router = $router;
    }

    public function getPaginatedItems() 
    {
        $page = $this->getPage();
        $nbPages = $this->getTotalPages();

        if($page > $nbPages) {
            throw new Exception("Il n\'existe pas de page $page");
        }

        $limit = self::PER_PAGE;
        $offset = ($page - 1) * self::PER_PAGE;

        $sql = "SELECT * FROM post LIMIT $limit OFFSET $offset";

        $results = $this->pdo->query($sql, PDO::FETCH_CLASS, Post::class)->fetchAll();

        return $results;
    }

    public function find(string $slug): ?Post 
    {
        $query = $this->pdo->prepare("SELECT * FROM post WHERE slug=:slug");
        $query->execute(['slug' => $slug]);
        $query->setFetchMode(PDO::FETCH_CLASS, Post::class);
        $result = $query->fetch();

        if($result === 0) throw new Exception('Cet article n\'existe pas!');

        return $result;
    }

    private function getPage(): int 
    {
        $page = $_GET['page'] ?? 1;

        if($page === 0 || !filter_var($page, FILTER_VALIDATE_INT)) {
            throw new Exception('La page n\'est pas un entier positif !');
        }
        return $page;
    }

    private function getTotalPages(): int
    {
        if($this->totalPages === null){
            $count =  $this->pdo->query('SELECT count(id) FROM post', PDO::FETCH_NUM)->fetch()[0];
            $this->totalPages = ceil($count / self::PER_PAGE);
        }

        return $this->totalPages;
    }

    public function getPreviousLink(): ?string 
    {
        $page = $this->getPage();

        if($page > 1) 
        {
            $page--;
            $url = $this->router->url('posts') . "?page=$page"; 
            return <<<HTML
                <div>
                    <a href="$url">Page précédente</a>
                </div>
            HTML;
        }  
        return null;
    }

    public function getNextLink(): ?string
    {
        $page = $this->getPage();
        $nbPages = $this->getTotalPages();

        if($page === $nbPages) return null;
        $page++;
        $url = $this->router->url('posts') . "?page=$page"; 
        return <<<HTML
            <div>
                <a href="$url">Page suivante</a>
            </div>
        HTML;
    }

    public function delete(string $slug) 
    {
        $ok = $this->pdo->exec("DELETE FROM post WHERE slug = '$slug'");
        if(!$ok) {
            throw new \Exception('Probléme dans la suppression');
        } else {
            
        }
    }
    
}