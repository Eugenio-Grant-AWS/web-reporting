@extends('layouts.app')

@section('content')
    <section class="vh-100" style="background-color: #eee;">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-lg-12 col-xl-11">
                    <div class="text-black card" style="border-radius: 25px;">
                        <div class="card-body p-md-5">
                            <div class="row justify-content-center">
                                <div class="order-2 col-md-10 col-lg-6 col-xl-5 order-lg-1">

                                    <!-- Page Title -->
                                    <p class="mx-1 mt-4 mb-5 text-center h1 fw-bold mx-md-4">Update Profile</p>

                                    <!-- Update Profile Form -->
                                    <form method="POST" action="{{ route('profile.update') }}" class="mx-1 mx-md-4">
                                        @csrf
                                        @method('patch')

                                        <!-- Name Field -->
                                        <div class="flex-row mb-4 d-flex align-items-center">
                                            <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                            <div data-mdb-input-init class="mb-0 form-outline flex-fill">
                                                <label class="form-label" for="name">Your Name</label>
                                                <input type="text" name="name" class="form-control"
                                                    value="{{ old('name', $user->name) }}" required autofocus
                                                    autocomplete="name" />
                                                @error('name')
                                                    <p class="pt-1 text-xs text-danger"> {{ $message }} </p>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Email Field -->
                                        <div class="flex-row mb-4 d-flex align-items-center">
                                            <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                                            <div data-mdb-input-init class="mb-0 form-outline flex-fill">
                                                <label class="form-label" for="email">Your Email</label>
                                                <input type="email" name="email" class="form-control"
                                                    value="{{ old('email', $user->email) }}" required
                                                    autocomplete="username" />
                                                @error('email')
                                                    <p class="pt-1 text-xs text-danger"> {{ $message }} </p>
                                                @enderror
                                            </div>
                                        </div>

                                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                            <div>
                                                <p class="mt-2 text-sm text-gray-800">
                                                    {{ __('Your email address is unverified.') }}
                                                    <button form="send-verification"
                                                        class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                        {{ __('Click here to re-send the verification email.') }}
                                                    </button>
                                                </p>
                                            </div>
                                        @endif

                                        <!-- Submit Button -->
                                        <div class="mx-4 mb-3 d-flex justify-content-center mb-lg-4">
                                            <button type="submit" class="btn btn-primary btn-lg">Save</button>
                                        </div>
                                    </form>

                                    <!-- Update Password Form -->
                                    <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                                        @csrf
                                        @method('put')

                                        <!-- Current Password Field -->
                                        <div class="flex-row mb-4 d-flex align-items-center">
                                            <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                                            <div data-mdb-input-init class="mb-0 form-outline flex-fill">
                                                <label class="form-label" for="update_password_current_password">Current
                                                    Password</label>
                                                <input type="password" id="update_password_current_password"
                                                    name="current_password" class="form-control" />
                                                @error('current_password')
                                                    <p class="pt-1 text-xs text-danger"> {{ $message }} </p>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- New Password Field -->
                                        <div class="flex-row mb-4 d-flex align-items-center">
                                            <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                                            <div data-mdb-input-init class="mb-0 form-outline flex-fill">
                                                <label class="form-label" for="update_password_password">New
                                                    Password</label>
                                                <input type="password" id="update_password_password" name="password"
                                                    class="form-control" />
                                                @error('password')
                                                    <p class="pt-1 text-xs text-danger"> {{ $message }} </p>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Confirm Password Field -->
                                        <div class="flex-row mb-4 d-flex align-items-center">
                                            <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                                            <div data-mdb-input-init class="mb-0 form-outline flex-fill">
                                                <label class="form-label"
                                                    for="update_password_password_confirmation">Confirm Password</label>
                                                <input type="password" id="update_password_password_confirmation"
                                                    name="password_confirmation" class="form-control" />
                                                @error('password_confirmation')
                                                    <p class="pt-1 text-xs text-danger"> {{ $message }} </p>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="mx-4 mb-3 d-flex justify-content-center mb-lg-4">
                                            <button type="submit" class="btn btn-primary btn-lg">Save Password</button>
                                        </div>
                                    </form>

                                    <!-- Account Deletion Form -->
                                    <form method="POST" action="{{ route('profile.destroy') }}" class="mt-6 space-y-6">
                                        @csrf
                                        @method('delete')

                                        <!-- Password for Deletion -->
                                        <div class="flex-row mb-4 d-flex align-items-center">
                                            <i class="fas fa-trash fa-lg me-3 fa-fw"></i>
                                            <div data-mdb-input-init class="mb-0 form-outline flex-fill">
                                                <label class="form-label" for="password">Confirm Password</label>
                                                <input type="password" id="password" name="password" class="form-control"
                                                    placeholder="Enter your password" required />
                                                @error('password')
                                                    <p class="pt-1 text-xs text-danger"> {{ $message }} </p>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Submit Button for Deletion -->
                                        <div class="mx-4 mb-3 d-flex justify-content-center mb-lg-4">
                                            <button type="submit" class="btn btn-danger btn-lg">Delete Account</button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Image/Other Content Section (Optional) -->
                                <div class="order-1 col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-lg-2">
                                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-registration/draw1.webp"
                                        class="img-fluid" alt="Sample image">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
