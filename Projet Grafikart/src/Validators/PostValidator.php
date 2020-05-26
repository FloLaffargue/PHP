<?php

namespace App\Validators;

use App\Validator;
use App\Table\PostTable;


class PostValidator extends AbstractValidator{

    public function __construct(array $data, PostTable $postTable, ?int $postID = null, array $categories) 
    {
        parent::__construct($data);

        $this->validator->rule('required',['name', 'slug']);
        $this->validator->rule('lengthBetween',['name', 'slug'], 3, 100);
        $this->validator->rule('slug','slug');
        $this->validator->rule('subset', 'categories_ids', $categories);
        $this->validator->rule('image', 'image');

        $this->validator->rule(function($field, $value) use($postTable, $postID){
            return !$postTable->exists($field, $value, $postID);
        }, ['slug', 'name'], 'Cette valeur est déjà utilisé');

    }

}