<?php

namespace App\Libraries;

use App\Models\Step;

class Steps
{
    protected static $steps = null;

    protected static function all()
    {
        if (self::$steps === null) {
            self::$steps = Step::pluck('id', 'step_name')->toArray();
        }

        return self::$steps;
    }

    public static function __callStatic($name, $arguments)
    {
        $steps = self::all();
        $normalizedName = preg_replace('/[-_\s]+/', '', strtolower($name));
        foreach ($steps as $stepName => $id) {
            $normalizedStep = preg_replace('/[-_\s]+/', '', strtolower($stepName));
            if ($normalizedStep === $normalizedName) {
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
