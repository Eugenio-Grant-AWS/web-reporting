
@extends('layouts.app')
@section('title','Pages')
@section('content')
<x-auth.authorization-background>
<div class="col-lg-5 col-md-7 m-auto">
    <div class="forget-password-form">
        <form action="{{ route('login') }}" method="POST" class="m-auto d-grid gap-4">
            @csrf
                 <h3>Sign In</h3>
                    <div class="input-group " >
                        <i class="fas fa-user"></i> 
                        <input   type="email" placeholder="Email" name="email" :value="old('email')" required autofocus>
                    </div>
                    <div class="input-group " >
                        <i class="fas fa-lock"></i>
                        <input   type="password" placeholder="password" name="password"  required autofocus>
                    </div>
                    <ul class="d-md-flex align-items-center justify-content-between">
                        <li class="d-flex align-items-center gap-2"><input type="checkbox" checked><p>Remember password</p></li>
                        <li><a href="{{ route('password.request') }}">Restore password</a></li>
                    </ul>
                    <button class="theme-btn" type="submit" data-bs-toggle="modal" data-bs-target="#successpassword">Sign In</button>
                    <small>Don’t have an account? <a href="{{ route('register') }}">Sign up</a></small>
             </form>
        </div>
    </div>
</x-auth.authorization-background>


@endsection