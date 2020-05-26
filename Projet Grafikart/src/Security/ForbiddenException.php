<?php

namespace App\Security;

class ForbiddenException extends \Exception {

    private $uri;

    public function __construct(string $uri) {
        $this->uri = $uri;
    }

    public function getUri() {
        return $this->uri;
    }
}