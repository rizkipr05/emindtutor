<?php

namespace App\Services;

use App\Models\NotificationDelivery;
use App\Support\IndonesianPhone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    public function sendOnce(Model $notifiable, string $type, ?string $phone, string $message, ?string $attachmentUrl = null): bool
    {
        if (! $phone) {
            Log::warning('WhatsApp notification skipped because the recipient phone number is empty.', ['type' => $type, 'notifiable_id' => $notifiable->getKey()]);
            return false;
        }

        $delivery = NotificationDelivery::firstOrCreate([
            'notification_type' => $type,
            'notifiable_type' => $notifiable->getMorphClass(),
            'notifiable_id' => $notifiable->getKey(),
            'channel' => 'whatsapp',
        ], [
            'recipient' => $this->normalizePhone($phone),
            'message' => $message,
            'status' => 'pending',
        ]);

        if (! $delivery->wasRecentlyCreated) {
            return $delivery->status === 'sent';
        }

        try {
            $response = FonnteService::sendMessage($delivery->recipient, $message, $attachmentUrl);
            $delivery->update([
                'status' => 'sent',
                'response' => $response,
                'sent_at' => now(),
            ]);

            return true;
        } catch (\Throwable $exception) {
            $delivery->update(['status' => 'failed', 'response' => ['error' => $exception->getMessage()]]);
            Log::warning('WhatsApp notification failed.', ['type' => $type, 'exception' => $exception]);

            return false;
        }
    }

    private function normalizePhone(string $phone): string
    {
        return IndonesianPhone::normalize($phone) ?? '';
    }
}