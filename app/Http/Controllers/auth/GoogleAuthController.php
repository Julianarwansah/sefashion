<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    /**
     * Redirect user to Google authentication page
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google callback after authentication
     */
    public function handleGoogleCallback()
    {
        try {
            // Get user info from Google
            $googleUser = Socialite::driver('google')->user();

            // Check if user already exists with this google_id
            $customer = Customer::where('google_id', $googleUser->getId())->first();

            if ($customer) {
                // User exists with Google ID - login directly
                Auth::guard('customer')->login($customer, true);
                return redirect()->intended('/')->with('success', 'Welcome back, ' . $customer->nama . '!');
            }

            // Check if user exists with this email (registered manually)
            $customer = Customer::where('email', $googleUser->getEmail())->first();

            if ($customer) {
                // Link Google account to existing customer account
                $customer->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);

                Auth::guard('customer')->login($customer, true);
                return redirect()->intended('/')->with('success', 'Google account linked successfully!');
            }

            // Create new customer account with Google
            $customer = Customer::create([
                'nama' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => null, // No password for Google users
            ]);

            Auth::guard('customer')->login($customer, true);
            return redirect()->intended('/')->with('success', 'Account created successfully! Welcome to SeFashion!');

        } catch (Exception $e) {
            \Log::error('Google OAuth Error: ' . $e->getMessage());

            return redirect()->route('login')
                ->with('error', 'Failed to login with Google. Please try again or use email/password login.');
        }
    }
}
