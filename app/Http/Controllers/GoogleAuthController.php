<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Look for user by google_id or email
            $user = User::where('google_id', $googleUser->id)->first();
            
            if (!$user) {
                $user = User::where('email', $googleUser->email)->first();
                if ($user) {
                    // Update user's google_id if they already exist
                    $user->update(['google_id' => $googleUser->id]);
                } else {
                    // Create new user
                    $referredBy = null;
                    if (session()->has('referred_by_code')) {
                        $referrer = User::where('referral_code', session('referred_by_code'))->first();
                        $referredBy = $referrer ? $referrer->id : null;
                    }

                    $user = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'role' => User::ROLE_USER,
                        'wallet_balance' => 0.00,
                        'is_active' => true,
                        // Provide a dummy phone number if none is present to satisfy uniqueness and constraints
                        'phone' => 'G-' . substr($googleUser->id, 0, 15),
                        'referral_code' => strtoupper(Str::random(10)),
                        'referred_by_id' => $referredBy,
                    ]);
                    
                    Wallet::create([
                        'user_id' => $user->id,
                        'balance' => 0.00,
                    ]);
                }
            }

            Auth::login($user);
            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Authentication failed: ' . $e->getMessage());
        }
    }
}
