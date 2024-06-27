<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class OTPVerificationController extends Controller
{
    public function showVerifyForm()
    {
        return view('auth.verify-otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);
        $email = session()->get('email');

        $user = User::where('email',$email)->first();
        // Check if OTP matches and has not expired
        if (Hash::check($request->otp, $user->otp) && now()->lt($user->otp_expires_at)) {
            // Clear OTP and redirect to intended page
            $user->otp = null;
            $user->otp_expires_at = null;
            $user->save();
            // Retrieve the password from session
            $password = session()->get('password');

            // Get email from request
            $email = session()->get('email');

            // Combine email and password into credentials array
            $credentials = [
                'email' => $email,
                'password' => $password,
            ];

            if (Auth::attempt($credentials)) {

                // Redirect to default authenticated page
                return redirect()->route('dashboard');

            }

        }

        throw ValidationException::withMessages([
            'otp' => __('Invalid OTP'),
        ]);
    }
}
