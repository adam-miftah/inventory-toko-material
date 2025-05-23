<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Company; // Asumsikan Anda memiliki model Company
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Menampilkan form untuk mengedit profil perusahaan.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit()
    {
        $company = Company::first(); // Ambil data perusahaan pertama (atau sesuaikan logika jika berbeda)
        return view('settings.company.edit', compact('company'));
    }

    /**
     * Menyimpan atau memperbarui data profil perusahaan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            // Tambahkan validasi untuk field lain sesuai kebutuhan
        ]);

        $company = Company::first(); // Ambil data perusahaan pertama (atau sesuaikan logika)

        if ($company) {
            $company->update($request->all());
            session()->flash('success', 'Profil perusahaan berhasil diperbarui.');
        } else {
            // Jika tidak ada data perusahaan, buat baru (opsional, tergantung kebutuhan)
            Company::create($request->all());
            session()->flash('success', 'Profil perusahaan berhasil disimpan.');
        }

        return redirect()->route('settings.company.edit');
    }
}