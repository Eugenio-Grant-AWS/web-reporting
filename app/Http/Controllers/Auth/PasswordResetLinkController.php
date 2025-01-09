<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Mail\OTPPasswordMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('pages.auth.forget_password');
    }

    /**
     * Display OTP verification page.
     */
    public function otp()
    {
        // Get the OTP expiry time from the session
        $otpExpiresAt = Session::get('otp_expires_at');
        $otpExpired = $this->isOtpExpired($otpExpiresAt);

        return view('pages.auth.otp', compact('otpExpired'));
    }

    /**
     * Display the new password form.
     */
    public function newpassword()
    {
        return view('pages.auth.new_password');
    }

    /**
     * Send OTP to the user's email.
     */
    public function sendOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $email = $request->input('email');

        $otp = rand(10000, 99999);

        Session::put([
            'otp' => $otp,
            'otp_email' => $email,
            'otp_expires_at' => now()->addMinute(),
        ]);

        $mailData = [
            'otp' => $otp,
            'email' => $email,
        ];

        Mail::to($email)->send(new OTPPasswordMail($mailData));
        session()->flash('otp_sent', 'OTP has been sent to your email!');

        return redirect()->route('password.request.otp');
    }

    /**
     * Verify the OTP entered by the user.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:5',
        ]);

        $storedOtp = Session::get('otp');
        $otpExpiresAt = Session::get('otp_expires_at');
        $enteredOtp = $request->input('otp');

        if ($this->isOtpExpired($otpExpiresAt)) {
            return $this->redirectWithOtpError('OTP has expired. Please request a new one.');
        }

        if ($storedOtp && $storedOtp == $enteredOtp) {
            return $this->redirectToNewPassword();
        }

        return $this->redirectWithOtpError('Invalid OTP. Please try again.');
    }

    /**
     * Check if the OTP has expired.
     */
    private function isOtpExpired($otpExpiresAt)
    {
        return !$otpExpiresAt || now()->greaterThan($otpExpiresAt);
    }

    /**
     * Redirect with OTP error message.
     */
    private function redirectWithOtpError($message)
    {
        Session::forget('otp');
        Session::forget('otp_expires_at');

        return redirect()->route('password.request.otp')->withErrors(['otp' => $message]);
    }

    /**
     * Redirect to the "new-password" route after successful OTP validation.
     */
    private function redirectToNewPassword()
    {
        Session::forget('otp');
        Session::forget('otp_expires_at');

        return redirect()->route('new-password');
    }

    /**
     * Store the new password after validation.
     */

    public function store(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required|min:8|same:password', // Ensure the confirmation matches the password
        ], [
            'password.required' => 'The password field is required.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.min' => 'The password must be at least 8 characters.',
            'password_confirmation.required' => 'The confirmation password is required.',
            'password_confirmation.same' => 'The password confirmation does not match the new password.'
        ]);

        $email = Session::get('otp_email');

        if (!$email) {
            return redirect()->route('password.request.otp')->withErrors(['otp' => 'Session expired. Please request an OTP again.']);
        }

        $user = User::where('email', $email)->firstOrFail();

        $user->update(['password' => Hash::make($request->password)]);

        Session::forget(['otp', 'otp_email']);

        return redirect()->route('login')->with('status', 'Password has been reset successfully!');
    }


    /**
     * Resend OTP to the user.
     */
    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $email = $request->input('email');
        $otp = rand(10000, 99999);

        Session::put([
            'otp' => $otp,
            'otp_email' => $email,
            'otp_expires_at' => now()->addMinute(),
        ]);

        $mailData = [
            'otp' => $otp,
            'email' => $email,
        ];
        Mail::to($email)->send(new OTPPasswordMail($mailData));

        return response()->json(['success' => true, 'message' => 'OTP has been resent!']);
    }
}