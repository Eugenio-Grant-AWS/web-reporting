@extends('layouts.app')
@section('title', 'Pages')
@section('content')
    <x-auth.authorization-background>
        <div class="m-auto col-lg-5 col-md-7">
            <div class="new-password">
                <form action="{{ route('password.store') }}" method="POST" class="gap-4 m-auto ">
                    @csrf
                    <h3 class="mb-4">Reset Password</h3>
                    <div class="mb-4 input-wrap">
                        <div class="input-group">
                            <i class="fas fa-lock"></i>
                            <input type="password" placeholder="Enter New Password" name="password" autofocus id="password"
                                oninput="hideError('password')">
                            <i class="fas fa-eye" id="togglePassword" onclick="togglePassword()"></i>
                        </div>
                        @error('password')
                            <small class="gap-1 text-danger d-flex align-items-baseline" id="error-password"><i
                                    class="fas fa-exclamation-circle"></i>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-4 input-wrap">
                        <div class="input-group">
                            <i class="fas fa-lock"></i>
                            <input type="password" placeholder="Confirm New Password" name="password_confirmation"
                                id="password_confirmation" oninput="hideError('password_confirmation')">

                            <i class="fas fa-eye" id="toggleConfirmPassword" onclick="toggleConfirmPassword()"></i>
                        </div>
                        @error('password_confirmation')
                            <small class="gap-1 text-danger d-flex align-items-baseline" id="error-password_confirmation"><i
                                    class="fas fa-exclamation-circle"></i>{{ $message }}</small>
                        @enderror
                    </div>

                    <button class="mb-4 theme-btn" type="submit">Reset Password</button>
                    {{-- @if ($errors->any())
                        <small class="gap-1 text-danger d-flex align-items-baseline justify-content-center"><i
                                class="fas fa-exclamation-circle"></i>{{ $message }}</small>
                    @endif --}}

                </form>
            </div>
            <!-- Button trigger modal -->


            <!-- Modal -->
            <div class="modal fade" id="successpassword" tabindex="-1" aria-labelledby="successpasswordLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="p-4 m-auto modal-content w-50">
                        <img src="{{ asset('assets/images/success-logo.png') }}" alt="">
                        <p class="mt-3">Password Reset</p>
                    </div>
                </div>
            </div>
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

    // Function to toggle confirm password visibility
    function toggleConfirmPassword() {
        const confirmPasswordField = document.getElementById("password_confirmation");
        const eyeIcon = document.getElementById("toggleConfirmPassword");

        if (confirmPasswordField.type === "password") {
            confirmPasswordField.type = "text";
            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
        } else {
            confirmPasswordField.type = "password";
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
