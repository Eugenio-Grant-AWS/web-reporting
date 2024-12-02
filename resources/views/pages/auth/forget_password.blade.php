
@extends('layouts.app')
@section('title','Pages')
@section('content')
<x-auth.authorization-background>
<div class="col-lg-5 col-md-7 m-auto">
    <div class="forget-password-form">
        <form action="{{ route('password.email') }}" method="POST" class="m-auto d-grid gap-4">
                 <h3>Forget Password</h3>
                    <div class="input-group " >
                        <i class="fas fa-user"></i> 
                        <input   type="email" placeholder="Enter you email " name="email" :value="old('email')" required autofocus>
                    </div>
                    <button class="theme-btn" type="submit" data-bs-toggle="modal" data-bs-target="#successpassword">Send</button>
                    <small>You will receive a 5 digit <span>OTP</span></small>
             </form>
        </div>
    </div>
</x-auth.authorization-background>


@endsection