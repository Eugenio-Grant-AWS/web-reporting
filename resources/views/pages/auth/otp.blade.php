
@extends('layouts.app')
@section('title','Pages')
@section('content')
<x-auth.authorization-background>
<div class="col-lg-5 col-md-7 m-auto">
    <div class="otp">
        <form action="{{ route('password.email') }}" method="POST" class="m-auto d-grid gap-4">
                 <h3>Forget Password</h3>
                    <div class="input-group " >
                        <i class="fas fa-thumbtack"></i>
                        <input   type="number"  maxlength="6" id="number-input" placeholder="Enter you 6 digit pin here " name="email" :value="old('email')" required >
                    </div>
                    <button class="theme-btn" type="submit">Send</button>
                    <small class="text-danger ">Invalid OTP. Please try again.</small>
             </form>
    </div>
    </div>
</x-auth.authorization-background>


@endsection