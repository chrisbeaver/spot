<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Socialite\Facades\Socialite;

class RedditAuthController extends Controller
{
    /**
     * Show the signup page with Reddit OAuth option.
     */
    public function showSignup(): Response
    {
        return Inertia::render('Auth/Signup');
    }

    /**
     * Redirect the user to Reddit's OAuth authorization page.
     */
    public function redirectToReddit(): RedirectResponse
    {
        return Socialite::driver('reddit')
            ->scopes(['identity'])
            ->redirect();
    }

    /**
     * Handle the callback from Reddit after authorization.
     */
    public function handleRedditCallback(): RedirectResponse
    {
        try {
            $redditUser = Socialite::driver('reddit')->user();

            // Log the response from Reddit
            Log::info('Reddit OAuth Response', [
                'id' => $redditUser->getId(),
                'nickname' => $redditUser->getNickname(),
                'name' => $redditUser->getName(),
                'email' => $redditUser->getEmail(),
                'avatar' => $redditUser->getAvatar(),
                'token' => $redditUser->token,
                'refresh_token' => $redditUser->refreshToken,
                'expires_in' => $redditUser->expiresIn,
            ]);

            // Find existing user by Reddit ID or create a new one
            $user = User::where('reddit_id', $redditUser->getId())->first();

            if ($user) {
                // Update existing user's tokens
                $user->update([
                    'reddit_token' => $redditUser->token,
                    'reddit_refresh_token' => $redditUser->refreshToken,
                ]);
            } else {
                // Create a new user
                $user = User::create([
                    'name' => $redditUser->getNickname() ?? $redditUser->getName() ?? 'Reddit User',
                    'email' => $redditUser->getEmail() ?? $redditUser->getId() . '@reddit.local',
                    'reddit_id' => $redditUser->getId(),
                    'reddit_username' => $redditUser->getNickname(),
                    'reddit_token' => $redditUser->token,
                    'reddit_refresh_token' => $redditUser->refreshToken,
                    'password' => null,
                ]);

                Log::info('Created new user from Reddit OAuth', [
                    'user_id' => $user->id,
                    'reddit_username' => $user->reddit_username,
                ]);
            }

            // Log the user in
            Auth::login($user, true);

            return redirect()->intended(route('dashboard'));
        } catch (\Exception $e) {
            Log::error('Reddit OAuth Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('signup')
                ->withErrors(['reddit' => 'Failed to authenticate with Reddit. Please try again.']);
        }
    }
}
