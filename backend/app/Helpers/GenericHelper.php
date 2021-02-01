<?php

namespace App\Helpers;

class GenericHelper
{
    /**
     * Convert a string to its slug form
     * @param string $data Input string to slugify
     * @return string Slug of input
     */
    public static function slugify(string $data): string
    {
        return strtolower(str_replace(" ", "-", $data));
    }
}
