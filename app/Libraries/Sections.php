<?php

namespace App\Libraries;

use App\Models\Section;

class Sections
{
    // Example of caching all sections at runtime
    protected static $sections = null;

    /**
     * Get all sections (cached)
     */
    protected static function all()
    {
        if (self::$sections === null) {
            // pluck returns ['section_name' => id]
            self::$sections = Section::pluck('id', 'section_name')->toArray();
        }

        return self::$sections;
    }

    /**
     * Magic static getter: allows Sections::CRISIS_INTERVENTION_SECTION style access.
     */
    public static function __callStatic($name, $arguments)
    {
        $sections = self::all();

        // Convert CRISIS_INTERVENTION_SECTION -> "Crisis Intervention Section"
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
