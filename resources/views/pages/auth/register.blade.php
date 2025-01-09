@extends('layouts.app')
@section('title', 'Pages')
@section('content')
    <x-auth.authorization-background>
        <div class="m-auto col-md-7">
            <div class="signup-form">
                <form action="{{ route('register.store') }}" method="POST" class="gap-4 m-auto ">
                    @csrf
                    <h3 class="mb-4">Signup</h3>
                    <div class="row ">
                        <div class="col-md-6">
                            <div class="input-wrap mb-4">
                                <div class="input-group">
                                    <i class="fas fa-user"></i>
                                    <input type="text" placeholder="First Name" name="firstname"
                                        value="{{ old('firstname') }}" autofocus id="firstname"
                                        oninput="hideError('firstname')">
                                </div>

                                @error('firstname')
                                    <small class="text-danger d-flex align-items-baseline gap-1 text-start lh-base"
                                        id="error-firstname"><i
                                            class="fas fa-exclamation-circle"></i>{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-wrap mb-4">
                                <div class="input-group">
                                    <i class="fas fa-user"></i>
                                    <input type="text" placeholder="Last Name" name="lastname"
                                        class="{{ $errors->has('firstname') ? 'is-invalid' : '' }}"
                                        value="{{ old('lastname') }}" autofocus id="lastname"
                                        oninput="hideError('lastname')">

                                </div>

                                @error('lastname')
                                    <small class="text-danger d-flex align-items-baseline gap-1 text-start lh-base"
                                        id="error-lastname"><i class="fas fa-exclamation-circle"></i>{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="input-wrap mb-4">
                        <div class="input-group ">
                            <i class="fas fa-envelope"></i>
                            <input type="email" placeholder="E Mail" name="email" value="{{ old('email') }}" autofocus
                                id="email" oninput="hideError('email')">
                        </div>
                        @error('email')
                            <small class="text-danger d-flex align-items-baseline gap-1" id="error-email"><i
                                    class="fas fa-exclamation-circle"></i>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="input-wrap mb-4">
                        <div class="input-group ">
                            <i class="fas fa-lock"></i>
                            <input type="password" placeholder="Password" name="password" value="{{ old('password') }}"
                                autofocus id="password" oninput="hideError('password')">
                            <i class="fas fa-eye" id="togglePassword" onclick="togglePassword()"></i>
                        </div>

                        @error('password')
                            <small class="text-danger d-flex align-items-baseline gap-1" id="error-password"><i
                                    class="fas fa-exclamation-circle"></i>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="input-wrap mb-4">
                        <div class="input-group ">
                            <i class="fas fa-lock"></i>
                            <input type="password" placeholder="Confirm Password" name="password_confirmation" autofocus
                                id="password_confirmation" oninput="hideError('password_confirmation')">

                            <i class="fas fa-eye" id="toggleConfirmPassword" onclick="toggleConfirmPassword()"></i>

                        </div>

                        @error('password_confirmation')
                            <small class="text-danger
                                d-flex align-items-baseline gap-1"
                                id="error-password_confirmation"><i class="fas fa-exclamation-circle"></i>{{ $message }}
                            </small>
                        @enderror
                    </div>

                    <div class="input-wrap mb-4">
                        <ul class="gap-2 d-flex align-items-center">
                            <li><input type="checkbox" name="terms" value="1" {{ old('terms') ? 'checked' : '' }}
                                    id="terms" oninput="hideError('terms')">
                            </li>
                            <li>
                                <p class="m-0">I Agree on <a href="#">Terms & policies</a> </p>
                            </li>
                        </ul>
                        @error('terms')
                            <small class="text-danger d-flex align-items-baseline gap-1" id="error-terms"><i
                                    class="fas fa-exclamation-circle"></i>{{ $message }}</small>
                        @enderror
                    </div>

                    <button class="theme-btn mb-4" type="submit" data-bs-toggle="modal"
                        data-bs-target="#successpassword">Sign
                        Up</button>
                    <small class="d-block">Already have an account? <a href="{{ route('login') }}">Sign-in</a></small>
                </form>
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
