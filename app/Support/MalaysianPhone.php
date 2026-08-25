<?php

namespace App\Support;

class MalaysianPhone
{
    public static function normalize(?string $phone): ?string
    {
        $phone = preg_replace('/\D+/', '', (string) $phone);

        if ($phone === '') {
            return null;
        }

        if (str_starts_with($phone, '0')) {
            return '60' . substr($phone, 1);
        }

        if (str_starts_with($phone, '1')) {
            return '60' . $phone;
        }

        return $phone;
    }

    public static function isValid(string $phone): bool
    {
        return (bool) preg_match('/^60\d{7,11}$/', $phone);
    }
}
