<?php

namespace App;

class PaginatedQuery {

    private $query;
    private $queryCount;
    private $pdo;
    private $perPage;
    private $count;
    private $items;

    public function __construct(
        string $query,
        string $queryCount,
        ?\PDO $pdo = null,
        int $perPage = 12
    ) 
    {
        $this->query = $query;
        $this->queryCount = $queryCount;
        $this->pdo = $pdo ?: Connection::getPDO();
        $this->perPage = $perPage;
    }

    private function getCurrentPage(): int 
    {
        return URL::getPositiveInt('page', 1);
    }

    private function getPages(): int 
    {

        if($this->count === null) {         
            $count = (int)$this->pdo
                    ->query($this->queryCount)
                    ->fetch(\PDO::FETCH_NUM)[0];
            $this->count = $count;
        } 
        return ceil($this->count / $this->perPage);

    }

    public function getItems(string $classMapping): array 
    {
        if($this->items === null) {

            $currentPage = $this->getCurrentPage();
            $pages = $this->getPages();
    
            if($currentPage > $pages) {
                throw new \Exception('Cette page n\'existe pas');
            }
    
            $offset = ($currentPage - 1) * $this->perPage;
            $this->query .= " LIMIT {$this->perPage} OFFSET $offset";
    
            $this->items = $this->pdo
                      ->query($this->query)
                      ->fetchAll(\PDO::FETCH_CLASS, $classMapping);
        }

        return $this->items;
    }

    public function previousLink(string $link): ?string 
    {
        $currentPage = $this->getCurrentPage();
        if($currentPage <= 1) return null;

        // Si on est page 2, pas besoin de mettre de param dans l'URL pour aller en page 1
        if ($currentPage > 2) $link .= '?page=' . ($currentPage - 1);

        return <<<HTML

            <a href="{$link}" class="btn btn-primary">&laquo; Page précédente</a>
HTML;
    }    
    
    public function nextLink(string $link): ?string 
    {
        $currentPage = $this->getCurrentPage();
        $pages = $this->getPages();

        if($currentPage >= $pages) return null;
        
        $link .= '?page=' . ($currentPage + 1);

        return <<<HTML

            <a href="{$link}" class="btn btn-primary ml-auto"> Page suivante &raquo;</a>
HTML;

    }

}