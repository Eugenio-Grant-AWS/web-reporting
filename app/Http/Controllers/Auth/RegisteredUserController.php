<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use App\Mail\EmailVerificationMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('pages.auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        try {

            $request->validate(
                [
                    'firstname' => ['required', 'string', 'max:255'],
                    'lastname' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                    'password' => ['required', 'string', 'min:8'],
                    'password_confirmation' => ['required', 'same:password'],
                    'terms' => ['accepted'],
                ],
                [
                    'firstname.required' => 'The first name field is required.',
                    'lastname.required' => 'The last name field is required.',
                    'password.min' => 'Password must be 8 characters long.',
                    'password_confirmation.same' => 'The password confirmation does not match.',
                    'terms.accepted' => 'You must agree to the terms and conditions.',
                ]
            );


            $user = User::create([
                'firstname' => $request->input('firstname'),
                'lastname' => $request->input('lastname'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            Mail::to($user)->send(new EmailVerificationMail($user));


            // Auth::login($user);


            return redirect()->route('reach-exposure-probability-with-mean');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    /**
     * Resend the email verification link to the user.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function resendVerificationLink(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard')
                ->with('status', 'Your email is already verified.');
        }

        Mail::to($user)->send(new EmailVerificationMail($user));

        return back()->with('status', 'verification-link-sent');
    }
}