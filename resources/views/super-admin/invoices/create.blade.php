@extends('layouts.superadmin')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Buat Subscription Manual</h1>
                <p class="text-gray-600">Pilih paket. Jika paket per-pelajar, masukkan jumlah siswanya.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-lg font-semibold text-sm">
                    Client: {{ $company->user->name ?? $company->nama_wakil_syarikat }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            @foreach($packages as $package)
                <div class="relative bg-white border rounded-2xl p-6 shadow-sm hover:shadow-lg hover:border-blue-500 transition duration-300 cursor-pointer group"
                     onclick="selectPackage(this)"
                     {{-- Simpan semua data logic di sini --}}
                     data-id="{{ $package->id }}"
                     data-name="{{ $package->nama_pakej }}"
                     data-duration="{{ $package->duration_days }}"
                     
                     {{-- Logic Per Student --}}
                     data-is-per-student="{{ $package->pricing_per_student }}" 
                     data-price-fixed="{{ $package->harga }}"
                     data-price-per-student="{{ $package->harga_per_pelajar }}">
                    
                    <div class="absolute top-0 right-0 p-4 opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded-full">Pilih</span>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-800">{{ $package->nama_pakej }}</h3>

                    {{-- Tampilan Harga di Kartu --}}
                    @if($package->pricing_per_student)
                        <p class="mt-2 text-3xl font-bold text-gray-900">
                            RM {{ number_format($package->harga_per_pelajar, 2) }}
                            <span class="text-sm font-normal text-gray-500">/ pelajar</span>
                        </p>
                    @else
                        <p class="mt-2 text-3xl font-bold text-gray-900">
                            RM {{ number_format($package->harga, 2) }}
                            <span class="text-sm font-normal text-gray-500">/ paket</span>
                        </p>
                    @endif
                    
                    <p class="text-sm text-gray-500 mt-1">Durasi: {{ $package->duration_days }} Hari</p>
                </div>
            @endforeach
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden" id="form-section">
            <div class="p-6 bg-gray-800 text-white">
                <h3 class="text-lg font-bold">Detail Invoice</h3>
            </div>

            <form action="{{ route('invoices.store', $company->id) }}" method="POST" class="p-6 space-y-6">
                @csrf
                <input type="hidden" name="package_id" id="package_id">
                
                <input type="hidden" name="is_per_student" id="is_per_student" value="0">
                <input type="hidden" name="base_price_per_student" id="base_price_per_student" value="0">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Paket Terpilih</label>
                        <input type="text" id="display_package_name" class="block w-full rounded-md border-gray-300 bg-gray-100 text-gray-500 sm:text-sm p-2.5" readonly>
                    </div>

                    <div id="student_count_container" class="hidden md:col-span-2 bg-yellow-50 p-4 rounded-md border border-yellow-200">
                        <label class="block text-sm font-bold text-gray-800 mb-1">
                            Masukkan Jumlah Pelajar <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center">
                            <input type="number" name="student_count" id="student_count" min="1" value="1"
                                class="block w-40 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2.5 border"
                                oninput="calculateTotal()">
                            <span class="ml-3 text-sm text-gray-600">x Harga per pelajar = Total Otomatis</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Paket ini mengharuskan Anda mengisi jumlah siswa untuk menghitung total tagihan.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Durasi (Hari)</label>
                        <input type="number" name="duration_days" id="duration_days" required
                            class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2.5 border">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Pembayaran (RM)</label>
                        <input type="number" name="jumlah" id="jumlah" required step="0.01"
                            class="block w-full rounded-md border-gray-300 bg-gray-50 focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2.5 border">
                        <p class="text-xs text-gray-500 mt-1">Dihitung otomatis, tapi bisa diedit manual.</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran</label>
                        <select name="status_bayaran" class="block w-full rounded-md border-gray-300 sm:text-sm p-2.5 border">
                            <option value="paid" selected>PAID (Lunas)</option>
                            <option value="upcoming">UPCOMING (Belum Bayar)</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-bold rounded-md hover:bg-blue-700 shadow-lg">
                        Simpan & Aktifkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function selectPackage(element) {
        // 1. Ambil Data
        const id = element.getAttribute('data-id');
        const name = element.getAttribute('data-name');
        const duration = element.getAttribute('data-duration');
        
        // Logic Per Student
        const isPerStudent = element.getAttribute('data-is-per-student') == '1';
        const priceFixed = parseFloat(element.getAttribute('data-price-fixed'));
        const pricePerStudent = parseFloat(element.getAttribute('data-price-per-student'));

        // 2. Set Nilai ke Input Hidden/Field Biasa
        document.getElementById('package_id').value = id;
        document.getElementById('display_package_name').value = name;
        document.getElementById('duration_days').value = duration;
        document.getElementById('is_per_student').value = isPerStudent ? 1 : 0;
        document.getElementById('base_price_per_student').value = pricePerStudent;

        // 3. Toggle Tampilan Input Student
        const studentContainer = document.getElementById('student_count_container');
        const studentInput = document.getElementById('student_count');
        
        if (isPerStudent) {
            // Jika Per Student: Munculkan input siswa, reset jumlah jadi 1, hitung total
            studentContainer.classList.remove('hidden');
            studentInput.value = 1; 
            studentInput.required = true;
            calculateTotal(); // Hitung: 1 * harga_per_pelajar
        } else {
            // Jika Fixed: Sembunyikan input siswa, Total = Harga Fixed
            studentContainer.classList.add('hidden');
            studentInput.required = false;
            document.getElementById('jumlah').value = priceFixed;
        }

        // 4. Highlight Kartu (Visual)
        document.querySelectorAll('.grid > div').forEach(div => {
            div.classList.remove('ring-2', 'ring-blue-600', 'bg-blue-50');
        });
        element.classList.add('bg-blue-50', 'ring-2', 'ring-blue-600');

        // Scroll ke form
        document.getElementById('form-section').scrollIntoView({ behavior: 'smooth' });
    }

    function calculateTotal() {
        // Hanya jalan jika mode per student
        const isPerStudent = document.getElementById('is_per_student').value == '1';
        if (isPerStudent) {
            const count = parseInt(document.getElementById('student_count').value) || 0;
            const price = parseFloat(document.getElementById('base_price_per_student').value) || 0;
            const total = count * price;
            
            document.getElementById('jumlah').value = total.toFixed(2);
        }
    }
</script>
@endsection