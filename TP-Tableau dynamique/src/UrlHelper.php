<?php

namespace App;

class UrlHelper {
    
    public static function withParam(array $data, string $param,  $value): string {
        if(is_array($value)) {
            $value = implode(',', $value);
        }
        return http_build_query(array_merge($data, [$param => $value]));
    }

    public static function withParams(array $data, array $params): string {

        $params = array_map(function($element) {
            return is_array($element) ? implode(',', $element) : $element;
        }, $params);

        return http_build_query(array_merge($data, $params));
    }
}