<?php

namespace App;

class TableHelper {

    public static function sort(string $sortKey, string $label,array $tab): string 
    {
        $sort = $tab['sort'] ?? null;
        $direction = $tab['dir'] ?? null;
        $icon = '';

        if($sort === $sortKey) {
            $icon = $direction === 'asc' ? '^' : 'v';
        }

        $url = UrlHelper::withParams($tab, ['sort' => $sortKey, 'dir' => $sort == $sortKey && $direction == 'asc' ? 'desc' : 'asc']);
        return "<a href=?$url>$label $icon</a>";
    }

}