<?php 

namespace App;

use PDO;

class QueryBuilder {

    private $fields = ['*'];

    private $from;

    private $order = [];

    private $limit;

    private $offset;

    private $where;

    private $params = [];


    public function toSQL() {
        $sql = "SELECT " . implode(', ', $this->fields) . " FROM {$this->from}";
        
        if($this->where) {
            $sql .= " WHERE {$this->where}";
        }

        if(!empty($this->order)) {
            $sql .= " ORDER BY " . implode(', ', $this->order);
        }  
        
        if($this->limit > 0) {
            $sql .= " LIMIT {$this->limit}";
        }

        if($this->offset !== null) {
            $sql .= " OFFSET {$this->offset}";
        }

        return $sql;
    }

    public function from(string $table, string $alias = null): self {
        $this->from = $alias == null ? $table : "$table $alias";
        return $this;
    }

    public function orderBy($key, $direction): self {
        $direction = strtoupper($direction);

        if(!in_array($direction, ['DESC', 'ASC'])){
            $this->order[] = $key;
        } else {
            $this->order[] = "$key $direction";
        }
        return $this;
    }

    public function limit($limit): self {
        $this->limit = $limit;
        return $this;
    }

    public function offset($offset): self {
        $this->offset = $offset;
        return $this;
    }

    public function page(int $page): self {
        $this->offset(($page * $this->limit) - $this->limit); 
        return $this;
    } 

    public function where(string $where): self {
        $this->where = $where;
        return $this;
    }

    public function setParam($key, $val): self {
        $this->params[$key] = $val;
        return $this;
    }

    public function select(...$fields): self {
        
        // Spécifique dans le cas du "testSelectAsArray", qui envoit un tableau
        // Du coup dans ...$fields j'ai [['id','name','product']]
        if(is_array($fields[0])) {
            $fields = $fields[0];
        }
        
        if($this->fields === ['*']) {
            $this->fields = $fields;
        } else {
            $this->fields = array_merge($this->fields, $fields);
        }
        return $this;
    }

    public function fetch(PDO $pdo, string $field): ?string {
        $query = $pdo->prepare($this->toSQL());
        $query->execute($this->params);
        $data = $query->fetch();
        
        return $data != false ? $data[$field] : null;

    }

    public function count(PDO $pdo): int {
        
        $query = clone $this;
        return (int)$query->select('COUNT(id) count')->fetch($pdo, 'count');
    }



    
}