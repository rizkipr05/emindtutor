<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('system_settings')
            ->where('setting_key', 'payment_confirmation_body')
            ->update(['setting_value' => '', 'updated_at' => now()]);
    }

    public function down(): void
    {
        // The previous template is intentionally not restored.
    }
};