
@extends('layouts.app')
@section('title','Pages')
@section('content')
<x-auth.authorization-background>
<div class="col-md-7 m-auto">
    <div class="signup-form">
        <form action="{{ route('register.store') }}" method="POST" class="m-auto d-grid gap-4">
            @csrf
                 <h3>Signup</h3>
                 <div class="row gy-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <i class="fas fa-user"></i>
                            <input type="text" placeholder="First Name" name="firstname"  required autofocus>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <i class="fas fa-user"></i>
                            <input type="text" placeholder="Last Name" name="lastname"  required autofocus>
                        </div>
                    </div>
                 </div>
                    <div class="input-group " >
                        <i class="fas fa-envelope"></i>
                        <input   type="email" placeholder="E Mail" name="email"  required autofocus>
                    </div>
                    <div class="input-group " >
                       <i class="fas fa-lock"></i>
                        <input type="password" placeholder="Password" name="password" required autofocus>
                    </div>
                    <div class="input-group " >
                       <i class="fas fa-lock"></i>
                        <input type="password" placeholder="Confirm Password" name="confirmpassword" required autofocus>
                    </div>
                    <ul class="d-flex align-items-center gap-2">
                        <li><input type="checkbox" checked></li>
                        <li><p class="m-0">I Agree on <a href="#">Terms & policies</a>  </p></li>
                    </ul>
                    <button class="theme-btn" type="submit" data-bs-toggle="modal" data-bs-target="#successpassword">Sign Up</button>
                    <small>Already have an account? <a href="#">Sign-in</a></small>
             </form>
        </div>
    </div>
</x-auth.authorization-background>


@endsection