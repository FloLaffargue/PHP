<?php

namespace App\Table;

use App\Model\User;
use App\Table\Exception\NotFoundException;

final class UserTable extends AbstractTable {

    protected $table = 'user';
    protected $class = User::class;
        
    public function findByUsername(string $username) {

        $query = $this->pdo->prepare('SELECT * FROM ' . $this->table . ' WHERE username = :username');
        $query->execute(['username' => $username]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->class);
        
        $result = $query->fetch();

        if(!$result) {
            throw new NotFoundException($this->table,$username);
        }

        /** @var Post|false */
        return $result;
    }



}