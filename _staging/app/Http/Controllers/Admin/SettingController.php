<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $whatsappNumber = Setting::get('whatsapp_number');

        return view('admin.settings', compact('whatsappNumber'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'whatsapp_number' => ['required', 'string', 'max:20', 'regex:/^62\d{8,15}$/'],
        ]);

        Setting::set('whatsapp_number', $data['whatsapp_number']);

        return redirect()
            ->route('admin.settings.edit')
            ->with('success', 'Nomor WhatsApp berhasil diperbarui.');
    }
}
