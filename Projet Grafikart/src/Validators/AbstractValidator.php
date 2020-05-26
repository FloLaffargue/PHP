<?php

namespace App\Validators;

use App\Validator;
use App\Table\PostTable;

abstract class AbstractValidator {

    protected $data;
    protected $validator;

    public function __construct(array $data) 
    {
        $this->data = $data;
        Validator::lang('fr');
        $this->validator = new Validator($data);
    }

    public function validate(): bool 
    {
        return $this->validator->validate();
    }

    public function errors(): array 
    {
        return $this->validator->errors();
    }
}