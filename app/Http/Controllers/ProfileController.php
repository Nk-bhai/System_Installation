<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
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
                    'keyData' => [
                        'email' => $user->email,
                        'profile_logo' => $user->profile_logo ?? 'blank.png'
                    ]
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


    // public function update(Request $request)
    // {

    //     // Validate the request
    //     $request->validate([
    //         'avatar' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
    //     ], [
    //         'avatar.image' => 'The file must be an image.',
    //         'avatar.mimes' => 'The image must be a PNG, JPG, or JPEG file.',
    //         'avatar.max' => 'The image must not exceed 2MB.',
    //     ]);

    //     $ip_address = $request->ip();

    //     // if profile is to be remove
    //     if ($request->input('avatar_remove')) {
    //         $ip_address = $request->ip();

    //         try {
    //             $response = Http::post("http://192.168.12.79:8005/api/superadmin/remove_profile_logo/{$ip_address}");

    //             if ($response->successful()) {
    //                 session(['profile_logo' => 'blank.png']);
    //                 return redirect()->route('profile.show')->with('success', 'Avatar removed successfully!');
    //             } else {
    //                 return back()->withErrors(['avatar' => $response->json()['error'] ?? 'Failed to remove profile logo via API']);
    //             }
    //         } catch (\Exception $e) {
    //             return back()->withErrors(['avatar' => 'Error occurred while calling remove API: ' . $e->getMessage()]);
    //         }
    //     }

    //     // if profile to be set
    //     if ($request->hasFile('avatar')) {

    //         // Prepare the file for the API request
    //         $file = $request->file('avatar');
    //         $filePath = $file->getPathname();
    //         $fileName = $file->getClientOriginalName();


    //         try {
    //             // Send the file to the profile logo API
    //             $response = Http::attach(
    //                 'avatar',
    //                 file_get_contents($filePath),
    //                 $fileName
    //             )->post("http://192.168.12.79:8005/api/superadmin/profile_logo/{$ip_address}");


    //             // Check if the API request was successful
    //             if ($response->successful()) {
    //                 // Store the avatar locally (optional, if needed)
    //                 $path = $file->storeAs('avatars', $fileName, 'public');
    //                 Log::info('Avatar stored locally', ['path' => $path]);

    //                 return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    //             } else {
    //                 return back()->withErrors(['avatar' => $response->json()['error'] ?? 'Failed to update profile logo via API']);
    //             }
    //         } catch (\Exception $e) {
    //             return back()->withErrors(['avatar' => 'An error occurred while uploading the file: ' . $e->getMessage()]);
    //         }
    //     } else {
    //         return back()->with('info', 'No avatar uploaded.');
    //     }
    // }


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
                $file->storeAs('avatars', $fileName , 'public');
                $user->profile_logo = $fileName;
                $user->save();
                session(['profile_logo' => $fileName]);
                return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
            }

            return back()->with('info', 'No avatar uploaded.');
        }

        // For super admins (API-based logic)
        $ip_address = $request->ip();

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
                    Log::info('Avatar stored locally', ['path' => $path]);
                    session(['profile_logo' => $fileName]);
                    return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
                }
                return back()->withErrors(['avatar' => $response->json()['error'] ?? 'Failed to update profile logo via API']);
            } catch (\Exception $e) {
                return back()->withErrors(['avatar' => 'An error occurred while uploading the file: ' . $e->getMessage()]);
            }
        }

        return back()->with('info', 'No avatar uploaded.');
    }

}