<?php

namespace App;

use finfo;
use Valitron\Validator as ValitronValidator;

class Validator extends ValitronValidator {

    public function __construct($data = array(), $fields = array(), $lang = null, $langDir = null) 
    {
        parent::__construct($data, $fields, $lang, $langDir);

        self::addRule('image', function($field, $value, array $params, array $fields) {
            // On accepte de ne pas envoyer d'images
            if($value['size'] === 0) return true;

            $finfo = new finfo();
            $info = $finfo->file($value['tmp_name'], FILEINFO_MIME_TYPE);
            $mimes = ['image/png', 'image/jpeg'];

            return in_array($info, $mimes);


        }, "Le fichier n'est pas une image valide");
    }

    protected function checkAndSetLabel($field, $message, $params)
    {
        return str_replace('{field}', '', $message);
    }


    
}
