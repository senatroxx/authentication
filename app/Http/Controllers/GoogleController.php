<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Socialite;
use App\User;
use Hash;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        if (Auth::check()) {
            return redirect('/');
        }

        $oauthUser = Socialite::driver('google')->user();
        $user = User::where('google_id', $oauthUser->id)->first();
        if ($user) {
            Auth::loginUsingId($user->id);
            return redirect('/home');
        }else{
            session(['oauth' => $oauthUser]);

            return redirect()->route('google.register');
        }
    }

    public function register()
    {
        return view('auth.registerGoogle');
    }

    public function addUser(Request $request)
    {
        $this->validate($request, [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $oauth = session('oauth');

        $newUser = User::create([
            'name' => $oauth->name,
            'email' => $oauth->email,
            'google_id' => $oauth->id,
            'password' => Hash::make($request['password']),
        ]);
        
        $newUser->sendEmailVerificationNotification();

        Auth::login($newUser);

        session()->forget('oauth');

        return redirect('/home');
    }
}
