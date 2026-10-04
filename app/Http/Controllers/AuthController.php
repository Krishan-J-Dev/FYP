<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // (Show Registration View)
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // (Process Registration)
    public function register(Request $request)
    {
        // A. உள்ளீடுகளைச் சரிபார்த்தல் (Form Input Validation)
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15',
            'user_type' => 'required|in:driver,mechanic,tow_service',
            'password' => 'required|string|min:8|confirmed',
            'g-recaptcha-response' => 'required',
        ]);

        //  (reCAPTCHA Verification)
        $recaptchaResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('NOCAPTCHA_SECRET'),
            'response' => $request->input('g-recaptcha-response'),
        ]);

        if (!$recaptchaResponse->json('success')) {
            return back()->withErrors(['g-recaptcha-response' => 'Google reCAPTCHA சரிபார்ப்பு தோல்வியடைந்தது. திரும்ப முயற்சிக்கவும்.'])->withInput();
        }

        //  (Create User)
        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'user_type' => $request->user_type,
            'password' => Hash::make($request->password),
        ]);

        //  (Auto Login & Redirect)
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'கணக்கு வெற்றிகரமாக உருவாக்கப்பட்டது!');
    }
}