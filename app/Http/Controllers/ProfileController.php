<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Log;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        if (session('login_email')) {
            $user = UserModel::where('email', session('login_email'))->first();
            if ($user) {
                session(['profile_logo' => $user->profile_logo ?? 'blank.png']);
                return view('profile', [
                    'email' => $user->email,
                    'password' => $user->password,
                    'profile_logo' => $user->profile_logo ?? 'blank.png'
                ]);
            }
        }
        $ip_address = $request->ip();
        $response = Http::get("http://192.168.12.79:8005/api/superadmin/get/{$ip_address}");
        $keyData = $response->json();
        session(['superadmin_email' => $keyData['email']]);
        session(['profile_logo' => $keyData['profile_logo']]);

        return view('profile', [
            'keyData' => $keyData,

        ]);
    }

    public function PasswordUpdate(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'avatar' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        //     'current_password' => 'required',
        //     'new_password' => 'required|min:8|confirmed',
        // ]);

        // if ($validator->fails()) {
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }

        dd("reach");
        $user = UserModel::where('email', session('login_email'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            dd("hello");
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect'])->withInput();
        }
        dd($request->input('password'));

        // Update password
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return redirect()->back()->with('success', 'Password updated successfully');
    }




    public function update(Request $request)
    {

        // Validate the request
        $request->validate([
            'avatar' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'avatar_remove' => 'nullable|in:1'
        ], [
            'avatar.image' => 'The file must be an image.',
            'avatar.mimes' => 'The image must be a PNG, JPG, or JPEG file.',
            'avatar.max' => 'The image must not exceed 2MB.',
        ]);

        // For non-super-admin users (session('login_email') is set)
        if (session('login_email')) {
            $user = UserModel::where('email', session('login_email'))->first();
            if (!$user) {
                return back()->withErrors(['avatar' => 'User not found.']);
            }
            if ($request->filled('password')) {
                $password = trim($request->input('password'));
                if (!empty($password)) {
                    $user->password = Hash::make($password);
                    $user->save();
                    session()->flash('success', 'Password updated successfully!');
                }
            }

            // Handle avatar removal
                if ($request->input('avatar_remove') == 1) {
                    if ($user->profile_logo && $user->profile_logo !== 'blank.png') {
                        Storage::delete('public' . $user->profile_logo);
                        $user->profile_logo = 'blank.png';
                        $user->save();
                    }
                    session(['profile_logo' => 'blank.png']);
                    return redirect()->route('profile.show')->with('success', 'Avatar removed successfully!');
                }

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists and not default
                if ($user->profile_logo && $user->profile_logo !== 'blank.png') {
                    Storage::delete('public/avatars/' . $user->profile_logo);
                }
                // Store new avatar
                $file = $request->file('avatar');
                $fileName = $file->getClientOriginalName();
                $file->storeAs('avatars', $fileName, 'public');
                $user->profile_logo = $fileName;
                $user->save();
                session(['profile_logo' => $fileName]);
                return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
            }

            return back()->with('info', 'No avatar uploaded.');
        }

        // For super admins (API-based logic)
        $ip_address = $request->ip();
        if (session('superadmin_email')) {

            if ($request->filled('password')) {
                $password = trim($request->input('password'));
                if (!empty($password)) {
                    DB::table('users')
                        ->where('email', session('superadmin_email'))
                        ->update(['password' => Hash::make($password)]);
                }
            }
        }
        // Handle avatar removal
        if ($request->input('avatar_remove') == 1) {
            try {
                $response = Http::post("http://192.168.12.79:8005/api/superadmin/remove_profile_logo/{$ip_address}");
                if ($response->successful()) {
                    session(['profile_logo' => 'blank.png']);
                    return redirect()->route('profile.show')->with('success', 'Avatar removed successfully!');
                }
                return back()->withErrors(['avatar' => $response->json()['error'] ?? 'Failed to remove profile logo via API']);
            } catch (\Exception $e) {
                return back()->withErrors(['avatar' => 'Error occurred while calling remove API: ' . $e->getMessage()]);
            }
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            // dd($file);
            $filePath = $file->getPathname();
            $fileName = $file->getClientOriginalName();

            try {
                $response = Http::attach(
                    'avatar',
                    file_get_contents($filePath),
                    $fileName
                )->post("http://192.168.12.79:8005/api/superadmin/profile_logo/{$ip_address}");

                if ($response->successful()) {
                    $path = $file->storeAs('public/avatars', $fileName);
                    session(['profile_logo' => $fileName]);
                    return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
                }
                return back()->withErrors(['avatar' => $response->json()['error'] ?? 'Failed to update profile logo via API']);
            } catch (\Exception $e) {
                return back()->withErrors(['avatar' => 'An error occurred while uploading the file: ' . $e->getMessage()]);
            }
        }

        if ($request->filled('password') && !$request->hasFile('avatar') && $request->input('avatar_remove') != 1) {
            return redirect()->route('profile.show')->with('success', 'Password updated successfully!');
        }

        // If nothing was done
        return back()->with('info', 'No changes made.');
    }


    public function SiteControlPage()
    {

        return view('site_control');
    }

    public function SiteControl(Request $request)
    {
        $request->validate([
            'Favicon' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'sidebar_logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'Favicon_remove' => 'nullable|in:0,1',
            'sidebar_logo_remove' => 'nullable|in:0,1',
            'copyright' => 'nullable',
        ]);

        $ip_address = $request->ip();

        // Handle Favicon Upload
        if ($request->hasFile('Favicon')) {
            $favicon = $request->file('Favicon');
            $faviconPath = $favicon->getPathname();
            $faviconName = $favicon->getClientOriginalName();

            try {
                $faviconResponse = Http::attach(
                    'Favicon',
                    file_get_contents($faviconPath),
                    $faviconName
                )->post("http://192.168.12.79:8005/api/superadmin/favicon/{$ip_address}");

                if ($faviconResponse->successful()) {
                    // dd("hello");
                    $favicon->storeAs('favicons', $faviconName, 'public');
                    session(['favicon' => $faviconName]);
                } else {
                    $error = $faviconResponse->json()['error'] ?? 'Favicon update failed';
                    return back()->withErrors(['favicon' => $error]);
                }
            } catch (\Exception $e) {
                return back()->withErrors(['favicon' => 'Favicon upload error: ' . $e->getMessage()]);
            }
        }

        // Handle Sidebar Logo Upload
        if ($request->hasFile('sidebar_logo')) {
            $sidebarLogo = $request->file('sidebar_logo');
            $sidebarLogoPath = $sidebarLogo->getPathname();
            $sidebarLogoName = $sidebarLogo->getClientOriginalName();

            try {
                $sidebarResponse = Http::attach(
                    'sidebar_logo',
                    file_get_contents($sidebarLogoPath),
                    $sidebarLogoName
                )->post("http://192.168.12.79:8005/api/superadmin/sidebar_logo/{$ip_address}");

                if ($sidebarResponse->successful()) {
                    $sidebarLogo->storeAs('sidebar_logos', $sidebarLogoName, 'public');
                    session(['sidebar_logo' => $sidebarLogoName]);
                } else {
                    $error = $sidebarResponse->json()['error'] ?? 'Sidebar logo update failed';
                    return back()->withErrors(['sidebar_logo' => $error]);
                }
            } catch (\Exception $e) {
                return back()->withErrors(['sidebar_logo' => 'Sidebar logo upload error: ' . $e->getMessage()]);
            }
        }

        // Handle Favicon Removal
        if ($request->input('Favicon_remove') == 1) {
            try {
                $response = Http::post("http://192.168.12.79:8005/api/superadmin/remove_favicon/{$ip_address}");
                if ($response->successful()) {
                    // dd("hello");
                    session(['favicon' => null]);
                } else {
                    $error = $response->json()['error'] ?? 'Failed to remove favicon';
                    return back()->withErrors(['favicon' => $error]);
                }
            } catch (\Exception $e) {
                return back()->withErrors(['favicon' => 'Favicon remove error: ' . $e->getMessage()]);
            }
        }

        // Handle Sidebar Logo Removal
        if ($request->input('sidebar_logo_remove') == 1) {
            try {
                $response = Http::post("http://192.168.12.79:8005/api/superadmin/remove_sidebar_logo/{$ip_address}");
                if ($response->successful()) {

                    session(['sidebar_logo' => null]);
                } else {
                    $error = $response->json()['error'] ?? 'Failed to remove sidebar logo';
                    return back()->withErrors(['sidebar_logo' => $error]);
                }
            } catch (\Exception $e) {
                return back()->withErrors(['sidebar_logo' => 'Sidebar logo remove error: ' . $e->getMessage()]);
            }
        }

        if ($request->filled('copyright')) {
            $copyright = trim($request->input('copyright'));
            if (!empty($copyright)) {
                try {
                    $response = Http::retry(3, 200)->post("http://192.168.12.79:8005/api/superadmin/save_copyright/{$ip_address}", [
                        'copyright' => $copyright,
                    ]);

                    if (!$response->successful()) {
                        $error = $response->json()['error'] ?? 'Failed to save copyright';
                        return back()->withErrors(['copyright' => $error]);
                    }
                } catch (\Exception $e) {
                    return back()->withErrors(['copyright' => 'Copyright save error: ' . $e->getMessage()]);
                }
            }
        }


        return redirect()->route('SiteControlPage')->with('success', 'Profile updated successfully!');
    }

}