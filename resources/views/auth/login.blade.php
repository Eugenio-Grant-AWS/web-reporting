@extends('layouts.app')

@section('content')
    <section class="vh-100" style="background-color: #eee;">
        <div class="container h-100">
            <div class="row d-flex justify-content-center pt-5 align-items-center h-100">
                <div class="col-lg-12 col-xl-11">
                    <div class="text-black card" style="border-radius: 25px;">
                        <div class="card-body p-md-5">
                            <div class="row justify-content-center">
                                <div class="order-2 col-md-10 col-lg-6 col-xl-5 order-lg-1">

                                    <p class="mx-1 mt-4 mb-5 text-center h1 fw-bold mx-md-4">Sign up</p>

                                    <form method="POST" action="{{ route('login') }}" class="mx-1 mx-md-4">
                                        @csrf

                                        <!-- Email input field -->
                                        <div class="flex-row mb-4 d-flex align-items-center">
                                            <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                                            <div data-mdb-input-init class="mb-0 form-outline flex-fill">
                                                <label class="form-label" for="form3Example3c">Your Email</label>
                                                <input type="email" name="email" class="form-control" />
                                                @error('email')
                                                    <p class="pt-1 text-xs text-danger"> {{ $message }} </p>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Password input field -->
                                        <div class="flex-row mb-4 d-flex align-items-center">
                                            <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                                            <div data-mdb-input-init class="mb-0 form-outline flex-fill">
                                                <label class="form-label" for="form3Example4c">Password</label>
                                                <input type="password" id="password" name="password"
                                                    class="form-control" />
                                            </div>
                                        </div>

                                        <!-- Forgot Password Link -->
                                        @if (Route::has('password.request'))
                                            <div class="text-center">
                                                <a class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                    href="{{ route('password.request') }}">
                                                    {{ __('Forgot your password?') }}
                                                </a>
                                            </div>
                                        @endif

                                        <!-- Remember Me checkbox (optional) -->
                                        <div class="mb-5 form-check d-flex justify-content-center pt-5">
                                            <input class="form-check-input me-2" type="checkbox" value=""
                                                id="form2Example3c" />
                                            <label class="form-check-label" for="form2Example3">
                                                I agree to all statements in <a href="#!">Terms of Service</a>
                                            </label>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="mx-4 mb-3 d-flex justify-content-center pt-5 mb-lg-4">
                                            <button type="submit" data-mdb-button-init data-mdb-ripple-init
                                                class="export-btn btn-lg">Log In</button>
                                        </div>

                                    </form>

                                </div>
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
