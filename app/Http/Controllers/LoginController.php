<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleProviderCallback()
    {
        $user = Socialite::driver('github')->user();

        $newUser = User::firstOrCreate(
            [
                'email' => $user->getEmail(),
                'provider_id' => $user->getId()
            ],
            [
                'name' => $user->getName(),
                'email_verified_at' => now()
            ]
        );

        Auth::login($newUser);

        return redirect(env('SPA_URL'));
    }
}
