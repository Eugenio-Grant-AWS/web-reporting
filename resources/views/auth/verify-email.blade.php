
    @extends('layouts.app')
@section('title', 'Pages')
@section('content')
<style>
    .verification-link{
        i{
            font-size: 3.5rem;
            color: #666666;
        }
        .verify-btns{
            display: flex;
            align-items: center;
            justify-content: center;
            @media screen and (max-width: 489px) {
                display: inherit !important;
                button{
                    margin-bottom:10px;
                }
            }
        }
    }
</style>
    <x-auth.authorization-background>
        <div class="verification-link">
        <i class="fas fa-envelope-open-text mb-3"></i>
        <h3 class="mb-3">Thank You For Registeration!</h3>
            <div class="m-auto col-xl-5 col-lg-6 col-md-9">
              
               
                <p class="lh-base">We have sent a verification link to <strong>lorem@lorem.com</strong>. Click on the link in the email to complete your registration. If you don’t see the email, please check your spam folder</p>
                <div class="verify-btns mt-3">
                    <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
            
                        
                            <button type="submit"
                                class=" theme-btn2">
                                {{ __('Resend Verification Email') }}
                            </button>
                        </form>
            
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
            
                            <button type="submit"
                                class=" theme-btn2">
                                {{ __('Log Out') }} 
                            </button>
                        </form>
                </div>
            </div>
        </div>
    </x-auth.authorization-background>


@endsection


