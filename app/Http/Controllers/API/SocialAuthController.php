<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }


    public function handleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            // dd($googleUser->token);
            $user = User::updateOrCreate([
                'google_id' => $googleUser->id,
                // 'email' => $googleUser->email
            ], [
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'google_token' => $googleUser->token,
                'google_refresh_token' => $googleUser->refreshToken,
                //** TODO: add avater */
            ]);

            Auth::login($user);

            return redirect('/')->with('success', "Logged in with google");
        } catch (\Exception $e) {
            dd($e);
        }

        return redirect('/')->with('success', "You are logged in");
    }
}