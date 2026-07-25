<?php

namespace App\Services;

use App\Models\User;

class RoleRedirector
{
    public static function routeNameForRole(string $role): string
    {
        return match ($role) {
            'super admin' => 'dashboard.index',
            'admin' => 'a.dashboard.index',
            'teacher' => 't.dashboard.index',
            'student' => 's.dashboard.index',
            default => 'login',
        };
    }

    public static function urlForUser(User $user): string
    {
        return route(self::routeNameForRole($user->role));
    }
}
