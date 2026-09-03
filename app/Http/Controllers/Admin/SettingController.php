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
        if(Auth::user()->id != 1)
        {
            session()->flash('fail', 'Access denied!');
            return back();
        }

        $setting = Setting::where(['id' => '1'])->first();

        return view('backend.settings', ['setting' => $setting]);
    }

    public function update(Request $request, Setting $setting)
    {
        $request->validate([
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
        $setting->save();

        Cache::forget('settings_data');

        session()->flash('success', 'Settings are updated.');
        return redirect()->route('admin.settings');
    }
}
