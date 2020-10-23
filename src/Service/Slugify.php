<?php

namespace App\Service;

class Slugify {
    public function generate(string $input): string 
    {
        $input = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $input);
        $input = preg_replace('/[[:punct:]]/', '', $input);
        return strtolower(trim(str_replace(' ', '-', $input),"-"));
    }
}