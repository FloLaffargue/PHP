<?php

namespace App\Table\Exception;

class NotFoundException extends \Exception {

    public function __construct(string $table, int $id) {
        $this->message = "Aucun enregistrement dans la table $table ne correspond à l'id #$id";
    }
}