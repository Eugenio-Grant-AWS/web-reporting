@extends('layouts.app')
@section('title', 'Pages')
@section('content')
    <x-auth.authorization-background>
        <div class="m-auto col-lg-5 col-md-7">
            <div class="forget-password-form">
                <form action="{{ route('login') }}" method="POST" class="gap-4 m-auto ">
                    @csrf
                    <h3 class="mb-4">Sign In</h3>
                    <div class="mb-4 input-wrap">
                        <div class="input-group ">
                            <i class="fas fa-user"></i>
                            <input type="email" placeholder="Email" name="email" :value="old('email')"
                                value="{{ old('email') }}" id="email" oninput="hideError('email')" autofocus>
                        </div>
                        @error('email')
                            <small class="gap-1 text-danger d-flex align-items-baseline" id="error-email"><i
                                    class="fas fa-exclamation-circle"></i>{{ $message }}</small>
                        @enderror
                    </div>



                    <div class="mb-4 input-wrap">
                        <div class="input-group ">
                            <i class="fas fa-lock"></i>
                            <input type="password" placeholder="Password" name="password" value="{{ old('password') }}"
                                autofocus id="password" oninput="hideError('password')">

                            <i class="fas fa-eye" id="togglePassword" onclick="togglePassword()"></i>
                        </div>

                        @error('password')
                            <small class="gap-1 text-danger d-flex align-items-baseline" id="error-password"><i
                                    class="fas fa-exclamation-circle"></i>{{ $message }}</small>
                        @enderror
                    </div>




                    @if (session('error'))
                        <small class="gap-1 mb-4 text-danger d-flex align-items-baseline justify-content-center"><i
                                class="fas fa-exclamation-circle"></i>{{ session('error') }}</small>
                    @endif

                    <ul class="mb-4 d-flex align-items-center justify-content-between">
                        <li class="gap-2 d-flex align-items-center"><input type="checkbox" checked>
                            <p>Remember password</p>
                        </li>
                        <li><a href="{{ route('password.request') }}">Forget password</a></li>
                    </ul>
                    <button class="mb-4 theme-btn" type="submit" data-bs-toggle="modal"
                        data-bs-target="#successpassword">Sign
                        In</button>
                    <small class="d-block">Don’t have an account? <a href="{{ route('register') }}">Sign up</a></small>
                </form>
            </div>





            @if (session('status'))
                <div class="modal fade" id="otpSentModal" tabindex="-1" aria-labelledby="successpasswordLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="p-4 m-auto modal-content w-50">
                            <img src="{{ asset('assets/images/success-logo.png') }}" alt="">
                            <p class="mt-3">Password Reset</p>
                        </div>
                    </div>
                </div>
                <script>
                    // Automatically show the modal if session message is set
                    document.addEventListener('DOMContentLoaded', function() {
                        var otpSentModal = new bootstrap.Modal(document.getElementById('otpSentModal'));
                        otpSentModal.show();
                    });
                </script>
            @endif

        </div>
    </x-auth.authorization-background>


@endsection
<script>
    // Function to toggle password visibility
    function togglePassword() {
        const passwordField = document.getElementById("password");
        const eyeIcon = document.getElementById("togglePassword");

        if (passwordField.type === "password") {
            passwordField.type = "text";
            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
        } else {
            passwordField.type = "password";
            eyeIcon.classList.remove("fa-eye-slash");
            eyeIcon.classList.add("fa-eye");
        }
    }

    function hideError(fieldName) {
        const errorMessage = document.getElementById('error-' + fieldName);
        if (errorMessage) {
            errorMessage.classList.add('d-none');
        }
    }
</script>
