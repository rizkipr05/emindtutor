<?php

namespace Tests\Feature;

use App\Mail\CompanyReceiptMail;
use App\Models\Company;
use App\Models\CompanyInvoice;
use App\Models\PaymentGateway;
use App\Models\Student;
use App\Models\SubscriptionPackage;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_the_package_payment_page(): void
    {
        [$admin, $company] = $this->makeAdminWithCompany();
        $package = $this->makePackage();

        $this->actingAs($admin)
            ->get(route('subscribe.payment', $package->id))
            ->assertOk()
            ->assertSee($package->nama_pakej);
    }

    public function test_admin_can_create_toyyibpay_bill_and_be_redirected_to_pending_page(): void
    {
        [$admin, $company] = $this->makeAdminWithCompany();
        $package = $this->makePackage();
        $this->makeSuperAdminGateway();

        Http::fake([
            '*' => Http::response([
                ['BillCode' => 'toyyibpay-bill-123'],
            ], 200),
        ]);

        $response = $this->actingAs($admin)
            ->post(route('payment.show', $package->id), [
                'package_id' => $package->id,
            ]);

        $invoice = CompanyInvoice::where('company_id', $company->id)->firstOrFail();

        $response->assertRedirect(route('payment.pending', [$invoice->id, $package->id]));
        $this->assertSame('pending', $invoice->fresh()->status_bayaran);
        $this->assertSame('toyyibpay-bill-123', $invoice->fresh()->toyyibpay_bill_code);
        $this->assertSame('https://toyyibpay.com/toyyibpay-bill-123', $invoice->fresh()->payment_url);
    }

    public function test_webhook_marks_invoice_paid_and_sends_receipt_mail(): void
    {
        Mail::fake();

        [$admin, $company] = $this->makeAdminWithCompany();
        $package = $this->makePackage([
            'duration_days' => 30,
        ]);

        $invoice = CompanyInvoice::create([
            'company_id' => $company->id,
            'package_id' => $package->id,
            'nomor_invoice' => 'INV-20260720-0001',
            'external_id' => 'EMT-20260720-0001-123456',
            'jumlah' => 100000,
            'status_bayaran' => 'pending',
        ]);

        $this->makeSuperAdminGateway();
        $payload = [
            'status' => '1',
            'order_id' => $invoice->external_id,
            'refno' => 'TP-001',
            'transaction_time' => '2026-07-20 10:00:00',
        ];
        $payload['hash'] = md5('test-secret1' . $invoice->external_id . 'TP-001ok');
        $response = $this->postJson(route('payment.webhook'), $payload);

        $response->assertOk()->assertJson([
            'message' => 'ToyyibPay callback received',
        ]);

        $invoice->refresh();
        $this->assertSame('paid', $invoice->status_bayaran);
        $this->assertNotNull($invoice->tarikh_terbit);
        $this->assertNotNull($invoice->tarikh_tamat);

        Mail::assertSent(CompanyReceiptMail::class, function (CompanyReceiptMail $mail) use ($invoice) {
            return $mail->invoice->is($invoice);
        });
    }

    private function makeSuperAdminGateway(): PaymentGateway
    {
        return PaymentGateway::create([
            'provider' => 'toyyibpay',
            'user_secret_key' => 'test-secret',
            'category_code' => 'test-category',
            'base_url' => 'https://toyyibpay.com',
            'api_url' => 'https://toyyibpay.com',
            'payment_channel' => 0,
            'expiry_days' => 1,
            'is_active' => true,
        ]);
    }

    public function test_superadmin_can_create_manual_invoice_and_confirm_payment(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super admin',
        ]);

        $companyOwner = User::factory()->create([
            'role' => 'admin',
        ]);

        $company = Company::create([
            'user_id' => $companyOwner->id,
            'nama_wakil_syarikat' => 'Manual Payment Company',
            'alamat' => 'Jalan Test 10',
            'no_telefon' => '0123456700',
            'status' => false,
        ]);

        $package = $this->makePackage();

        $storeResponse = $this->actingAs($superAdmin)
            ->post(route('invoices.store', $company->id), [
                'package_id' => $package->id,
                'duration_days' => 14,
                'jumlah' => 250000,
                'status_bayaran' => 'paid',
            ]);

        $storeResponse->assertRedirect(route('companies.index'));

        $invoice = CompanyInvoice::where('company_id', $company->id)->firstOrFail();
        $this->assertSame('paid', $invoice->status_bayaran);
        $this->assertSame('active', $invoice->status);
        $this->assertTrue((bool) $company->fresh()->status);

        $company->update(['status' => false]);
        $invoice->update([
            'status_bayaran' => 'upcoming',
            'status' => 'inactive',
            'tarikh_terbit' => null,
            'tarikh_tamat' => null,
        ]);

        $confirmResponse = $this->actingAs($superAdmin)
            ->post(route('invoices.confirmManual', $invoice->id));

        $confirmResponse->assertRedirect();
        $invoice->refresh();
        $this->assertSame('paid', $invoice->status_bayaran);
        $this->assertSame('active', $invoice->status);
        $this->assertTrue((bool) $company->fresh()->status);
    }

    private function makeAdminWithCompany(): array
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $company = Company::create([
            'user_id' => $admin->id,
            'nama_wakil_syarikat' => 'Payment Smoke Company',
            'alamat' => 'Jalan Payment 1',
            'no_telefon' => '0123456789',
            'status' => true,
        ]);

        return [$admin, $company];
    }

    private function makePackage(array $overrides = []): SubscriptionPackage
    {
        return SubscriptionPackage::create(array_merge([
            'is_trial' => false,
            'nama_pakej' => 'Smoke Package',
            'duration_days' => 30,
            'harga' => 100000,
            'maks_pelajar' => 10,
            'maks_guru' => 5,
            'maks_kelas' => 3,
            'deskripsi' => 'Payment smoke test package',
            'status' => 'aktif',
            'pricing_per_student' => false,
            'harga_per_pelajar' => null,
        ], $overrides));
    }
}
