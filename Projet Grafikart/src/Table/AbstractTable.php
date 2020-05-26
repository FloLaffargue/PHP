<?php

namespace App\Table;

use PDO;
use App\Table\Exception\NotFoundException;

abstract class AbstractTable {
    
    protected PDO $pdo;
    protected $table = null;
    protected $class = null;
    
    public function __construct(PDO $pdo) 
    {
        if($this->table === null) {
            throw new \Exception("La class " . get_class($this) . " n'a pas de propriété \$table");
        }        
        
        if($this->class === null) {
            throw new \Exception("La class " . get_class($this) . " n'a pas de propriété \$table");
        }
        $this->pdo = $pdo;
    }

    public function find(int $id)
    {
        $query = $this->pdo->prepare('SELECT * FROM ' . $this->table . ' WHERE id = :id');
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        
        $result = $query->fetch();

        if(!$result) {
            throw new NotFoundException($this->table,$id);
        }

        /** @var Post|false */
        return $result;

    }

    /**
     * Vérifie si une valeur existe dans la table
     * @param string $field Champ à rechercher
     * @param string $value Valeur associée au champ
     */
    public function exists(string $field, $value, ?int $except = null): bool {

        $sql = "SELECT COUNT(id) FROM {$this->table} WHERE $field = :value";
        $params = ['value' => $value];
        if($except != null) {
            $sql .= " AND id != :except";
            $params['except'] = $except;
        }

        $query = $this->pdo->prepare($sql);
        $query->execute($params);
        $query->setFetchMode(PDO::FETCH_NUM);

        return $query->fetch()[0] > 0;
        
    }

    public function all(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->pdo->query($sql, PDO::FETCH_CLASS, $this->class)->fetchAll();
    }

    public function delete(int $id): void 
    {

        $query = $this->pdo->prepare('DELETE FROM ' . $this->table . '  WHERE id = :id');
        $ok = $query->execute(['id' => $id]);

        if(!$ok) {
            throw new \Exception("Impossible de supprimer l'enregistrement $id dans la table {$this->table}");
        }
    }

    public function create(array $data): int 
    {
        $sqlFields = [];

        foreach($data as $k => $v) {
            $sqlFields[] = "$k = :$k";
        }

        $sql = "INSERT INTO {$this->table} SET " . implode(', ', $sqlFields);
        $query = $this->pdo->prepare($sql);
        $ok = $query->execute($data);

        if(!$ok) {
            throw new \Exception("Impossible d'insérer l'enregistrement dans la table {$this->table}");
        }

        return (int)$this->pdo->lastInsertId();

    }

    public function update(array $data, int $id): void
    {
        $sqlFields = [];

        foreach($data as $k => $v) {
            $sqlFields[] = "$k = :$k";
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $sqlFields) . ' WHERE id = :id';
        $query = $this->pdo->prepare($sql);
        $ok = $query->execute(array_merge($data, ['id' => $id]));

        if(!$ok) {
            throw new \Exception("Impossible d'insérer l'enregistrement dans la table {$this->table}");
        }
    }

    public function queryAndFetchAll(string $sql): array
    { 
        return $this->pdo->query($sql, PDO::FETCH_CLASS, $this->class)->fetchAll();       
    }


}