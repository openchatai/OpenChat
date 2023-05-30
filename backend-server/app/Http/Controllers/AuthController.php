<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function marketingLogin()
    {
        // check if the user is already logged in
        if (auth()->check()) {
            return redirect()->route('index');
        }

        return view('marketing.signin');
    }

    public function marketingRegister()
    {
        // check if the user is already logged in
        if (auth()->check()) {
            return redirect()->route('index');
        }

        return view('marketing.signup');
    }

    public function register(Request $request)
    {
        // Validate the request...
        $validatedData = $request->validate(['name' => 'required|max:55', 'email' => 'email|required', 'password' => 'required']);

        // Create user
        $validatedData['password'] = bcrypt($request->password);
        $user = User::create($validatedData);

        // Login the user
        Auth::login($user);

        // Redirect to home
        return redirect()->route('onboarding.welcome');
    }

    public function login(Request $request)
    {
        // Validate the request...
        $validatedData = $request->validate(['email' => 'email|required', 'password' => 'required']);

        // Login the user
        if (Auth::attempt($validatedData)) {
            return redirect()->route('index');
        }

        // Redirect to log in
        return redirect()->route('marketing.login')->with('error', 'Invalid credentials');
    }


    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();


        // Check if the user already exists in the database
        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            $this->initiateUserAccount($googleUser->getName(), $googleUser->getEmail(), null, 'google', $googleUser->getAvatar());
        } else {
            auth()->login($user);
        }
        // Make the user data is updated as it is from Google
        $user->name = $googleUser->getName();
        $user->avatar = $googleUser->getAvatar();
        $user->save();

        return redirect()->route('index');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('marketing');
    }
}
