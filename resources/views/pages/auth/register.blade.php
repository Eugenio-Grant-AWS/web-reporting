@extends('layouts.app')
@section('title', 'Pages')
@section('content')
    <x-auth.authorization-background>
        <div class="m-auto col-md-7">
            <div class="signup-form">
                <form action="{{ route('register.store') }}" method="POST" class="gap-4 m-auto d-grid">
                    @csrf
                    <h3>Signup</h3>
                    <div class="row gy-4">
                        <div class="col-md-6">
                            <div class="input-group">
                                <i class="fas fa-user"></i>
                                <input type="text" placeholder="First Name" name="firstname"
                                    value="{{ old('firstname') }}" autofocus>
                            </div>

                            @error('firstname')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <i class="fas fa-user"></i>
                                <input type="text" placeholder="Last Name" name="lastname" value="{{ old('lastname') }}"
                                    autofocus>
                            </div>

                            @error('lastname')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="input-group ">
                        <i class="fas fa-envelope"></i>
                        <input type="email" placeholder="E Mail" name="email" value="{{ old('email') }}" autofocus>
                    </div>
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                    <div class="input-group ">
                        <i class="fas fa-lock"></i>
                        <input type="password" placeholder="Password" name="password" value="{{ old('password') }}"
                            autofocus>
                    </div>

                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                    <div class="input-group ">
                        <i class="fas fa-lock"></i>
                        <input type="password" placeholder="Confirm Password" name="confirmpassword" autofocus>
                    </div>

                    @error('confirmpassword')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                    <ul class="gap-2 d-flex align-items-center">
                        <li><input type="checkbox" checked></li>
                        <li>
                            <p class="m-0">I Agree on <a href="#">Terms & policies</a> </p>
                        </li>
                    </ul>
                    <button class="theme-btn" type="submit" data-bs-toggle="modal" data-bs-target="#successpassword">Sign
                        Up</button>
                    <small>Already have an account? <a href="{{ route('login') }}">Sign-in</a></small>
                </form>
            </div>
        </div>
    </x-auth.authorization-background>


@endsection
