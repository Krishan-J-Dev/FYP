<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Show Registration Form
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Process Registration
    public function register(Request $request)
    {
        // 1. Form Input Validations with English Messages
        $request->validate([
            'full_name' => 'required|string|min:3|max:255',
            'email'     => 'required|string|email|max:255|unique:users,email',
            'phone'     => ['required', 'regex:/^0[0-9]{9}$/'],
            'user_type' => 'required|in:driver,mechanic,tow_service',
            'password'  => 'required|string|min:8|confirmed',
            'g-recaptcha-response' => 'required',
        ], [
            'full_name.required' => 'Full name is required.',
            'full_name.min'      => 'Full name must be at least 3 characters.',
            'email.required'     => 'Email address is required.',
            'email.email'        => 'Please enter a valid email address.',
            'email.unique'       => 'This email address is already registered.',
            'phone.required'     => 'Phone number is required.',
            'phone.regex'        => 'Phone number must be exactly 10 digits starting with 0 (e.g., 0771234567).',
            'user_type.required' => 'Please select a user type.',
            'password.required'  => 'Password is required.',
            'password.min'       => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification.',
        ]);

        // 2. Google reCAPTCHA Verification
        $recaptchaResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => env('NOCAPTCHA_SECRET'),
            'response' => $request->input('g-recaptcha-response'),
        ]);

        if (!$recaptchaResponse->json('success')) {
            return back()->withErrors(['g-recaptcha-response' => 'Google reCAPTCHA verification failed. Please try again.'])->withInput();
        }

        // 3. User Creation
        $user = User::create([
            'full_name' => $request->full_name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'user_type' => $request->user_type,
            'password'  => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Account created successfully!');
    }

    // Show Login Form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Process Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Email address is required.',
            'email.email'       => 'Please enter a valid email address.',
            'password.required' => 'Password is required.',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard')->with('success', 'Logged in successfully!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Logout Process
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully!');
    }

    // Show Forgot Password Form
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    // Process Password Reset Link
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email address is required.',
            'email.exists'   => 'No account found with this email address.',
        ]);

        return redirect()->route('password.reset', ['email' => $request->email]);
    }

    // Show Reset Password Form
    public function showResetPasswordForm(Request $request)
    {
        return view('auth.reset-password', ['email' => $request->email]);
    }

    // Process Reset Password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.required'  => 'New password is required.',
            'password.min'       => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')->with('success', 'Password reset successfully! Please login with your new password.');
    }
}