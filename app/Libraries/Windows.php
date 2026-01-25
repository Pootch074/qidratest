<?php

namespace App\Libraries;

use App\Models\Window;

class Windows
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    protected static $windows = null;

    public static function all()
    {
        if (self::$windows === null) {
            self::$windows = Window::pluck('window_number', 'id')->toArray();
        }

        return self::$windows;
    }
}
