<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubscriptionPackage;

class SubscriptionPackageController extends Controller
{
    public function index()
    {
        $packages = SubscriptionPackage::all();
        return view('super-admin.langganan.index', compact('packages'));
    }

    public function create()
    {
        return view('super-admin.langganan.subpack.create');
    }

    public function store(Request $request)
    {
        // Check if pricing is per student (value = '1' for per student, '0' for fixed)
        $isPerStudent = $request->pricing_per_student === '1';
        
        // Base validation rules
        $rules = [
            'nama_pakej' => 'required|string|max:255',
            'duration_days' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,tamat',
            'is_trial' => 'required|in:0,1',
            'pricing_per_student' => 'required|in:0,1',
        ];
        
        // Conditional validation based on pricing type
        if ($isPerStudent) {
            // Per Student: require harga_per_pelajar, make others nullable
            $rules['harga_per_pelajar'] = 'required|numeric|min:0';
            $rules['harga'] = 'nullable|numeric';
            $rules['maks_pelajar'] = 'nullable|integer';
            $rules['maks_guru'] = 'nullable|integer';
            $rules['maks_kelas'] = 'nullable|integer';
        } else {
            // Fixed Price: require harga, limitations are optional
            $rules['harga'] = 'required|numeric|min:0';
            $rules['harga_per_pelajar'] = 'nullable|numeric';
            $rules['maks_pelajar'] = 'nullable|integer|min:0';
            $rules['maks_guru'] = 'nullable|integer|min:0';
            $rules['maks_kelas'] = 'nullable|integer|min:0';
        }
        
        $validated = $request->validate($rules);
        
        // Prepare data for creation
        $data = [
            'is_trial' => $request->is_trial == '1',
            'pricing_per_student' => $isPerStudent,
            'nama_pakej' => $request->nama_pakej,
            'duration_days' => $request->duration_days,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
        ];
        
        // Set pricing and limitations based on type
        if ($isPerStudent) {
            // Per Student pricing
            $data['harga'] = 0;
            $data['harga_per_pelajar'] = $request->harga_per_pelajar;
            $data['maks_pelajar'] = 0; // No limit
            $data['maks_guru'] = 0; // No limit
            $data['maks_kelas'] = 0; // No limit
        } else {
            // Fixed pricing
            $data['harga'] = $request->harga;
            $data['harga_per_pelajar'] = null;
            $data['maks_pelajar'] = $request->filled('maks_pelajar') ? $request->maks_pelajar : 0;
            $data['maks_guru'] = $request->filled('maks_guru') ? $request->maks_guru : 0;
            $data['maks_kelas'] = $request->filled('maks_kelas') ? $request->maks_kelas : 0;
        }
        
        SubscriptionPackage::create($data);
        
        return redirect()->route('subscription-packages.index')
            ->with('success', 'Pakej berjaya ditambah.');
    }

    public function edit(SubscriptionPackage $subscriptionPackage)
    {
        return view('super-admin.langganan.subpack.edit', compact('subscriptionPackage'));
    }

    public function update(Request $request, SubscriptionPackage $subscriptionPackage)
    {
        $request->validate([
            'nama_pakej' => 'required|string|max:255',
            'duration_days' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'maks_pelajar' => 'nullable|integer|min:0',
            'maks_guru' => 'nullable|integer|min:0',
            'maks_kelas' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,tamat',
            'is_trial' => 'sometimes|boolean',
        ]);

        $subscriptionPackage->update([
            'is_trial' => $request->is_trial,
            'nama_pakej' => $request->nama_pakej,
            'duration_days' => $request->duration_days,
            'harga' => $request->harga,
            'maks_pelajar' => $request->filled('maks_pelajar') ? $request->maks_pelajar : 0,
            'maks_guru' => $request->filled('maks_guru') ? $request->maks_guru : 0,
            'maks_kelas' => $request->filled('maks_kelas') ? $request->maks_kelas : 0,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
        ]);

        return redirect()->route('subscription-packages.index')->with('success', 'Pakej berjaya dikemas kini.');
    }

    public function show(SubscriptionPackage $subscriptionPackage)
    {
        return view('super-admin.langganan.subpack.show', compact('subscriptionPackage'));
    }

    public function destroy(SubscriptionPackage $subscriptionPackage)
    {
        $subscriptionPackage->delete();
        return redirect()->route('subscription-packages.index')->with('success', 'Pakej berjaya dihapus.');
    }

    public function editPerStudent()
    {
        // Ambil 1 data khusus untuk subscription by student
        $subscriptionPackage = SubscriptionPackage::where('nama_pakej', 'Per Pelajar')->first();

        // Kalau belum ada, buat dulu secara default
        if (!$subscriptionPackage) {
            $subscriptionPackage = SubscriptionPackage::create([
                'is_trial' => false,
                'nama_pakej' => 'Per Pelajar',
                'duration_days' => 30,
                'harga' => 0,
                'maks_pelajar' => 0,
                'maks_guru' => 0,
                'maks_kelas' => 0,
                'deskripsi' => 'Langganan berdasarkan jumlah pelajar aktif',
                'status' => 'aktif',
            ]);
        }

        return view('super-admin.langganan.subpack.edit-per-student', compact('subscriptionPackage'));
    }

    public function updatePerStudent(Request $request)
    {
        $request->validate([
            'harga' => 'required|numeric|min:0',
            'status' => 'required|in:aktif,tamat',
        ]);

        $subscriptionPackage = SubscriptionPackage::where('nama_pakej', 'Per Pelajar')->first();

        if ($subscriptionPackage) {
            $subscriptionPackage->update([
                'harga' => $request->harga,
                'status' => $request->status,
            ]);
        }

        return redirect()->route('subscription-packages.index')
            ->with('success', 'Harga per pelajar berjaya dikemas kini.');
    }
}
