<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $user = User::where('email',$request->input('email'))->first();

        // If the user doesn't exist or password doesn't match, redirect back
        if (!$user || !Hash::check($request->password, $user->password)) {
            return redirect()->back()->withErrors(['email' => 'Invalid credentials']);
        }

        // Check if the user has the 'admin' role
        if ($user->hasRole('admin')) {
            // Generate OTP and store it hashed
            $otp = rand(100000, 999999); // Generate a random 6-digit OTP
            $hashedOtp = Hash::make($otp); // Hash the OTP

            // Store OTP and its expiration time
            $user->otp = $hashedOtp;
            $user->otp_expires_at = now()->addMinutes(10); // OTP expiration time (10 minutes)
            $user->save();

            // Store email and password in session (optional)
            session()->put('email', $request->email);
            session()->put('password', $request->password);

            // Send OTP via notification (email or SMS)
            $user->notify(new \App\Notifications\SendOTPNotification($otp));

            // Redirect to OTP verification page
            return redirect()->route('otp.verify');
        }
            else{
            if (Auth::attempt($credentials)) {

                // Redirect to default authenticated page
                return redirect()->route('dashboard');

            }
        }


        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }
}
