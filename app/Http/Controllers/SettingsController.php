<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage; 

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('settings.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('settings.index')->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!Hash::check($value, Auth::user()->password)) {
                    $fail('Kata sandi saat ini salah.');
                }
            }],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('settings.index')->with('success', 'Kata sandi berhasil diubah!');
    }

    public function updateTheme(Request $request)
    {
        $request->validate([
            'theme' => ['required', 'in:light,dark,system'],
        ]);

        $user = Auth::user();
        $user->theme = $request->theme;
        $user->save();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'theme' => $user->theme]);
        }

        return redirect()->back()->with('success', 'Tema berhasil disimpan!');
    }

    public function updateProfileImage(Request $request)
    {
        $request->validate([
            'profile_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
        ]);

        $user = Auth::user();

        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $path = $request->file('profile_image')->store('profile_images', 'public');
        $user->profile_image = $path;
        $user->save();

        return redirect()->route('settings.index')->with('success', 'Foto profil berhasil diunggah!');
    }
}