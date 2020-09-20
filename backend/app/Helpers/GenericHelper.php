<?php

namespace App\Helpers;

class GenericHelper
{
    /**
     * Convert a string to its slug form
     * @param string $data
     * @return string
     */
    public static function slugify(string $data)
    {
        return strtolower(str_replace(" ", "-", $data));
    }
}
