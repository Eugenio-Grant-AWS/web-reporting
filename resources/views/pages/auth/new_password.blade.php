
@extends('layouts.app')
@section('title','Pages')
@section('content')
<x-auth.authorization-background>
<div class="col-lg-5 col-md-7 m-auto">
    <div class="new-password">
        <form action="{{ route('password.email') }}" method="POST" class="m-auto d-grid gap-4">
                 <h3>Forget Password</h3>
                    <div class="input-group " >
                        <i class="fas fa-lock"></i>
                        <input   type="password" placeholder="Enter New  Password" name="password" :value="old('email')" required autofocus>
                    </div>
                    <div class="input-group " >
                        <i class="fas fa-lock"></i>
                        <input   type="password" placeholder="Confirm New Password" name="confirmpassword" :value="old('email')" required autofocus>
                    </div>
                    <button class="theme-btn" type="submit" data-bs-toggle="modal" data-bs-target="#successpassword">Send</button>
                    <small class="text-danger">Passwords do not match</small>
             </form>
    </div>
    <!-- Button trigger modal -->

  
  <!-- Modal -->
  <div class="modal fade" id="successpassword" tabindex="-1" aria-labelledby="successpasswordLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content w-50 m-auto p-4">
        <img src="{{ asset('assets/images/success-logo.png') }}" alt="">
        <p class="mt-3">Password Reset</p>  
      </div>
    </div>
  </div>
</div>
</x-auth.authorization-background>


@endsection