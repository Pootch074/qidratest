<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public static function boot()
    {
        // Call the parent boot method to ensure any inherited boot logic is executed
        parent::boot();

        // Define a "creating" model event hook that triggers before a new Window is inserted
        static::creating(function ($window) {

            // Check if the window being created has window_number equal to 1
            if ($window->window_number == 1) {

                // Query the database to see if a Window with the same step_id
                // and window_number = 1 already exists
                $exists = Window::where('step_id', $window->step_id)
                    ->where('window_number', 1)
                    ->exists();

                // If such a window exists, prevent creation by throwing an exception
                if ($exists) {
                    throw new \Exception('Window number 1 already exists for this step.');
                }
            }
        });
    }

}
