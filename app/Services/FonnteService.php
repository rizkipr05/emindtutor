<?php

namespace App\Services;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Http;

class FonnteService
{
    public static function sendMessage($target, $message, ?string $attachmentUrl = null)
    {
        $token = SystemSetting::fonnteToken() ?: config('services.fonnte.token');

        if (! $token) {
            throw new \RuntimeException('Fonnte token has not been configured.');
        }

        $payload = [
            'target'  => $target,
            'message' => $message,
        ];

        if ($attachmentUrl) {
            $payload['url'] = $attachmentUrl;
        }

        $response = Http::timeout(15)->withHeaders([
            'Authorization' => $token,
        ])->post('https://api.fonnte.com/send', $payload);

        $response->throw();

        $payload = $response->json() ?? [];

        if (($payload['status'] ?? false) !== true) {
            throw new \RuntimeException($payload['reason'] ?? $payload['detail'] ?? 'Fonnte rejected the message.');
        }

        return $payload;
    }
}
