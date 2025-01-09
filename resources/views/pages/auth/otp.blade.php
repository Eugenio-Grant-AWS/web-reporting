@extends('layouts.app')
@section('title', 'Enter OTP')
@section('content')
    <x-auth.authorization-background>
        <div class="m-auto col-lg-5 col-md-7">
            <div class="otp">
                <form action="{{ route('password.verifyOtp') }}" method="POST" class="gap-4 m-auto d-grid" id="otp-form">
                    @csrf
                    <h3>Verify OTP</h3>
                    <div class="input-group">
                        <i class="fas fa-thumbtack"></i>
                        <input type="number" maxlength="5" placeholder="Enter your 5 digit OTP here" name="otp"
                            id="otp" oninput="hideError('otp')">
                    </div>
                    <button id="otp-btn" class="theme-btn" type="submit" disabled>Verify OTP</button>
                    @if ($errors->any())
                        <small class="gap-1 text-danger d-flex align-items-baseline justify-content-center"
                            id="error-otp"><i class="fas fa-exclamation-circle"></i>Invalid OTP. Please try again.</small>
                    @endif
                    <p id="countdown" class="d-none">You can resend OTP in <span id="time-left">60</span> seconds</p>
                    <a id="resend-link" href="javascript:void(0);" class="d-none" onclick="resendOtp()">Click here to resend
                        OTP</a>
                </form>
            </div>
        </div>
    </x-auth.authorization-background>
@endsection

<script>
    // Function to hide error messages
    function hideError(fieldName) {
        const errorMessage = document.getElementById('error-' + fieldName);
        if (errorMessage) {
            errorMessage.classList.add('d-none');
        }
    }

    // Function to handle OTP resend logic and countdown
    function startCountdown(remainingTime) {
        const countdownElement = document.getElementById('countdown');
        const timeLeftElement = document.getElementById('time-left');
        const resendLink = document.getElementById('resend-link');
        const verifyOtpButton = document.getElementById('otp-btn');

        // Show countdown message and hide resend link
        countdownElement.classList.remove('d-none');
        resendLink.classList.add('d-none');

        verifyOtpButton.disabled = false;

        const countdownInterval = setInterval(function() {
            remainingTime--;
            timeLeftElement.textContent = remainingTime;

            if (remainingTime <= 0) {
                clearInterval(countdownInterval);
                countdownElement.classList.add('d-none');
                resendLink.classList.remove('d-none');
                verifyOtpButton.disabled = true;
            }
        }, 1000);
    }

    // Check if OTP has expired and if resend button should be visible
    document.addEventListener('DOMContentLoaded', function() {
        const otpExpiresAt = @json(session('otp_expires_at', null)); // Fetch the OTP expiration time
        const currentTime = new Date().getTime();

        // If OTP has been sent before, check if it has expired
        if (otpExpiresAt) {
            const otpExpirationTime = new Date(otpExpiresAt).getTime();
            const remainingTime = Math.floor((otpExpirationTime - currentTime) / 1000);

            if (remainingTime > 0) {
                startCountdown(remainingTime);
            } else {
                // OTP has expired, enable resend link
                document.getElementById('resend-link').classList.remove('d-none');
            }
        } else {
            // No OTP sent yet, enable resend link immediately
            document.getElementById('resend-link').classList.remove('d-none');
        }
    });

    // Function to resend OTP using AJAX
    function resendOtp() {
        const email = @json(session('otp_email'));

        if (!email) {
            alert('Session expired. Please request OTP again.');
            return;
        }

        // Disable the OTP form while sending the request
        document.getElementById('otp-btn').disabled = true;
        document.getElementById('otp').disabled = true;

        fetch('{{ route('password.resendOtp') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    email
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    startCountdown(60);

                    // Re-enable the OTP form after a successful resend
                    document.getElementById('otp-btn').disabled = false;
                    document.getElementById('otp').disabled = false;
                    document.getElementById('otp').value = ''; // Clear the old OTP field
                    document.getElementById('error-otp').classList.add('d-none'); // Hide any previous error
                }
            });
    }
</script>
