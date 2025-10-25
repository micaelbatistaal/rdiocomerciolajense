<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class RadioSettingsController extends Controller
{
    public function update(Request $request)
    {
        $data = $request->validate([
            'url' => ['required', 'url'],
        ]);

        Setting::updateOrCreate(
            ['key' => 'radio_stream_url'],
            ['value' => $data['url']]
        );

        return back()->with('success', 'URL do stream atualizada.');
    }
}
