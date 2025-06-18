<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Log;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $ip_address = $request->ip();
        $response = Http::get("http://192.168.1.14:8005/api/superadmin/get/{$ip_address}");
        $keyData = $response->json();
        session(['superadmin_email' => $keyData['email']]);
        session(['profile_logo' => $keyData['profile_logo']]);

        return view('profile', [
            'keyData' => $keyData,

        ]);
    }


    public function update(Request $request)
    {

        // Validate the request
        $validated = $request->validate([
            'avatar' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ], [
            'avatar.image' => 'The file must be an image.',
            'avatar.mimes' => 'The image must be a PNG, JPG, or JPEG file.',
            'avatar.max' => 'The image must not exceed 2MB.',
        ]);

        $ip_address = $request->ip();

        // if profile is to be remove
        if ($request->input('avatar_remove')) {
            $ip_address = $request->ip();

            try {
                $response = Http::post("http://192.168.1.14:8005/api/superadmin/remove_profile_logo/{$ip_address}");

                if ($response->successful()) {
                    session(['profile_logo' => 'blank.png']);
                    return redirect()->route('profile.show')->with('success', 'Avatar removed successfully!');
                } else {
                    return back()->withErrors(['avatar' => $response->json()['error'] ?? 'Failed to remove profile logo via API']);
                }
            } catch (\Exception $e) {
                return back()->withErrors(['avatar' => 'Error occurred while calling remove API: ' . $e->getMessage()]);
            }
        }

        // if profile to be set
        if ($request->hasFile('avatar')) {

            // Prepare the file for the API request
            $file = $request->file('avatar');
            $filePath = $file->getPathname();
            $fileName = $file->getClientOriginalName();


            try {
                // Send the file to the profile logo API
                $response = Http::attach(
                    'avatar',
                    file_get_contents($filePath),
                    $fileName
                )->post("http://192.168.1.14:8005/api/superadmin/profile_logo/{$ip_address}");


                // Check if the API request was successful
                if ($response->successful()) {
                    // Store the avatar locally (optional, if needed)
                    $path = $file->storeAs('avatars', $fileName, 'public');
                    Log::info('Avatar stored locally', ['path' => $path]);

                    return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
                } else {
                    return back()->withErrors(['avatar' => $response->json()['error'] ?? 'Failed to update profile logo via API']);
                }
            } catch (\Exception $e) {
                return back()->withErrors(['avatar' => 'An error occurred while uploading the file: ' . $e->getMessage()]);
            }
        } else {
            return back()->with('info', 'No avatar uploaded.');
        }
    }


}
