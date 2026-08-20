<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Company;

class CompanyController extends Controller
{
    public function index() 
    {
        
    }
    
    public function edit($id)
    {
        $company = Company::findOrFail($id);
        return view('admin.company.edit', compact('company'));
    }

    public function update(Request $request, $id)
    {
        $company = Company::findOrFail($id);

        $validated = $request->validate([
            'nama_wakil_syarikat' => 'required|string|max:255',
            'pic_telefon' => 'nullable|string|max:50',
            'pic_email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'link' => 'nullable|string|max:255',
            'no_telefon' => 'nullable|string|max:50',
            'logo_syarikat' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'nomor_pendaftaran_syarikat' => 'nullable|string|max:255',
            // ❌ status tidak divalidasi
        ]);

        // Handle logo upload
        if ($request->hasFile('logo_syarikat')) {

            // Delete old logo
            $uploadDirectory = is_dir(base_path('../public_html'))
                ? base_path('../public_html/uploads')
                : base_path('uploads');

            if (! File::isDirectory($uploadDirectory)) {
                File::makeDirectory($uploadDirectory, 0775, true, true);
            }

            if ($company->logo_syarikat && file_exists($uploadDirectory . '/' . $company->logo_syarikat)) {
                @unlink($uploadDirectory . '/' . $company->logo_syarikat);
            }

            $file = $request->file('logo_syarikat');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($uploadDirectory, $filename);

            $validated['logo_syarikat'] = $filename;
        }

        // Update only fields in $validated
        $company->update($validated);

        return redirect()->back()->with('success', 'Company info updated successfully.');
    }


}
