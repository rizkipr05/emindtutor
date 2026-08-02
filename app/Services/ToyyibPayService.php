<?php

namespace App\Services;

use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class ToyyibPayService
{
    public function createBill(array $attributes, PaymentGateway $gateway): array
    {
        $secretKey = (string) $gateway->user_secret_key;
        $categoryCode = (string) $gateway->category_code;

        if ($secretKey === '' || $categoryCode === '') {
            throw new RuntimeException('ToyyibPay belum dikonfigurasi. Isi TOYYIBPAY_USER_SECRET_KEY dan TOYYIBPAY_CATEGORY_CODE.');
        }

        $response = Http::asForm()->post($this->apiUrl($gateway, '/index.php/api/createBill'), [
            'userSecretKey' => $secretKey,
            'categoryCode' => $categoryCode,
            'billName' => $this->cleanText($attributes['name'], 30),
            'billDescription' => $this->cleanText($attributes['description'], 100),
            'billPriceSetting' => 1,
            'billPayorInfo' => 1,
            // ToyyibPay expects the amount in cents; application prices are in RM.
            'billAmount' => (int) round(((float) $attributes['amount']) * 100),
            'billReturnUrl' => $attributes['return_url'],
            'billCallbackUrl' => $attributes['callback_url'],
            'billExternalReferenceNo' => $attributes['reference'],
            'billTo' => $this->cleanText($attributes['payer_name'] ?? '', 30),
            'billEmail' => $attributes['payer_email'] ?? '',
            'billPhone' => $attributes['payer_phone'] ?? '',
            'billPaymentChannel' => $gateway->payment_channel,
            'billExpiryDays' => $gateway->expiry_days,
        ]);

        $data = $response->json();
        $billCode = is_array($data) ? ($data[0]['BillCode'] ?? null) : null;

        if (! $response->successful() || ! $billCode) {
            throw new RuntimeException('ToyyibPay gagal membuat bill: ' . ($this->errorMessage($data) ?: 'Respons tidak valid.'));
        }

        return [
            'bill_code' => (string) $billCode,
            'payment_url' => rtrim((string) $gateway->base_url, '/') . '/' . $billCode,
        ];
    }

    public function isValidCallback(array $payload, PaymentGateway $gateway): bool
    {
        foreach (['status', 'order_id', 'refno', 'hash'] as $field) {
            if (! array_key_exists($field, $payload)) {
                return false;
            }
        }

        $expected = md5((string) $gateway->user_secret_key
            . $payload['status'] . $payload['order_id'] . $payload['refno'] . 'ok');

        return hash_equals($expected, (string) $payload['hash']);
    }

    public function hasSuccessfulTransaction(string $billCode, PaymentGateway $gateway): bool
    {
        $response = Http::asForm()->post($this->apiUrl($gateway, '/index.php/api/getBillTransactions'), [
            'billCode' => $billCode,
            'billpaymentStatus' => '1',
        ]);

        $transactions = $response->json();

        return $response->successful()
            && is_array($transactions)
            && collect($transactions)->contains(fn ($transaction) => (string) ($transaction['billpaymentStatus'] ?? '') === '1');
    }

    private function apiUrl(PaymentGateway $gateway, string $path): string
    {
        return rtrim((string) $gateway->api_url, '/') . $path;
    }

    private function cleanText(string $value, int $limit): string
    {
        $value = preg_replace('/[^A-Za-z0-9 _-]/', ' ', $value) ?? '';

        return substr(trim(preg_replace('/\s+/', ' ', $value) ?? ''), 0, $limit) ?: 'Payment';
    }

    private function errorMessage(mixed $data): ?string
    {
        if (! is_array($data)) {
            return null;
        }

        return $data['message'] ?? $data[0]['message'] ?? $data[0]['Error'] ?? null;
    }
}
