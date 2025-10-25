<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'banners.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
            'link_url'  => ['nullable', 'url'],
        ]);

        if ($request->hasFile('banners')) {
            foreach ($request->file('banners') as $file) {
                $path = $file->store('banners', 'public');
                Banner::create([
                    'image_path' => $path,
                    'link_url'   => $request->input('link_url'),
                    'active'     => true,
                ]);
            }
        }

        return back()->with('success', 'Banners enviados com sucesso.');
    }

    public function toggle(Banner $banner)
    {
        $banner->active = ! $banner->active;
        $banner->save();

        return back();
    }

    public function destroy(Banner $banner)
    {
        Storage::disk('public')->delete($banner->image_path);
        $banner->delete();

        return back()->with('success', 'Banner excluído.');
    }
}
