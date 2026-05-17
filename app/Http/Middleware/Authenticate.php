<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {

            // Admin guard
            if ($request->routeIs('admins.*')
                || $request->is('dashboard')
                || $request->is('dashboard/*')) {

                return route('admin.login');
            }

            // Student guard
            return route('student.login');
        }

        return null;
    }
}
