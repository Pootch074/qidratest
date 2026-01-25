<?php

namespace App\Libraries;

use App\Models\Window;

class Windows
{
    protected static $windows = null;

    public static function all()
    {
        if (self::$windows === null) {
            self::$windows = Window::pluck('window_number', 'id')->toArray();
        }

        return self::$windows;
    }

    public static function __callStatic($name, $arguments)
    {
        $windows = self::all();
        $formatted = ucwords(strtolower(str_replace('_', ' ', $name)));

        foreach ($windows as $windowName => $id) {
            if (strcasecmp($windowName, $formatted) === 0) {
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
