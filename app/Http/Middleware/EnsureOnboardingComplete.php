<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            if ($user->must_change_password) {
                return redirect()->route('onboarding.password');
            }
            if (!$user->onboarding_completed && !$user->is_admin) {
                return redirect()->route('onboarding.start');
            }
        }

        return $next($request);
    }
}
