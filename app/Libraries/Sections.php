<?php

namespace App\Libraries;

use App\Models\Section;

class Sections
{
    // Example of caching all sections at runtime
    protected static $sections = null;
    public static function all()
    {
        if (self::$sections === null) {
            self::$sections = Section::pluck('section_name', 'id')->toArray();
        }

        return self::$sections;
    }

    public static function __callStatic($name, $arguments)
    {
        $sections = self::all();
        $formatted = ucwords(strtolower(str_replace('_', ' ', $name)));

        foreach ($sections as $sectionName => $id) {
            if (strcasecmp($sectionName, $formatted) === 0) {
                return $id;
            }
        }
        return null;
    }

    public static function id(string $key): ?int
    {
        return self::__callStatic($key, []);
    }
}
