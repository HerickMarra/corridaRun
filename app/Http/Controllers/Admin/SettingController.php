<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = $request->except('_token', '_method');

        foreach ($settings as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            if ($setting) {
                // Handling files (logo, etc)
                if ($request->hasFile($key)) {
                    $path = $request->file($key)->store('settings', 'public');
                    $value = $path;
                }

                $setting->update(['value' => $value]);
                Cache::forget("setting.{$key}");
            }
        }

        return redirect()->back()->with('success', 'Configurações atualizadas com sucesso!');
    }
}
