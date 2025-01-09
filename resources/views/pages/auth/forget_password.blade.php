@extends('layouts.app')
@section('title', 'Pages')
@section('content')
    <x-auth.authorization-background>
        <div class="m-auto col-lg-5 col-md-7">
            <div class="forget-password-form">
                <form action="{{ route('password.sendOtp') }}" method="POST" class="gap-4 m-auto ">
                    @csrf
                    <h3 class="mb-4">Forget Password</h3>
                    <div class="mb-4 input-wrap">
                        <div class="input-group ">
                            <i class="fas fa-user"></i>
                            <input type="email" placeholder="Enter your email " name="email" :value="old('email')"
                                autofocus id="email" oninput="hideError('email')">
                        </div>
                        @error('email')
                            {{-- <small class="text-danger">{{ $message }}</small> --}}
                            <small class="gap-1 text-danger d-flex align-items-baseline" id="error-email"><i
                                    class="fas fa-exclamation-circle"></i>{{ $message }}</small>
                        @enderror
                    </div>

                    <button class="mb-4 theme-btn" type="submit" data-bs-toggle="modal"
                        data-bs-target="#successpassword">Send</button>
                    <small class="fw-bold">You will receive a 5 digit <span>OTP</span></small>
                </form>
            </div>
        </div>
    </x-auth.authorization-background>


@endsection

<script>
    function hideError(fieldName) {
        const errorMessage = document.getElementById('error-' + fieldName);
        if (errorMessage) {
            errorMessage.classList.add('d-none');
        }
    }
</script>
