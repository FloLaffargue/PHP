<?php

namespace App\Model;

class User {

    private $id;
    private $username;
    private $password;
    
    public function getID(): ?int {
        return $this->id;
    }

    public function getUsername(): ?string {
        return $this->username;
    }

    public function setUsername($username): self {
        $this->username = $username;

        return $this;
    }  

    public function getPassword(): ?string {
        return $this->password;
    }

    public function setPassword($password): self {
        $this->password = $password;

        return $this;
    }
    
}