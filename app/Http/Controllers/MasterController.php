<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class MasterController extends Controller
{
    /**
     * Display master settings.
     */
    public function index(Request $request)
    {
        $whatsapp = Setting::where('type', 'whatsapp')->first();
        $telegram = Setting::where('type', 'telegram')->first();
        $phone = Setting::where('type', 'phone')->first();

        return view('master_setting.index', compact(
            'whatsapp',
            'telegram',
            'phone'
        ));
    }


    /**
     * Show create page.
     */
    public function create()
    {
        return view('master_setting.create');
    }


    /**
     * Save master settings.
     */
    public function store(Request $request)
    {
        $request->validate([
            'whatsapp' => 'nullable|string|max:255',
            'telegram' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
        ]);

        Setting::updateOrCreate(
            ['type' => 'whatsapp'],
            ['value' => $request->whatsapp]
        );

        Setting::updateOrCreate(
            ['type' => 'telegram'],
            ['value' => $request->telegram]
        );

        Setting::updateOrCreate(
            ['type' => 'phone'],
            ['value' => $request->phone]
        );

        return redirect()
            ->route('master_setting.index')
            ->with('success', 'Master settings updated successfully.');
    }


    /**
     * Show edit page.
     */
    public function edit($master_setting)
    {
        $setting = Setting::findOrFail($master_setting);

        return view('master_setting.edit', compact('setting'));
    }


    /**
     * Update a setting.
     */
    public function update(Request $request, $master_setting)
    {
        $request->validate([
            'value' => 'nullable|string|max:255',
        ]);

        $setting = Setting::findOrFail($master_setting);

        $setting->update([
            'value' => $request->value,
        ]);

        return redirect()
            ->route('master_setting.index')
            ->with('success', 'Setting updated successfully.');
    }


    /**
     * Delete a setting.
     */
    public function destroy($master_setting)
    {
        $setting = Setting::findOrFail($master_setting);

        $setting->delete();

        return redirect()
            ->route('master_setting.index')
            ->with('success', 'Setting deleted successfully.');
    }
}