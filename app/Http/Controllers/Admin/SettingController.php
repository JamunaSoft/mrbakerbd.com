<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
class SettingController extends Controller
{
    public function settings()
    {
        abort_unless(Auth::user()->hasRole('Admin'), 403);

        $setting = Setting::where(['id' => '1'])->first();

        return view('backend.settings', ['setting' => $setting]);
    }

    public function update(Request $request, Setting $setting)
    {
        abort_unless(Auth::user()->hasRole('Admin'), 403);

        $request->validate([
            'google_tag_manager_id' => ['nullable', 'string', 'max:64', 'regex:/\AGTM-[A-Z0-9]+\z/', 'required_if:tracking_delivery,gtm'],
            'ga4_measurement_id' => ['nullable', 'string', 'max:64', 'regex:/\AG-[A-Z0-9]+\z/'],
            'google_ads_id' => ['nullable', 'string', 'max:64', 'regex:/\AAW-[0-9]+\z/', 'required_with:google_ads_conversion_label'],
            'google_ads_conversion_label' => ['nullable', 'string', 'max:128', 'regex:/\A[A-Za-z0-9_-]+\z/', 'required_with:google_ads_id'],
            'meta_pixel_ids' => ['nullable', 'string', 'max:255', 'regex:/\A[0-9]+(?:\s*,\s*[0-9]+)*\z/'],
            'tracking_delivery' => ['required', 'in:website,gtm'],
            'enhanced_conversions_enabled' => ['nullable', 'boolean'],
            'name'         => 'required|max:150',
            'logo'         => 'nullable|image',
            'phone'        => 'required',
            'email'        => 'required',
        ],[
            'name.required' => 'Name can not be empty!',
        ]);

        $setting->name = $request->name;

        if($request->hasFile('logo'))
        {
            if(File::exists('images/' . $setting->logo))
            {
                File::delete('images/' . $setting->logo);
            }

            $logo = $request->file('logo');
            $img = time() .'.'. $logo->getClientOriginalExtension();
            $location = public_path('images/' . $img);
            $manager = new ImageManager(new Driver());
            $manager->read($logo)->resize(150)->save($location);

            $setting->logo = $img;
        }

        $setting->site_title = $request->site_title;

        if($request->hasFile('site_logo'))
        {
            if(File::exists('images/' . $setting->site_logo))
            {
                File::delete('images/' . $setting->site_logo);
            }

            $site_logo = $request->file('site_logo');
            $site_img = time() .'s.'. $site_logo->getClientOriginalExtension();
            $location = public_path('images/' . $site_img);
            $manager = new ImageManager(new Driver());
            $manager->read($site_logo)->save($location);

            $setting->site_logo = $site_img;
        }

        $setting->phone = $request->phone;
        $setting->email = $request->email;
        $setting->address = $request->address;
        $setting->admin_url = $request->admin_url;
        $setting->site_url = $request->site_url;
        $setting->date_format = $request->date_format;
        $setting->facebook_url = $request->facebook_url;
        $setting->twitter_url = $request->twitter_url;
        $setting->instagram_url = $request->instagram_url;
        $setting->google_tag_manager_id = $request->input('google_tag_manager_id') ?? '';
        foreach (['ga4_measurement_id', 'google_ads_id', 'google_ads_conversion_label', 'meta_pixel_ids', 'tracking_delivery'] as $field) {
            $setting->$field = $request->input($field) ?? '';
        }
        $setting->enhanced_conversions_enabled = $request->boolean('enhanced_conversions_enabled');
        $setting->save();

        Cache::forget('settings_data');
        Cache::forget('settings');

        session()->flash('success', 'Settings are updated.');
        return redirect()->route('admin.settings');
    }
}
