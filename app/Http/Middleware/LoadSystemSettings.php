<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SystemSetting;

class LoadSystemSettings
{
    public function handle(Request $request, Closure $next)
    {
        // Retrieve the settings from the database
        $settings = SystemSetting::first();
        
        // If settings exist, add them to the session
        if ($settings) {
            foreach ($settings->toArray() as $key => $value) {
                session([$key => $value]);
            }
        }

        return $next($request);
    }
}
