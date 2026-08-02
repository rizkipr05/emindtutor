<div class="grid grid-cols-1 gap-5 md:grid-cols-2">
    <div class="md:col-span-2 rounded-lg border border-cyan-100 bg-cyan-50 p-4 text-sm text-cyan-900">
        Konfigurasi ini digunakan khusus untuk bill ToyyibPay. User Secret Key disimpan dalam bentuk terenkripsi dan tidak ditampilkan kembali.
    </div>
    <div>
        <label class="mb-1 block font-semibold">User Secret Key</label>
        <input type="password" name="user_secret_key" value="" placeholder="{{ $gateway ? 'Tersimpan — masukkan ulang bila ingin mengganti' : 'Masukkan User Secret Key' }}" class="w-full rounded-lg border px-3 py-2" required>
        @error('user_secret_key') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="mb-1 block font-semibold">Category Code</label>
        <input type="text" name="category_code" value="{{ old('category_code', $gateway?->category_code) }}" class="w-full rounded-lg border px-3 py-2" required>
        @error('category_code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="mb-1 block font-semibold">Payment URL</label>
        <input type="url" name="base_url" value="{{ old('base_url', $gateway?->base_url ?? 'https://toyyibpay.com') }}" class="w-full rounded-lg border px-3 py-2" required>
    </div>
    <div>
        <label class="mb-1 block font-semibold">API URL</label>
        <input type="url" name="api_url" value="{{ old('api_url', $gateway?->api_url ?? 'https://toyyibpay.com') }}" class="w-full rounded-lg border px-3 py-2" required>
    </div>
    <div>
        <label class="mb-1 block font-semibold">Kanal Pembayaran</label>
        <select name="payment_channel" class="w-full rounded-lg border px-3 py-2">
            <option value="0" @selected(old('payment_channel', $gateway?->payment_channel ?? 0) == 0)>FPX / Online Banking</option>
            <option value="1" @selected(old('payment_channel', $gateway?->payment_channel) == 1)>Kartu Kredit</option>
            <option value="2" @selected(old('payment_channel', $gateway?->payment_channel) == 2)>FPX & Kartu Kredit</option>
        </select>
    </div>
    <div>
        <label class="mb-1 block font-semibold">Masa Aktif Bill (hari)</label>
        <input type="number" min="1" max="100" name="expiry_days" value="{{ old('expiry_days', $gateway?->expiry_days ?? 1) }}" class="w-full rounded-lg border px-3 py-2" required>
    </div>
    <label class="flex items-center gap-2 md:col-span-2">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $gateway?->is_active ?? true))>
        <span class="font-semibold">Aktifkan ToyyibPay</span>
    </label>
</div>
