@extends('layouts.app')
@section('title', 'Pages')
@section('content')
    <x-auth.authorization-background>
        <div class="m-auto col-lg-5 col-md-7">
            <div class="forget-password-form">
                <form action="{{ route('login') }}" method="POST" class="gap-4 m-auto d-grid">
                    @csrf
                    <h3>Sign In</h3>
                    <div class="input-group ">
                        <i class="fas fa-user"></i>
                        <input type="email" placeholder="Email" name="email" :value="old('email')"
                            value="{{ old('email') }}" autofocus>
                    </div>

                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                    <div class="input-group ">
                        <i class="fas fa-lock"></i>
                        <input type="password" placeholder="password" name="password" value="{{ old('password') }}"
                            autofocus>
                    </div>

                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                    @if (session('error'))
                        <small class="text-danger">{{ session('error') }}</small>
                    @endif

                    <ul class="d-md-flex align-items-center justify-content-between">
                        <li class="gap-2 d-flex align-items-center"><input type="checkbox" checked>
                            <p>Remember password</p>
                        </li>
                        <li><a href="{{ route('password.request') }}">Restore password</a></li>
                    </ul>
                    <button class="theme-btn" type="submit" data-bs-toggle="modal" data-bs-target="#successpassword">Sign
                        In</button>
                    <small>Don’t have an account? <a href="{{ route('register') }}">Sign up</a></small>
                </form>
            </div>
        </div>
    </x-auth.authorization-background>


@endsection
