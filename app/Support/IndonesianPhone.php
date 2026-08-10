<?php

namespace App\Support;

class IndonesianPhone
{
    public static function normalize(?string $phone): ?string
    {
        $phone = preg_replace('/\D+/', '', (string) $phone);

        if ($phone === '') {
            return null;
        }

        if (str_starts_with($phone, '0')) {
            return '62' . substr($phone, 1);
        }

        if (str_starts_with($phone, '8')) {
            return '62' . $phone;
        }

        return $phone;
    }

    public static function isValid(string $phone): bool
    {
        return (bool) preg_match('/^62\d{8,13}$/', $phone);
    }
}