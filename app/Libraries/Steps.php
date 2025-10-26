<?php

namespace App\Libraries;

use App\Models\Step;

class Steps
{
    protected static $steps = null;

    /**
     * Cache all steps at runtime.
     */
    protected static function all()
    {
        if (self::$steps === null) {
            // ['step_name' => id]
            self::$steps = Step::pluck('id', 'step_name')->toArray();
        }

        return self::$steps;
    }

    /**
     * Allow dynamic static calls like Steps::PRE_ASSESSMENT()
     */
    public static function __callStatic($name, $arguments)
    {
        $steps = self::all();

        // Convert "PRE_ASSESSMENT" → "Pre-assessment"
        $formatted = ucwords(strtolower(str_replace('_', '-', str_replace('_', ' ', $name))));
        // Handle both "Pre-assessment" and "Pre Assessment"
        $formattedAlt = ucwords(strtolower(str_replace('_', ' ', $name)));

        foreach ($steps as $stepName => $id) {
            if (strcasecmp($stepName, $formatted) === 0 || strcasecmp($stepName, $formattedAlt) === 0) {
                return $id;
            }
        }

        return null;
    }

    /**
     * Get ID by readable key (alternative usage).
     */
    public static function id(string $key): ?int
    {
        return self::__callStatic($key, []);
    }
}
