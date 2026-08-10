<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FonnteService
{
    public static function sendMessage($target, $message)
    {
        $token = config('services.fonnte.token');

        if (! $token) {
            throw new \RuntimeException('Fonnte token has not been configured.');
        }

        $response = Http::timeout(15)->withHeaders([
            'Authorization' => $token,
        ])->post('https://api.fonnte.com/send', [
            'target'  => $target,     // nomor tujuan
            'message' => $message,    // isi pesan
        ]);

        $response->throw();

        $payload = $response->json() ?? [];

        if (($payload['status'] ?? false) !== true) {
            throw new \RuntimeException($payload['reason'] ?? $payload['detail'] ?? 'Fonnte rejected the message.');
        }

        return $payload;
    }
}
