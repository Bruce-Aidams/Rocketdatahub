<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to Google's OAuth page.
     */
    public function redirectToGoogle()
    {
        // If the user is already logged in, send them straight to the dashboard.
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google.
     * The InvalidStateException is the most common OAuth failure — it happens when
     * the CSRF state token stored in the session does not match the one Google sends
     * back (e.g. after a session reset or a browser back-button navigation).
     * We retry using stateless() which skips the state check entirely.
     */
    public function handleGoogleCallback()
    {
        // If already authenticated just go to dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        try {
            $googleUser = $this->fetchGoogleUser();
        } catch (\Exception $e) {
            Log::error('Google OAuth callback failed: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'trace'     => $e->getTraceAsString(),
            ]);
            return redirect()->route('login')
                ->with('error', 'Google sign-in failed. Please try again.');
        }

        try {
            $user = $this->findOrCreateUser($googleUser);
            Auth::login($user, true); // remember=true for convenience
            $intendedUrl = redirect()->intended(route('dashboard'))->getTargetUrl();
            if (str_contains($intendedUrl, '/poll') || str_contains($intendedUrl, '/api/') || str_contains($intendedUrl, '/webhooks/')) {
                return redirect()->route('dashboard');
            }
            return redirect($intendedUrl);
        } catch (\Exception $e) {
            Log::error('Google OAuth user creation/login failed: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'Unable to complete sign-in. Please try again.');
        }
    }

    /**
     * Fetch the Google user, falling back to stateless if InvalidStateException.
     */
    private function fetchGoogleUser()
    {
        try {
            return Socialite::driver('google')->user();
        } catch (InvalidStateException $e) {
            // State mismatch — retry without state validation
            Log::warning('Google OAuth InvalidStateException — retrying stateless.');
            return Socialite::driver('google')->stateless()->user();
        }
    }

    /**
     * Find an existing user by google_id/email, or create a brand-new account.
     */
    private function findOrCreateUser($googleUser): User
    {
        // 1. Exact google_id match
        $user = User::where('google_id', $googleUser->id)->first();

        if ($user) {
            return $user;
        }

        // 2. Existing account with the same email — link the Google ID
        $user = User::where('email', $googleUser->email)->first();
        if ($user) {
            $user->update(['google_id' => $googleUser->id]);
            return $user;
        }

        // 3. Brand-new registration via Google
        $referredBy = null;
        if (session()->has('referred_by_code')) {
            $referrer = User::where('referral_code', session('referred_by_code'))->first();
            $referredBy = $referrer?->id;
        }

        $user = User::create([
            'name'          => $googleUser->name,
            'email'         => $googleUser->email,
            'google_id'     => $googleUser->id,
            'role'          => User::ROLE_USER,
            'wallet_balance' => 0.00,
            'is_active'     => true,
            // Placeholder phone — unique but identifiable as a Google account
            'phone'         => 'G-' . substr($googleUser->id, 0, 15),
            'referral_code' => strtoupper(Str::random(10)),
            'referred_by_id' => $referredBy,
        ]);

        Wallet::create([
            'user_id' => $user->id,
            'balance' => 0.00,
        ]);

        return $user;
    }
}

