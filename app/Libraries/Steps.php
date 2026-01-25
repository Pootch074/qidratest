<?php

namespace App\Libraries;

use App\Models\Step;

class Steps
{
    protected static $steps = null;

    public static function all()
    {
        if (self::$steps === null) {
            self::$steps = Step::pluck('step_number', 'id')->toArray();
        }

        return self::$steps;
    }

    public static function __callStatic($name, $arguments)
    {
        $steps = self::all();
        $formatted = ucwords(strtolower(str_replace('_', ' ', $name)));

        foreach ($steps as $stepName => $id) {
            if (strcasecmp($stepName, $formatted) === 0) {
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
