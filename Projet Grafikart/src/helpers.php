<?php

function esc(string $chaine): string {
    return htmlentities($chaine);
}