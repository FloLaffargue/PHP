<?php

namespace App\Table\Exception;

class NotFoundException extends \Exception {

    public function __construct(string $table, $data) {
        $this->message = "Aucun enregistrement dans la table $table ne correspond à #$data";
    }
}