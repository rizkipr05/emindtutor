<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\CompanyInvoice;
use App\Models\SubscriptionPackage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Tests\TestCase;

class AdminSettingsAndSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_settings_page_and_subscription_card_work(): void
    {
        // 1. Create admin user
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        // 2. Create company
        $company = Company::create([
            'user_id' => $user->id,
            'nama_wakil_syarikat' => 'Admin Test Company',
            'alamat' => 'No 1 Malaysia',
            'no_telefon' => '0123456789',
            'status' => true,
        ]);

        // 3. Create packages & active subscription
        $package = SubscriptionPackage::create([
            'nama_pakej' => 'Premium Test',
            'harga' => 100,
            'duration_days' => 30,
            'maks_pelajar' => 100,
            'maks_guru' => 10,
            'maks_kelas' => 5,
            'status' => 'active',
        ]);

        $today = Carbon::today();
        CompanyInvoice::create([
            'company_id' => $company->id,
            'package_id' => $package->id,
            'nomor_invoice' => 'INV-TEST-001',
            'jumlah' => 100,
            'status' => 'active',
            'status_bayaran' => 'paid',
            'tarikh_terbit' => $today->copy()->subDays(10)->toDateString(), // Started 10 days ago
            'tarikh_tamat' => $today->copy()->addDays(20)->toDateString(),  // Ends in 20 days (total 30 days)
        ]);

        // 4. Test Dashboard contains expiry widget
        $responseDashboard = $this->actingAs($user)
            ->get(route('a.dashboard.index'));

        $responseDashboard->assertOk();
        $responseDashboard->assertSee('Tarikh Tamat Langganan');
        $responseDashboard->assertSee($today->copy()->addDays(20)->translatedFormat('d F Y'));
        $responseDashboard->assertSee('21 days'); 
        $responseDashboard->assertSee('Perbaharui Langganan'); // since < 30 days remaining

        // 5. Test Settings Page displays info
        $responseSettings = $this->actingAs($user)
            ->get(route('admin.settings.index'));

        $responseSettings->assertOk();
        $responseSettings->assertSee('Tetapan Sistem');
        $responseSettings->assertSee('Premium Test');
        $responseSettings->assertSee('Status Langganan');
        $responseSettings->assertSee('Baki Masa Langganan');
        $responseSettings->assertSee('21 hari tersisa');
        $responseSettings->assertSee('Maklumat Sistem');
    }
}
