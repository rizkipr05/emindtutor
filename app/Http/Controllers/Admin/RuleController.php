<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TeacherRule;

class RuleController extends Controller
{
    // Menampilkan semua rules beserta company
    public function index()
    {
        $companyId = auth()->user()->company->id;
        $teacherRule = TeacherRule::where('company_id', $companyId)->first();

        return view('admin.teachers.rules.index', compact('teacherRule'));
    }

    // Form tambah aturan
    public function create()
    {
        return view('admin.teachers.rules.create');
    }

    // Simpan aturan baru
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
        ]);
        // dd("hi");
        $companyId = Auth::user()->company->id;

        // Cek apakah company sudah punya aturan
        if (TeacherRule::where('company_id', $companyId)->exists()) {
            return redirect()->route('admin.teachers.rules.index')
                ->with('error', 'Company ini sudah memiliki aturan.');
        }
        // dd($request->content);

        TeacherRule::create([
            'company_id' => $companyId,
            'rule_description' => $request->content,
        ]);

        return redirect()->route('admin.teachers.rules.index')
            ->with('success', 'Aturan guru berhasil ditambahkan.');
    }

    // Form edit aturan
    public function edit($id)
    {
        $rule = TeacherRule::findOrFail($id);

        return view('admin.teachers.rules.edit', compact('rule'));
    }

    // Update aturan
    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $rule = TeacherRule::findOrFail($id);
        $rule->update([
            'rule_description' => $request->content,
        ]);

        return redirect()->route('admin.teachers.rules.index')
            ->with('success', 'Aturan guru berhasil diperbarui.');
    }

    // Hapus aturan
    public function destroy($id)
    {
        $rule = TeacherRule::findOrFail($id);
        $rule->delete();

        return redirect()->route('admin.teachers.rules.index')
            ->with('success', 'Aturan guru berhasil dihapus.');
    }
}
